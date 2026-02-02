<?php

namespace App\Http\Controllers;

use App\Models\{Campaign, Category, SubCategory};
use Illuminate\Http\Request;

class PublicCampaignController extends Controller
{
    public function index(Request $request)
    {
        $campaigns = Campaign::query()
            // ->where('approved', true)
            ->where('is_completed', false)
            ->where('status', 'Live')
            ->with(['campaignType', 'campaignCategory'])
            ->when($request->search, function ($query, $search) {
                $query->where('post_title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->type, function ($query, $type) {
                $query->where('campaign_type', $type);
            })
            ->when($request->category, function ($query, $category) {
                $query->where('campaign_subcategory', $category);
            })
            ->orderByRaw("
                CASE
                    WHEN job_id = 'Lgh1yOgwO' THEN 0
                    WHEN approved IN ('Priotized','Priotize') THEN 1
                    ELSE 2
                END
            ")
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $campaignTypes = Category::all();
        $categories = SubCategory::all();

        return view('campaigns.index', compact('campaigns', 'campaignTypes', 'categories'));
    }

    public function show($job_id)
    {
        try {
            $campaign = $this->getCampaignData($job_id);

            if (!$campaign) {
                return view('campaigns.not-found');
            }

            if ($campaign['is_completed']) {
                return view('campaigns.completed', ['campaign' => $campaign]);
            }

            // Increment impressions
            Campaign::where('job_id', $job_id)->increment('impressions');

            return view('campaigns.show', ['campaign' => $campaign]);
        } catch (\Exception $e) {
            return view('campaigns.not-found');
        }
    }

     public function show2($job_id)
    {
        try {
            $campaign = $this->getCampaignData($job_id);

            if (!$campaign) {
                return view('campaigns.not-found');
            }

            if ($campaign['is_completed']) {
                return view('campaigns.completed', ['campaign' => $campaign]);
            }

            // Increment impressions
            Campaign::where('job_id', $job_id)->increment('impressions');

            return view('campaign-view', ['campaign' => $campaign]);
        } catch (\Exception $e) {
            return view('campaigns.not-found');
        }
    }

    private function getCampaignData($job_id)
    {
        $campaign = Campaign::where('job_id', $job_id)
            ->with(['campaignType', 'campaignCategory'])
            ->first();

        if (!$campaign) {
            return null;
        }

        return [
            'job_id' => $campaign->job_id,
            'post_title' => $campaign->post_title,
            'post_link' => $campaign->post_link,
            'description' => $campaign->description,
            'proof' => $campaign->proof,
            'campaign_amount' => $campaign->campaign_amount,
            'currency' => $campaign->currency,
            'number_of_staff' => $campaign->number_of_staff,
            'completed_count' => $campaign->completed_count,
            'pending_count' => $campaign->pending_count,
            'impressions' => $campaign->impressions,
            'approval_time' => $campaign->approval_time,
            'is_completed' => $campaign->is_completed,
            'approved' => $campaign->approved,
            'created_at' => $campaign->created_at,
            'campaign_type' => $campaign->campaign_type,
            'campaignType' => [
                'id' => $campaign->campaignType->id ?? null,
                'name' => $campaign->campaignType->name ?? 'N/A',
            ],
            'campaignCategory' => [
                'id' => $campaign->campaignCategory->id ?? null,
                'name' => $campaign->campaignCategory->name ?? 'N/A',
            ],
            // For backwards compatibility with old view
            'local_converted_currency' => $campaign->currency,
            'local_converted_amount' => number_format($campaign->campaign_amount, 2),
        ];
    }
}
