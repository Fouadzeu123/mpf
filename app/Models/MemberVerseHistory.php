<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberVerseHistory extends Model
{
    protected $table = 'member_verse_history';

    protected $fillable = [
        'member_id',
        'verse_reference',
        'verse_text',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
