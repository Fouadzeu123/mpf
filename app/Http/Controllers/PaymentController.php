<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Models\Member;
use App\Models\PaymentTransaction;
use App\Services\CommunionService;
use App\Services\NotchPayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    public function __construct(
        protected NotchPayService $notchPayService,
        protected CommunionService $communionService,
    ) {}

    public function index(): Response
    {
        return Inertia::render('payments/Index', [
            'transactions' => PaymentTransaction::with('member')->latest()->paginate(20),
        ]);
    }

    public function create(Request $request): Response
    {
        $member = $request->user('member') ?? Member::findOrFail($request->member_id);

        return Inertia::render('payments/Create', [
            'member' => $member,
            'amount' => $this->communionService->amountFor($member),
            'isPreparationDay' => $this->communionService->isPreparationDay(),
        ]);
    }

    public function initiate(Request $request): RedirectResponse
    {
        $member = auth('member')->user() ?? Member::findOrFail($request->member_id);

        $transaction = $this->notchPayService->initiate(
            $member,
            $this->communionService->amountFor($member),
            'Préparation Sainte Cène',
        );

        if (! config('services.notchpay.private_key')) {
            $this->communionService->prepare($member, remote: true, paymentReference: $transaction->reference);
            $transaction->update(['status' => PaymentStatus::Paid]);

            return redirect()->route('member.portal')
                ->with('toast', ['type' => 'success', 'message' => 'Préparation enregistrée (mode démo).']);
        }

        return redirect()->route('payments.verify', $transaction->reference);
    }

    public function callback(Request $request): RedirectResponse
    {
        $reference = $request->get('reference') ?? $request->get('trxref');

        if ($reference) {
            return redirect()->route('payments.verify', $reference);
        }

        return redirect()->route('member.portal')->with('toast', ['type' => 'error', 'message' => 'Paiement annulé.']);
    }

    public function verify(string $reference): RedirectResponse
    {
        $transaction = $this->notchPayService->verify($reference);
        $member = $transaction->member;

        if ($transaction->status === PaymentStatus::Paid) {
            if ($transaction->type === 'event_contribution') {
                \App\Models\Contribution::where('payment_reference', $reference)->update(['payment_status' => 'paid']);

                return redirect()->route('member.portal')
                    ->with('toast', ['type' => 'success', 'message' => 'Paiement validé. Contribution enregistrée.']);
            } else {
                $this->communionService->prepare($member, remote: true, paymentReference: $reference);

                return redirect()->route('member.portal')
                    ->with('toast', ['type' => 'success', 'message' => 'Paiement validé. Préparation enregistrée.']);
            }
        }

        if ($transaction->type === 'event_contribution') {
            return redirect()->route('member.portal')
                ->with('toast', ['type' => 'warning', 'message' => 'Paiement de la contribution en attente de confirmation.']);
        }

        return redirect()->route('payments.create', ['member_id' => $member->id])
            ->with('toast', ['type' => 'warning', 'message' => 'Paiement en attente de confirmation.']);
    }

    public function initiateContribution(Request $request, \App\Models\Event $event): RedirectResponse
    {
        $request->validate([
            'amount' => ['required', 'integer', 'min:100'],
        ]);

        $member = auth('member')->user();
        if (!$member) {
            return redirect()->back()->with('toast', ['type' => 'error', 'message' => 'Non autorisé.']);
        }

        $amount = (int) $request->amount;

        // Create pending contribution
        $contribution = \App\Models\Contribution::create([
            'event_id' => $event->id,
            'member_id' => $member->id,
            'amount' => $amount,
            'payment_status' => 'pending',
            'payment_method' => 'mobile_money',
        ]);

        $transaction = $this->notchPayService->initiate(
            $member,
            $amount,
            'Contribution : ' . $event->title,
            'event_contribution',
            $event->id
        );

        $contribution->update([
            'payment_reference' => $transaction->reference,
        ]);

        if (! config('services.notchpay.private_key')) {
            $contribution->update(['payment_status' => 'paid']);
            $transaction->update(['status' => PaymentStatus::Paid]);

            return redirect()->route('member.portal')
                ->with('toast', ['type' => 'success', 'message' => 'Contribution enregistrée avec succès (mode démo).']);
        }

        return redirect()->route('payments.verify', $transaction->reference);
    }
}
