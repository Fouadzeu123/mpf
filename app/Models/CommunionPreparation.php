<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunionPreparation extends Model
{
    protected $fillable = [
        'member_id',
        'verse_reference',
        'verse_text',
        'whatsapp_sent',
        'payment_status',
        'payment_reference',
        'remote',
    ];

    protected function casts(): array
    {
        return [
            'whatsapp_sent' => 'boolean',
            'payment_status' => PaymentStatus::class,
            'remote' => 'boolean',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
