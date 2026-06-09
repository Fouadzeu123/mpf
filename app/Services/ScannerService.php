<?php

namespace App\Services;

use App\Enums\ScanMode;
use App\Enums\ServiceType;
use App\Models\Member;
use App\Models\Visitor;

class ScannerService
{
    public function __construct(
        protected AttendanceService $attendanceService,
        protected CommunionService $communionService,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function process(string $code, ScanMode $mode, ?ServiceType $serviceType = null): array
    {
        $member = Member::where('qr_code', $code)->orWhere('member_code', $code)->first();
        $visitor = $member ? null : Visitor::where('qr_code', $code)->first();

        if (! $member && ! $visitor) {
            return ['success' => false, 'message' => 'QR code non reconnu.'];
        }

        if ($visitor) {
            return [
                'success' => true,
                'type' => 'visitor',
                'visitor' => $visitor,
                'message' => 'Visiteur identifié.',
            ];
        }

        if ($mode === ScanMode::Attendance && ($serviceType ?? ServiceType::CulteDimanche) === ServiceType::CulteDimanche) {
            if (! now()->isSunday()) {
                return [
                    'success' => false,
                    'message' => 'Le scan pour le culte du dimanche est possible uniquement le dimanche.',
                ];
            }
        }

        return match ($mode) {
            ScanMode::Profile => [
                'success' => true,
                'type' => 'member',
                'member' => $this->formatMember($member),
                'message' => 'Profil membre.',
            ],
            ScanMode::Attendance => $this->handleAttendance($member, $serviceType ?? ServiceType::CulteDimanche),
            ScanMode::Communion => $this->handleCommunion($member),
        };
    }

    /** @return array<string, mixed> */
    protected function handleAttendance(Member $member, ServiceType $serviceType): array
    {
        $result = $this->attendanceService->record($member, $serviceType);

        return [
            'success' => $result['success'],
            'type' => 'attendance',
            'member' => $this->formatMember($member),
            'message' => $result['message'],
            'duplicate' => $result['duplicate'],
        ];
    }

    /** @return array<string, mixed> */
    protected function handleCommunion(Member $member): array
    {
        $alreadyToday = $member->communionPreparations()
            ->whereDate('created_at', today())
            ->exists();

        if ($alreadyToday) {
            return [
                'success' => false,
                'type' => 'communion',
                'member' => $this->formatMember($member),
                'message' => 'Préparation déjà enregistrée aujourd\'hui.',
            ];
        }

        $preparation = $this->communionService->prepare($member);

        return [
            'success' => true,
            'type' => 'communion',
            'member' => $this->formatMember($member),
            'preparation' => $preparation,
            'message' => 'Carte préparée.',
        ];
    }

    /** @return array<string, mixed> */
    protected function formatMember(Member $member): array
    {
        return [
            'id' => $member->id,
            'member_code' => $member->member_code,
            'full_name' => $member->full_name,
            'photo_url' => $member->photo_url,
            'department' => $member->department,
            'status' => $member->status?->value ?? $member->status,
            'age' => $member->age,
            'gender' => $member->gender,
            'phone' => $member->phone,
            'address_description' => $member->address_description,
        ];
    }
}
