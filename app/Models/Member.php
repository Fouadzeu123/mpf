<?php

namespace App\Models;

use App\Enums\MemberStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Member extends Authenticatable
{
    use HasFactory;

    protected $appends = ['full_name', 'photo_url'];

    protected $fillable = [
        'member_code',
        'first_name',
        'last_name',
        'photo',
        'age',
        'gender',
        'phone',
        'address_description',
        'department',
        'status',
        'qr_code',
        'latitude',
        'longitude',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'status' => MemberStatus::class,
        ];
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function communionPreparations(): HasMany
    {
        return $this->hasMany(CommunionPreparation::class);
    }

    public function verseHistory(): HasMany
    {
        return $this->hasMany(MemberVerseHistory::class);
    }

    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->photo) {
            return null;
        }

        return str_starts_with($this->photo, 'http')
            ? $this->photo
            : asset('storage/'.$this->photo);
    }

    public function checkPassword(string $plain): bool
    {
        return Hash::check(strtolower($plain), $this->password)
            || Hash::check($plain, $this->password);
    }

}
