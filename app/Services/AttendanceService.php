<?php

namespace App\Services;

use App\Enums\ServiceType;
use App\Models\Attendance;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function __construct(
        protected ActivityLogService $activityLogService,
    ) {}

    public function record(Member $member, ServiceType $serviceType): array
    {
        $today = Carbon::today();

        $exists = Attendance::where('member_id', $member->id)
            ->where('service_type', $serviceType)
            ->whereDate('scanned_at', $today)
            ->exists();

        if ($exists) {
            return [
                'success' => false,
                'message' => 'Présence déjà enregistrée aujourd\'hui.',
                'duplicate' => true,
            ];
        }

        $attendance = Attendance::create([
            'member_id' => $member->id,
            'scanned_by' => auth()->id(),
            'service_type' => $serviceType,
            'scanned_at' => now(),
        ]);

        $this->activityLogService->log('attendance_recorded', meta: [
            'member_id' => $member->id,
            'service' => $serviceType->value,
        ]);

        return [
            'success' => true,
            'message' => 'Présence enregistrée.',
            'attendance' => $attendance,
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
