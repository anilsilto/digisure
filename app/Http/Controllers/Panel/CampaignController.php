<?php

namespace App\Http\Controllers\Panel;

use App\Domain\Campaign\CampaignAnalyzer;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Support\ActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CampaignController extends Controller
{
    public function __construct(private readonly CampaignAnalyzer $analyzer) {}

    public function index(): View
    {
        $customers = Customer::query()
            ->with('campaignProfile')
            ->orderByDesc('id')
            ->paginate(25);

        return view('panel.campaign.index', [
            'customers' => $customers,
            'analyzer' => $this->analyzer,
        ]);
    }

    public function show(Customer $customer): View
    {
        $profile = $customer->campaignProfile;
        $report = $this->analyzer->analyze($profile->branches ?? []);

        return view('panel.campaign.show', [
            'customer' => $customer,
            'profile' => $profile,
            'report' => $report,
            'pitch' => $this->analyzer->pitch($customer->first_name, $report, $profile?->rewardLabel()),
            'branchConfig' => config('digisure.campaign.branches'),
        ]);
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $valid = $request->validate([
            'branches' => ['array'],
            'branches.*' => ['string', Rule::in(array_keys(config('digisure.campaign.branches')))],
        ]);
        $branches = array_values(array_unique($valid['branches'] ?? []));

        $profile = $customer->campaignProfile()->firstOrNew([]);
        $profile->branches = $branches;
        $profile->updated_by_user_id = $request->user('panel')->id;

        // Baraj artık geçilmiyorsa seçili ödül düşer.
        if (! $this->analyzer->analyze($branches)->qualified) {
            $profile->selected_reward = null;
            $profile->reward_selected_at = null;
            $profile->reward_selected_by = null;
        }
        $profile->save();

        ActivityLogger::log('kampanya.branslar_guncellendi', $customer, ['branches' => $branches]);

        return back()->with('status', 'Kampanya branşları güncellendi.');
    }

    public function selectReward(Request $request, Customer $customer): RedirectResponse
    {
        $valid = $request->validate([
            'reward' => ['required', 'string', Rule::in(array_keys(config('digisure.campaign.rewards')))],
        ]);

        $profile = $customer->campaignProfile;
        abort_unless(
            $profile && $this->analyzer->analyze($profile->branches ?? [])->qualified,
            422,
            'Müşteri kampanya barajını geçmedi.',
        );

        $profile->update([
            'selected_reward' => $valid['reward'],
            'reward_selected_at' => now(),
            'reward_selected_by' => 'panel',
        ]);

        ActivityLogger::log('kampanya.odul_secildi', $customer, ['reward' => $valid['reward'], 'by' => 'panel']);

        return back()->with('status', 'Ödül kaydedildi.');
    }
}
