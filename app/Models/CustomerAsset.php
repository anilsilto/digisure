<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAsset extends Model
{
    /** Varlık tipleri (kişi hariç — o RiskAnalyzer'da örtük değerlendirilir). */
    public const TYPES = [
        'arac' => 'Araç',
        'konut' => 'Konut',
        'isyeri' => 'İşyeri',
    ];

    protected $fillable = [
        'customer_id',
        'type',
        'label',
        'meta',
        'source',
        'status',
        'created_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
