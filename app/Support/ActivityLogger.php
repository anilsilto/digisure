<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogger
{
    /**
     * Panel/müşteri kullanıcısının bir kayıt üzerindeki eylemini denetim günlüğüne yazar.
     *
     * @param  array<string, mixed>  $changes
     */
    public static function log(string $action, Model $subject, array $changes = []): void
    {
        ActivityLog::create([
            'user_id' => auth('panel')->id(),
            'subject_type' => $subject::class,
            'subject_id' => $subject->getKey(),
            'action' => $action,
            'changes' => $changes ?: null,
            'ip' => request()?->ip(),
        ]);
    }
}
