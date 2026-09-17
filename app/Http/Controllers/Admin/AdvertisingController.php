<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdAnalyticsEvent;
use App\Models\AdCode;
use App\Models\AdPlacement;
use App\Models\AdvertisingConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdvertisingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the Advertising Management Dashboard.
     */
    public function index(Request $request)
    {
        $config = AdvertisingConfig::current();

        $period = $request->query('period', '7d');
        $since = null;
        $periodLabel = 'Last 7 Days';

        switch ($period) {
            case '24h':
            case '1d':
                $since = now()->subHours(24);
                $periodLabel = 'Last 24 Hours';
                $period = '24h';
                break;
            case '30d':
            case '30':
                $since = now()->subDays(30);
                $periodLabel = 'Last 30 Days';
                $period = '30d';
                break;
            case '60d':
            case '60':
                $since = now()->subDays(60);
                $periodLabel = 'Last 60 Days';
                $period = '60d';
                break;
            case '90d':
            case '90':
                $since = now()->subDays(90);
                $periodLabel = 'Last 90 Days';
                $period = '90d';
                break;
            case 'all':
                $since = null;
                $periodLabel = 'All Time';
                $period = 'all';
                break;
            case '7d':
            case '7':
            default:
                $since = now()->subDays(7);
                $periodLabel = 'Last 7 Days';
                $period = '7d';
                break;
        }

        // Telemetry summary for the chosen period
        $renderedQuery = AdAnalyticsEvent::where('event_name', 'ad_rendered');
        $clickedQuery = AdAnalyticsEvent::where('event_name', 'ad_clicked');
        $failedQuery = AdAnalyticsEvent::where('event_name', 'ad_failed');
        $byPageQuery = AdAnalyticsEvent::where('event_name', 'ad_rendered')
            ->select('page_type', DB::raw('count(*) as impressions'))
            ->groupBy('page_type');

        if ($since !== null) {
            $renderedQuery->where('created_at', '>=', $since);
            $clickedQuery->where('created_at', '>=', $since);
            $failedQuery->where('created_at', '>=', $since);
            $byPageQuery->where('created_at', '>=', $since);
        }

        $totalRendered = $renderedQuery->count();
        $totalClicked = $clickedQuery->count();
        $totalFailed = $failedQuery->count();
        $ctr = $totalRendered > 0 ? round(($totalClicked / $totalRendered) * 100, 2) : 0;
        $byPage = $byPageQuery->get();

        $activePlacementsCount = AdPlacement::where('enabled', true)->count();
        $totalPlacementsCount = AdPlacement::count();

        return view('admin.advertising.index', compact(
            'config',
            'period',
            'periodLabel',
            'totalRendered',
            'totalClicked',
            'totalFailed',
            'ctr',
            'byPage',
            'activePlacementsCount',
            'totalPlacementsCount'
        ));
    }

    /**
     * Update Global & Format Advertising Settings.
     */
    public function updateConfig(Request $request)
    {
        $config = AdvertisingConfig::current();

        $config->status = $request->has('status_active') ? 'ACTIVE' : 'INACTIVE';
        $config->web_enabled = $request->has('web_enabled');
        $config->mobile_app_enabled = $request->has('mobile_app_enabled');
        $config->native_enabled = $request->has('native_enabled');
        $config->banner_enabled = $request->has('banner_enabled');
        $config->social_bar_enabled = $request->has('social_bar_enabled');
        $config->interstitial_enabled = $request->has('interstitial_enabled');
        $config->popunder_enabled = $request->has('popunder_enabled');
        $config->smartlink_enabled = $request->has('smartlink_enabled');
        $config->max_ads_per_session = (int) $request->input('max_ads_per_session', 10);

        $config->save();
        $this->purgePublicCaches();

        return back()->with('success', 'Advertising settings updated successfully.');
    }

    /**
     * Placements Management Screen.
     */
    public function placements()
    {
        $placements = AdPlacement::orderBy('page_type')->orderBy('priority')->get();
        return view('admin.advertising.placements', compact('placements'));
    }

    /**
     * Toggle or Update Ad Placement.
     */
    public function updatePlacement(Request $request, $id)
    {
        $placement = AdPlacement::findOrFail($id);

        if ($request->has('toggle_only')) {
            $placement->enabled = !$placement->enabled;
            $placement->save();
            $this->purgePublicCaches();
            return back()->with('success', "Placement {$placement->placement_key} is now " . ($placement->enabled ? 'ENABLED' : 'DISABLED') . '.');
        }

        $placement->enabled = $request->has('enabled');
        if ($request->has('display_interval')) {
            $placement->display_interval = $request->input('display_interval') ? (int) $request->input('display_interval') : null;
        }
        if ($request->has('frequency_cap')) {
            $placement->frequency_cap = (int) $request->input('frequency_cap', 1);
        }
        if ($request->has('priority')) {
            $placement->priority = (int) $request->input('priority', 0);
        }
        if ($request->has('code')) {
            $placement->code = $request->input('code');
        }

        $placement->save();
        $this->purgePublicCaches();

        return back()->with('success', "Placement {$placement->placement_key} updated successfully.");
    }

    /**
     * Ad Code Snippets Management Screen.
     */
    public function codes()
    {
        $codes = AdCode::all()->keyBy(function ($item) {
            return $item->ad_format . '_' . $item->device;
        });

        return view('admin.advertising.codes', compact('codes'));
    }

    /**
     * Update Ad Code Snippets.
     */
    public function updateCodes(Request $request)
    {
        $codesInput = $request->input('codes', []);

        foreach ($codesInput as $key => $codeText) {
            $parts = explode('_', $key, 2);
            $format = $parts[0] ?? '';
            $device = $parts[1] ?? 'ALL';

            // Check if composite format (e.g. NATIVE_BANNER)
            if (in_array($key, ['NATIVE_BANNER_ALL', 'BANNER_DESKTOP_DESKTOP', 'BANNER_MOBILE_MOBILE', 'APP_CONFIG_ALL', 'SOCIAL_BAR_ALL', 'INTERSTITIAL_ALL', 'POPUNDER_ALL', 'SMARTLINK_ALL'])) {
                $lastUnderscore = strrpos($key, '_');
                $format = substr($key, 0, $lastUnderscore);
                $device = substr($key, $lastUnderscore + 1);
            }

            AdCode::updateOrCreate(
                [
                    'provider' => 'ADSTERRA',
                    'ad_format' => $format,
                    'device' => $device,
                ],
                [
                    'code' => $codeText,
                    'is_active' => true,
                ]
            );
        }

        $this->purgePublicCaches();

        return back()->with('success', 'Adsterra code snippets updated successfully.');
    }

    /**
     * Purge local and remote public caches.
     */
    protected function purgePublicCaches(): void
    {
        Cache::flush();

        try {
            $frontendUrl = env('FREEBYZ_FRONTEND_URL', env('FRONTEND_URL', 'http://127.0.0.1:8001'));
            if ($frontendUrl) {
                \Illuminate\Support\Facades\Http::timeout(1)->get(rtrim($frontendUrl, '/') . '/api/internal/advertising/clear-cache');
            }
        } catch (\Exception $e) {
            // Non-critical fallback
        }
    }
}
