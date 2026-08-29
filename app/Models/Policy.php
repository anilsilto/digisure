<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_type_id',
        'insurer',
        'policy_no',
        'start_date',
        'end_date',
        'premium',
        'quote_id',
        'file_path',
        'status',
        'renewed_from_policy_id',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'premium' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(RenewalReminder::class);
    }

    public function insurerLabel(): string
    {
        return config("digisure.insurer_labels.{$this->insurer}", $this->insurer);
    }
}
