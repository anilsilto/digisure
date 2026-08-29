<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'subject_type',
        'subject_id',
        'action',
        'changes',
        'ip',
    ];

    protected function casts(): array
    {
        return ['changes' => 'array'];
    }
}
