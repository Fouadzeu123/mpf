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
        $member->load(['verseHistory' => fn ($q) => $q->latest()->limit(20), 'communionPreparations' => fn ($q) => $q->latest()->limit(10)]);

        $preparedToday = $member->communionPreparations()
            ->whereDate('created_at', today())
            ->exists();

        return Inertia::render('member/Portal', [
            'member' => $member,
            'qrDataUri' => $this->qrCodeService->generateDataUri($member->qr_code),
            'preparedToday' => $preparedToday,
            'communionFee' => config('church.communion_fee'),
            'programs' => config('church.programs'),
        ]);
    }
}
