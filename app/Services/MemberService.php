<?php

namespace App\Services;

use App\Enums\MemberStatus;
use App\Models\Member;
use App\Models\Visitor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MemberService
{
    public function __construct(
        protected MemberCodeService $memberCodeService,
        protected QrCodeService $qrCodeService,
        protected ActivityLogService $activityLogService,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data, ?UploadedFile $photo = null): Member
    {
        $memberCode = $this->memberCodeService->generate();
        $password = $data['password'] ?? $this->memberCodeService->defaultPassword($memberCode);

        $photoPath = null;
        if ($photo) {
            $photoPath = $this->storePhoto($photo);
        } elseif (!empty($data['photo']) && is_string($data['photo'])) {
            $photoPath = $data['photo'];
        }

        $member = Member::create([
            'member_code' => $memberCode,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'birth_date' => $data['birth_date'] ?? null,
            'gender' => $data['gender'] ?? null,
            'profession' => $data['profession'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address_description' => $data['address_description'] ?? null,
            'department' => $data['department'] ?? null,
            'status' => $data['status'] ?? MemberStatus::Active,
            'qr_code' => $memberCode,
            'password' => Hash::make(strtolower($password)),
            'photo' => $photoPath,
        ]);

        $this->activityLogService->log('member_created', meta: ['member_id' => $member->id]);

        return $member;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Member $member, array $data, ?UploadedFile $photo = null): Member
    {
        if ($photo) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $data['photo'] = $this->storePhoto($photo);
        } elseif (isset($data['photo']) && is_string($data['photo']) && $data['photo'] !== $member->photo) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
        }

        if (! empty($data['password'])) {
            $data['password'] = Hash::make(strtolower($data['password']));
        } else {
            unset($data['password']);
        }

        $member->update($data);
        $this->activityLogService->log('member_updated', meta: ['member_id' => $member->id]);

        return $member->fresh();
    }

    public function updateLocation(Member $member, float $latitude, float $longitude): Member
    {
        $member->update([
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        $this->activityLogService->log('member_gps_saved', meta: [
            'member_id' => $member->id,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        return $member->fresh();
    }

    public function fromVisitor(Visitor $visitor, array $extra = []): Member
    {
        return $this->create(array_merge([
            'first_name' => $visitor->first_name,
            'last_name' => $visitor->last_name,
            'phone' => $visitor->phone,
            'address_description' => $visitor->address_description,
        ], $extra));
    }

    protected function storePhoto(UploadedFile $photo): string
    {
        return $photo->store('members', 'public');
    }
}
