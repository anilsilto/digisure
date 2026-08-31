<?php

namespace App\Models;

use App\Support\Pii;
use Database\Factories\CustomerFactory;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model implements Authenticatable
{
    /** @use HasFactory<CustomerFactory> */
    use AuthenticatableTrait, HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'tc_no',
        'phone',
        'email',
        'birth_date',
        'address',
        'kvkk_consent_at',
        'marketing_consent_at',
    ];

    // tc_hash / phone_hash $fillable dışında => toplu atanamaz; saving hook'u doldurur.

    protected function casts(): array
    {
        return [
            'tc_no' => 'encrypted',
            'phone' => 'encrypted',
            'birth_date' => 'date',
            'kvkk_consent_at' => 'datetime',
            'marketing_consent_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Customer $customer) {
            if ($customer->isDirty('tc_no') && filled($customer->tc_no)) {
                $customer->tc_hash = Pii::hash($customer->tc_no);
            }
            if ($customer->isDirty('phone') && filled($customer->phone)) {
                $customer->phone_hash = Pii::hash($customer->phone);
            }
        });
    }

    public function policies(): HasMany
    {
        return $this->hasMany(Policy::class);
    }

    public function quoteRequests(): HasMany
    {
        return $this->hasMany(QuoteRequest::class);
    }

    public function campaignProfile(): HasOne
    {
        return $this->hasOne(CampaignProfile::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(CustomerAsset::class);
    }

    public function scopeWhereTc(Builder $query, string $tc): Builder
    {
        return $query->where('tc_hash', Pii::hash($tc));
    }

    public function scopeWherePhone(Builder $query, string $phone): Builder
    {
        return $query->where('phone_hash', Pii::hash($phone));
    }

    /**
     * TC'ye göre müşteriyi bulur veya oluşturur; verilen alanları günceller.
     */
    public static function upsertByTc(array $attributes): self
    {
        return static::updateOrCreate(
            ['tc_hash' => Pii::hash($attributes['tc_no'])],
            $attributes,
        );
    }
}
