<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_request_id',
        'insurer',
        'status',
        'premium',
        'coverage_summary',
        'policy_period_months',
        'insurer_quote_no',
        'valid_until',
        'origin',
        'file_path',
        'entered_by',
    ];

    protected function casts(): array
    {
        return [
            'coverage_summary' => 'array',
            'valid_until' => 'date',
            'premium' => 'decimal:2',
        ];
    }

    public function quoteRequest(): BelongsTo
    {
        return $this->belongsTo(QuoteRequest::class);
    }

    public function enteredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entered_by');
    }

    public function insurerLabel(): string
    {
        return config("digisure.insurer_labels.{$this->insurer}", $this->insurer);
    }
}
