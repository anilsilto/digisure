<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignProfile extends Model
{
    protected $fillable = [
        'customer_id',
        'branches',
        'selected_reward',
        'reward_selected_at',
        'reward_selected_by',
        'updated_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'branches' => 'array',
            'reward_selected_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function rewardLabel(): ?string
    {
        return $this->selected_reward
            ? config("digisure.campaign.rewards.{$this->selected_reward}.label")
            : null;
    }
}
