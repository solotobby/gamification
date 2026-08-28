<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CareerProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $profiles = CareerProfile::query()
            ->select(['id', 'user_id', 'slug', 'professional_title', 'profile_completeness', 'cv_file_path', 'is_public', 'created_at'])
            ->with('user:id,name')
            ->when($request->status === 'public', fn($q) => $q->where('is_public', true))
            ->when($request->status === 'private', fn($q) => $q->where('is_public', false))
            ->when($request->search, function ($q) use ($request) {
                $q->where(fn($sq) => $sq
                    ->where('professional_title', 'like', "%{$request->search}%")
                    ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$request->search}%")));
            })
            ->latest()
            ->paginate(20)
            ->appends($request->query());

        $stats = Cache::remember('admin.career_profiles.stats', now()->addMinutes(10), function () {
            $row = CareerProfile::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN is_public = 1 THEN 1 ELSE 0 END) as public_count,
            SUM(CASE WHEN is_public = 0 THEN 1 ELSE 0 END) as private_count,
            AVG(profile_completeness) as avg_completeness,
            SUM(CASE WHEN cv_file_path IS NOT NULL THEN 1 ELSE 0 END) as with_cv,
            SUM(views_count) as total_profile_views
        ')->first();

            return [
                'total' => (int) $row->total,
                'public_count' => (int) $row->public_count,
                'private_count' => (int) $row->private_count,
                'avg_completeness' => round($row->avg_completeness ?? 0),
                'with_cv' => (int) $row->with_cv,
                'total_profile_views' => (int) $row->total_profile_views,
            ];
        });

        return view('admin.career-profiles.index', compact('profiles', 'stats'));
    }

    public function show($id)
    {
        $profile = CareerProfile::with([
            'user:id,name,email',
            'skills:id,name',
            'experiences',
            'educations.university',
            'certifications',
            'socialProfiles',
            'availabilities',
        ])->findOrFail($id);

        // total_views now reads the counter column directly — zero extra
        // query, and stays fast no matter how large profile_views grows.
        // The remaining three queries are all scoped by the indexed
        // career_profile_id column, so they stay bounded to this one
        // profile's history regardless of how big the table gets overall.
        $viewsQuery = DB::table('profile_views')->where('career_profile_id', $profile->id);

        $analytics = [
            'total_views' => $profile->views_count,
            'cv_downloads' => (clone $viewsQuery)->where('action', 'cv_download')->count(),
            'unique_viewers' => (clone $viewsQuery)->where('action', 'view')->distinct('viewer_user_id')->count('viewer_user_id'),
            'views_last_7_days' => (clone $viewsQuery)->where('action', 'view')->where('created_at', '>=', now()->subDays(7))->count(),
            'views_last_30_days' => (clone $viewsQuery)->where('action', 'view')->where('created_at', '>=', now()->subDays(30))->count(),
            'views_by_day' => (clone $viewsQuery)
                ->where('action', 'view')
                ->where('created_at', '>=', now()->subDays(30))
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
        ];

        return view('admin.career-profiles.show', compact('profile', 'analytics'));
    }

    public function update(Request $request, $id)
    {
        $profile = CareerProfile::findOrFail($id);

        $validated = $request->validate([
            'professional_title' => 'nullable|string|max:150',
            'headline' => 'nullable|string|max:150',
            'summary' => 'nullable|string|max:3000',
            'professional_level' => 'nullable|in:student_talent,junior_professional,mid_level_professional,senior_professional,expert',
            'city' => 'nullable|string|max:120',
            'state' => 'nullable|string|max:120',
            'country' => 'nullable|string|max:120',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0|gte:price_min',
        ]);

        $profile->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function togglePublish($id)
    {
        $profile = CareerProfile::findOrFail($id);
        $profile->is_public = !$profile->is_public;
        $profile->save();

        Cache::forget('admin.career_profiles.stats');  // public/private counts just changed

        return back()->with('success', $profile->is_public ? 'Profile published.' : 'Profile unpublished.');
    }
}
