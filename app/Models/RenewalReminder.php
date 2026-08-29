<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RenewalReminder extends Model
{
    protected $fillable = [
        'policy_id',
        'remind_on',
        'channel',
        'status',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'remind_on' => 'date',
            'sent_at' => 'datetime',
        ];
    }

    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }
}
