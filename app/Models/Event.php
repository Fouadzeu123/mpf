<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'location',
        'start_date',
        'end_date',
        'banner',
    ];

    protected $appends = ['banner_url'];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    public function contributions(): HasMany
    {
        return $this->hasMany(Contribution::class);
    }

    public function getBannerUrlAttribute(): ?string
    {
        if (!$this->banner) {
            return null;
        }

        return str_starts_with($this->banner, 'http')
            ? $this->banner
            : asset('storage/' . $this->banner);
    }

    public function getTotalContributionsAttribute(): int
    {
        return (int) $this->contributions()->where('payment_status', 'paid')->sum('amount');
    }
}
