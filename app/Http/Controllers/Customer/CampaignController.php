<?php

namespace App\Http\Controllers\Customer;

use App\Domain\Campaign\CampaignAnalyzer;
use App\Http\Controllers\Controller;
use App\Support\ActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CampaignController extends Controller
{
    public function __construct(private readonly CampaignAnalyzer $analyzer) {}

    public function show(Request $request): View
    {
        $customer = $request->user('customer');
        $profile = $customer->campaignProfile;
        $report = $this->analyzer->analyze($profile->branches ?? []);

        return view('customer.campaign.show', [
            'report' => $report,
            'profile' => $profile,
            'campaignName' => config('digisure.campaign.name'),
        ]);
    }

    public function selectReward(Request $request): RedirectResponse
    {
        $valid = $request->validate([
            'reward' => ['required', 'string', Rule::in(array_keys(config('digisure.campaign.rewards')))],
        ]);

        $customer = $request->user('customer');
        $profile = $customer->campaignProfile;

        abort_unless(
            $profile && $this->analyzer->analyze($profile->branches ?? [])->qualified,
            422,
            'Kampanya barajını henüz geçmediniz.',
        );

        $profile->update([
            'selected_reward' => $valid['reward'],
            'reward_selected_at' => now(),
            'reward_selected_by' => 'customer',
        ]);

        ActivityLogger::log('kampanya.odul_secildi', $customer, ['reward' => $valid['reward'], 'by' => 'customer']);

        return back()->with('status', 'Ödül seçiminiz kaydedildi.');
    }
}
