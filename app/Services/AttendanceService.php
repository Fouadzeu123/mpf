<?php

namespace App\Services;

use App\Enums\ServiceType;
use App\Models\Attendance;
use App\Models\Member;
use App\Models\MemberVerseHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceService
{
    public function __construct(
        protected ActivityLogService $activityLogService,
        protected BibleVerseService $bibleVerseService,
        protected WhatsAppService $whatsAppService,
    ) {}

    public function record(Member $member, ServiceType $serviceType): array
    {
        $today = Carbon::today();

        // 1. Pre-check if card has already been scanned today (any service type)
        $existing = Attendance::where('member_id', $member->id)
            ->whereDate('scanned_at', $today)
            ->first();

        if ($existing) {
            return [
                'success' => false,
                'message' => 'Carte déjà scannée aujourd\'hui à ' . $existing->scanned_at->format('H:i') . ' (Doublon refusé).',
                'duplicate' => true,
                'attendance' => $existing,
            ];
        }

        // 2. Atomic creation to prevent race condition duplicates
        $attendance = DB::transaction(function () use ($member, $serviceType, $today) {
            $already = Attendance::where('member_id', $member->id)
                ->whereDate('scanned_at', $today)
                ->lockForUpdate()
                ->first();

            if ($already) {
                return $already;
            }

            return Attendance::create([
                'member_id' => $member->id,
                'scanned_by' => auth()->id(),
                'service_type' => $serviceType,
                'scanned_at' => now(),
            ]);
        });

        if (! $attendance->wasRecentlyCreated) {
            return [
                'success' => false,
                'message' => 'Carte déjà scannée aujourd\'hui à ' . $attendance->scanned_at->format('H:i') . ' (Doublon refusé).',
                'duplicate' => true,
                'attendance' => $attendance,
            ];
        }

        // 3. Generate member's Bible verse for today
        $verse = $this->bibleVerseService->randomVerse();

        MemberVerseHistory::create([
            'member_id' => $member->id,
            'verse_reference' => $verse['reference'],
            'verse_text' => $verse['text'],
        ]);

        // 4. Send directly via WhatsApp to member
        $whatsappSent = false;
        if (! empty($member->phone)) {
            try {
                $whatsappSent = $this->whatsAppService->sendAttendanceVerse(
                    $member,
                    $verse['reference'],
                    $verse['text'],
                    $attendance->scanned_at->format('H:i')
                );
            } catch (\Throwable $e) {
                Log::error('Attendance WhatsApp sending failed: ' . $e->getMessage());
            }
        }

        $this->activityLogService->log('attendance_recorded', meta: [
            'member_id' => $member->id,
            'service' => $serviceType->value,
            'verse' => $verse['reference'],
            'whatsapp_sent' => $whatsappSent,
        ]);

        $statusMsg = $whatsappSent
            ? "Verset WhatsApp envoyé au membre (" . $member->phone . ")."
            : "Présence validée.";

        return [
            'success' => true,
            'message' => 'Présence enregistrée à ' . $attendance->scanned_at->format('H:i') . '. ' . $statusMsg,
            'attendance' => $attendance,
            'verse' => $verse,
            'whatsapp_sent' => $whatsappSent,
            'duplicate' => false,
        ];
    }

    /** @return array<string, int> */
    public function todayStats(): array
    {
        return [
            'total' => Attendance::whereDate('scanned_at', today())->count(),
            'by_service' => Attendance::whereDate('scanned_at', today())
                ->select('service_type', DB::raw('count(*) as total'))
                ->groupBy('service_type')
                ->pluck('total', 'service_type')
                ->toArray(),
        ];
    }
}
