<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Jobs\SendBibleVerseJob;
use App\Models\CommunionPreparation;
use App\Models\Member;
use App\Models\MemberVerseHistory;
use Illuminate\Validation\ValidationException;

class CommunionService
{
    public function __construct(
        protected BibleVerseService $bibleVerseService,
        protected ActivityLogService $activityLogService,
    ) {}

    public function prepare(Member $member, bool $remote = false, ?string $paymentReference = null): CommunionPreparation
    {
        if (! $this->isPreparationDay()) {
            throw ValidationException::withMessages([
                'preparation' => 'La préparation se fait uniquement le jour défini pour la préparation à la Sainte Cène.',
            ]);
        }

        $verse = $this->bibleVerseService->randomVerse();

        $preparation = CommunionPreparation::create([
            'member_id' => $member->id,
            'verse_reference' => $verse['reference'],
            'verse_text' => $verse['text'],
            'whatsapp_sent' => false,
            'payment_status' => $paymentReference ? PaymentStatus::Paid : PaymentStatus::Free,
            'payment_reference' => $paymentReference,
            'remote' => $remote,
        ]);

        MemberVerseHistory::create([
            'member_id' => $member->id,
            'verse_reference' => $verse['reference'],
            'verse_text' => $verse['text'],
        ]);

        // Send verse asynchronously via queue
        SendBibleVerseJob::dispatch($member, $verse['reference']);

        $this->activityLogService->log('communion_prepared', meta: [
            'member_id' => $member->id,
            'reference' => $verse['reference'],
            'remote' => $remote,
        ]);

        return $preparation->fresh();
    }

    public function amountFor(Member $member): int
    {
        return ($member->age !== null && $member->age < 18)
            ? (int) config('church.communion_student_fee')
            : (int) config('church.communion_worker_fee');
    }

    public function isPreparationDay(): bool
    {
        if (auth()->check() && in_array(auth()->user()->role->value ?? auth()->user()->role, [
            'admin',
            'secretaire',
            'ancienne'
        ], true)) {
            return true;
        }

        if (app()->environment('local') || config('app.debug')) {
            return true;
        }

        $customDate = \App\Models\Setting::where('key', 'communion_preparation_date')->value('value');
        if ($customDate) {
            return now()->toDateString() === $customDate;
        }

        $today = now();

        return $today->isSaturday()
            && $today->day >= 8
            && $today->day <= 14;
    }
}
