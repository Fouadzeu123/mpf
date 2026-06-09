<?php

namespace App\Http\Controllers;

use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MemberPortalController extends Controller
{
    public function __construct(
        protected QrCodeService $qrCodeService,
    ) {}

    public function index(Request $request): Response
    {
        $member = $request->user('member');
        $member->load([
            'verseHistory' => fn ($q) => $q->latest()->limit(20),
            'communionPreparations' => fn ($q) => $q->latest()->limit(10),
            'contributions.event'
        ]);

        $preparedToday = $member->communionPreparations()
            ->whereDate('created_at', today())
            ->exists();

        $upcomingEvents = \App\Models\Event::where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->get()
            ->map(fn ($e) => [
                'id' => $e->id,
                'title' => $e->title,
                'description' => $e->description,
                'location' => $e->location,
                'start_date' => $e->start_date->format('d/m/Y H:i'),
                'end_date' => $e->end_date ? $e->end_date->format('d/m/Y H:i') : null,
                'banner_url' => $e->banner_url,
            ]);

        $pastEvents = \App\Models\Event::where('start_date', '<', now())
            ->orderBy('start_date', 'desc')
            ->get()
            ->map(fn ($e) => [
                'id' => $e->id,
                'title' => $e->title,
                'description' => $e->description,
                'location' => $e->location,
                'start_date' => $e->start_date->format('d/m/Y H:i'),
                'end_date' => $e->end_date ? $e->end_date->format('d/m/Y H:i') : null,
                'banner_url' => $e->banner_url,
            ]);

        $contributions = $member->contributions()
            ->where('payment_status', 'paid')
            ->latest()
            ->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'event_title' => $c->event->title,
                'amount' => $c->amount,
                'payment_method' => $c->payment_method === 'manual' ? 'Manuel (Espèces)' : 'Mobile Money',
                'date' => $c->created_at->format('d/m/Y H:i'),
            ]);

        $totalContributions = (int) $member->contributions()
            ->where('payment_status', 'paid')
            ->sum('amount');

        return Inertia::render('member/Portal', [
            'member' => $member,
            'qrDataUri' => $this->qrCodeService->generateDataUri($member->qr_code),
            'preparedToday' => $preparedToday,
            'communionFee' => config('church.communion_fee'),
            'programs' => config('church.programs'),
            'upcomingEvents' => $upcomingEvents,
            'pastEvents' => $pastEvents,
            'contributions' => $contributions,
            'totalContributions' => $totalContributions,
        ]);
    }
}
