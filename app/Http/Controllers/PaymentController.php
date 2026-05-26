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
            $this->communionService->prepare($member, remote: true, paymentReference: $reference);

            return redirect()->route('member.portal')
                ->with('toast', ['type' => 'success', 'message' => 'Paiement validé. Préparation enregistrée.']);
        }

        return redirect()->route('payments.create', ['member_id' => $member->id])
            ->with('toast', ['type' => 'warning', 'message' => 'Paiement en attente de confirmation.']);
    }
}
