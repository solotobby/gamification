<?php

namespace Database\Seeders;

use App\Models\AdCode;
use App\Models\AdPlacement;
use App\Models\AdvertisingConfig;
use Illuminate\Database\Seeder;

class AdvertisingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Base Advertising Configuration
        AdvertisingConfig::firstOrCreate(
            ['name' => 'default'],
            [
                'status' => 'ACTIVE',
                'web_enabled' => true,
                'mobile_app_enabled' => true,
                'native_enabled' => true,
                'banner_enabled' => true,
                'social_bar_enabled' => false,
                'interstitial_enabled' => false,
                'popunder_enabled' => false,
                'smartlink_enabled' => false,
                'max_ads_per_session' => 10,
            ]
        );

        // 2. Standard Ad Placements
        $placements = [
            // Jobs listing
            [
                'placement_key' => 'JOBS_LIST_TOP_BANNER',
                'page_type' => 'JOBS_LIST',
                'ad_format' => 'STANDARD_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'TOP',
                'display_interval' => null,
                'frequency_cap' => 1,
                'enabled' => true,
                'priority' => 1,
            ],
            [
                'placement_key' => 'JOBS_LIST_MID_NATIVE',
                'page_type' => 'JOBS_LIST',
                'ad_format' => 'NATIVE_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'IN_FEED',
                'display_interval' => 6,
                'frequency_cap' => 4,
                'enabled' => true,
                'priority' => 2,
            ],
            [
                'placement_key' => 'JOBS_LIST_BOTTOM_BANNER',
                'page_type' => 'JOBS_LIST',
                'ad_format' => 'STANDARD_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'BOTTOM',
                'display_interval' => null,
                'frequency_cap' => 1,
                'enabled' => true,
                'priority' => 3,
            ],

            // Individual Job Details
            [
                'placement_key' => 'JOB_DETAIL_NATIVE_01',
                'page_type' => 'JOB_DETAIL',
                'ad_format' => 'NATIVE_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'AFTER_DESCRIPTION',
                'display_interval' => null,
                'frequency_cap' => 1,
                'enabled' => true,
                'priority' => 1,
            ],
            [
                'placement_key' => 'JOB_DETAIL_SIDEBAR_01',
                'page_type' => 'JOB_DETAIL',
                'ad_format' => 'NATIVE_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'SIDEBAR_PROMO_CARDS',
                'display_interval' => null,
                'frequency_cap' => 1,
                'enabled' => true,
                'priority' => 2,
            ],
            [
                'placement_key' => 'JOB_DETAIL_BANNER_01',
                'page_type' => 'JOB_DETAIL',
                'ad_format' => 'STANDARD_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'SIDEBAR',
                'display_interval' => null,
                'frequency_cap' => 1,
                'enabled' => false,
                'priority' => 3,
            ],
            [
                'placement_key' => 'JOB_DETAIL_BOTTOM_BANNER',
                'page_type' => 'JOB_DETAIL',
                'ad_format' => 'STANDARD_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'BOTTOM',
                'display_interval' => null,
                'frequency_cap' => 1,
                'enabled' => true,
                'priority' => 4,
            ],

            // Microtasks Listing
            [
                'placement_key' => 'TASKS_LIST_MID_NATIVE',
                'page_type' => 'TASKS_LIST',
                'ad_format' => 'NATIVE_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'IN_FEED',
                'display_interval' => 5,
                'frequency_cap' => 4,
                'enabled' => true,
                'priority' => 1,
            ],
            [
                'placement_key' => 'TASKS_LIST_BOTTOM_BANNER',
                'page_type' => 'TASKS_LIST',
                'ad_format' => 'STANDARD_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'BOTTOM',
                'display_interval' => null,
                'frequency_cap' => 1,
                'enabled' => true,
                'priority' => 2,
            ],

            // Microtask Details
            [
                'placement_key' => 'TASK_DETAIL_SIDEBAR_01',
                'page_type' => 'TASK_DETAIL',
                'ad_format' => 'NATIVE_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'SIDEBAR_PROMO_CARDS',
                'display_interval' => null,
                'frequency_cap' => 1,
                'enabled' => true,
                'priority' => 1,
            ],
            [
                'placement_key' => 'TASK_DETAIL_BOTTOM_BANNER',
                'page_type' => 'TASK_DETAIL',
                'ad_format' => 'STANDARD_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'BOTTOM',
                'display_interval' => null,
                'frequency_cap' => 1,
                'enabled' => true,
                'priority' => 2,
            ],

            // Talent Marketplace
            [
                'placement_key' => 'TALENT_MARKETPLACE_TOP_BANNER',
                'page_type' => 'TALENT_MARKETPLACE',
                'ad_format' => 'STANDARD_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'TOP',
                'display_interval' => null,
                'frequency_cap' => 1,
                'enabled' => true,
                'priority' => 1,
            ],
            [
                'placement_key' => 'TALENT_MARKETPLACE_NATIVE_01',
                'page_type' => 'TALENT_MARKETPLACE',
                'ad_format' => 'NATIVE_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'MID_PAGE',
                'display_interval' => null,
                'frequency_cap' => 1,
                'enabled' => true,
                'priority' => 2,
            ],
            [
                'placement_key' => 'TALENT_MARKETPLACE_MID_NATIVE',
                'page_type' => 'TALENT_MARKETPLACE',
                'ad_format' => 'NATIVE_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'IN_FEED',
                'display_interval' => 6,
                'frequency_cap' => 4,
                'enabled' => true,
                'priority' => 3,
            ],
            [
                'placement_key' => 'TALENT_MARKETPLACE_BOTTOM_BANNER',
                'page_type' => 'TALENT_MARKETPLACE',
                'ad_format' => 'STANDARD_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'BOTTOM',
                'display_interval' => null,
                'frequency_cap' => 1,
                'enabled' => true,
                'priority' => 4,
            ],

            // Blog Articles Listing
            [
                'placement_key' => 'BLOG_LIST_TOP_BANNER',
                'page_type' => 'BLOG_LIST',
                'ad_format' => 'STANDARD_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'AFTER_SEARCH',
                'display_interval' => null,
                'frequency_cap' => 1,
                'enabled' => true,
                'priority' => 1,
            ],
            [
                'placement_key' => 'BLOG_LIST_MID_NATIVE',
                'page_type' => 'BLOG_LIST',
                'ad_format' => 'NATIVE_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'IN_FEED',
                'display_interval' => 6,
                'frequency_cap' => 4,
                'enabled' => true,
                'priority' => 2,
            ],
            [
                'placement_key' => 'BLOG_LIST_BOTTOM_BANNER',
                'page_type' => 'BLOG_LIST',
                'ad_format' => 'STANDARD_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'BOTTOM',
                'display_interval' => null,
                'frequency_cap' => 1,
                'enabled' => true,
                'priority' => 3,
            ],

            // Blog Article Single Reading
            [
                'placement_key' => 'BLOG_ARTICLE_NATIVE_01',
                'page_type' => 'BLOG_ARTICLE',
                'ad_format' => 'NATIVE_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'AFTER_INTRO',
                'display_interval' => null,
                'frequency_cap' => 1,
                'enabled' => true,
                'priority' => 1,
            ],
            [
                'placement_key' => 'BLOG_ARTICLE_BANNER_01',
                'page_type' => 'BLOG_ARTICLE',
                'ad_format' => 'STANDARD_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'BETWEEN_CONTENT',
                'display_interval' => null,
                'frequency_cap' => 1,
                'enabled' => true,
                'priority' => 2,
            ],
            [
                'placement_key' => 'BLOG_ARTICLE_NATIVE_02',
                'page_type' => 'BLOG_ARTICLE',
                'ad_format' => 'NATIVE_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'END_CONTENT',
                'display_interval' => null,
                'frequency_cap' => 1,
                'enabled' => true,
                'priority' => 3,
            ],
            [
                'placement_key' => 'BLOG_ARTICLE_BOTTOM_BANNER',
                'page_type' => 'BLOG_ARTICLE',
                'ad_format' => 'STANDARD_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'BOTTOM',
                'display_interval' => null,
                'frequency_cap' => 1,
                'enabled' => true,
                'priority' => 4,
            ],

            // Home Page
            [
                'placement_key' => 'HOME_BOTTOM_BANNER',
                'page_type' => 'HOME',
                'ad_format' => 'STANDARD_BANNER',
                'device' => 'ALL',
                'platform' => 'WEB',
                'position' => 'BOTTOM',
                'display_interval' => null,
                'frequency_cap' => 1,
                'enabled' => true,
                'priority' => 1,
            ],
        ];

        foreach ($placements as $placement) {
            AdPlacement::updateOrCreate(
                ['placement_key' => $placement['placement_key']],
                $placement
            );
        }

        // 3. Ad Code Slots
        $codes = [
            [
                'provider' => 'ADSTERRA',
                'ad_format' => 'NATIVE_BANNER',
                'device' => 'ALL',
                'code' => env('ADSTERRA_NATIVE_WEB_CODE', ''),
                'is_active' => true,
            ],
            [
                'provider' => 'ADSTERRA',
                'ad_format' => 'BANNER_DESKTOP',
                'device' => 'DESKTOP',
                'code' => env('ADSTERRA_BANNER_DESKTOP_CODE', ''),
                'is_active' => true,
            ],
            [
                'provider' => 'ADSTERRA',
                'ad_format' => 'BANNER_MOBILE',
                'device' => 'MOBILE',
                'code' => env('ADSTERRA_BANNER_MOBILE_CODE', ''),
                'is_active' => true,
            ],
            [
                'provider' => 'ADSTERRA',
                'ad_format' => 'APP_CONFIG',
                'device' => 'ALL',
                'code' => env('ADSTERRA_APP_CONFIGURATION', ''),
                'is_active' => true,
            ],
            [
                'provider' => 'ADSTERRA',
                'ad_format' => 'SOCIAL_BAR',
                'device' => 'ALL',
                'code' => '',
                'is_active' => false,
            ],
            [
                'provider' => 'ADSTERRA',
                'ad_format' => 'INTERSTITIAL',
                'device' => 'ALL',
                'code' => '',
                'is_active' => false,
            ],
            [
                'provider' => 'ADSTERRA',
                'ad_format' => 'POPUNDER',
                'device' => 'ALL',
                'code' => '',
                'is_active' => false,
            ],
            [
                'provider' => 'ADSTERRA',
                'ad_format' => 'SMARTLINK',
                'device' => 'ALL',
                'code' => '',
                'is_active' => false,
            ],
        ];

        foreach ($codes as $code) {
            AdCode::updateOrCreate(
                [
                    'provider' => $code['provider'],
                    'ad_format' => $code['ad_format'],
                    'device' => $code['device'],
                ],
                $code
            );
        }
    }
}
