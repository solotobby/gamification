<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\GeneralMail;
use App\Models\Banner;
use App\Models\PaymentTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $bannerList = Banner::query()
            ->with('user:id,name')
            ->when($request->status, function ($q) use ($request) {
                match ($request->status) {
                    'pending'  => $q->where(fn($sq) => $sq->where('live_state', 'Under Review')->orWhereNull('live_state')),
                    'live'     => $q->where('live_state', 'Started'),
                    'paused'   => $q->where('live_state', 'Paused'),
                    'rejected' => $q->where('live_state', 'Rejected'),
                    'ended'    => $q->whereNotIn('live_state', [null, 'Under Review', 'Started', 'Paused', 'Rejected']),
                    default    => $q,
                };
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(20)
            ->appends($request->query());

        return view('admin.banner.index', compact('bannerList'));
    }

    public function activateBanner($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized.');
        }

        $banner = Banner::findOrFail($id);

        if ($banner->live_state === 'Rejected') {
            return back()->with('error', 'This banner was rejected and refunded — it cannot be reactivated without a new payment.');
        }

        if ($banner->status == true) {
            return back()->with('success', 'Ad Banner is already live.');
        }

        $banner->status = true;
        $banner->live_state = 'Started';
        $banner->banner_end_date = Carbon::now()->addDays($banner->duration);
        $banner->save();

        if ($user = $banner->user) {
            $content = 'Congratulations, your ad is Live on Freebyz.';
            $subject = 'Ad Banner Placement - Live!';
            Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));
        }

        return back()->with('success', 'Ad Banner is Live!');
    }

    public function rejectBanner($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized.');
        }

        $banner = Banner::findOrFail($id);

        if ($banner->live_state === 'Rejected') {
            return back()->with('error', 'This banner has already been rejected and refunded.');
        }

        $banner->live_state = 'Rejected';
        $banner->status = false;
        $banner->save();

        if ($user = $banner->user) {
            creditWallet($user, 'NGN', $banner->amount);

            PaymentTransaction::create([
                'user_id' => $user->id,
                'campaign_id' => '1',
                'reference' => time(),
                'amount' => $banner->amount,
                'balance' => walletBalance($user->id),
                'status' => 'successful',
                'currency' => 'NGN',
                'channel' => 'internal',
                'type' => 'ad_banner_reversal',
                'description' => 'Reversal: Ad Banner Placement by ' . $user->name,
                'tx_type' => 'Credit',
                'user_type' => 'regular',
            ]);

            $subject = 'Ad Banner Placement - Rejected';
            $content = 'Sorry, your banner ad was rejected. This is because it falls into one or more categories against our advertising policies — betting apps, spamming websites, investment apps, or links we consider unsafe for our users.';
            Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));
        }

        return back()->with('success', 'Ad Banner Rejected and refunded.');
    }

    /**
     * Pause / resume a banner that's already been approved and gone live at
     * least once. Mirrors the API's BannerService::toggleBanner() guard: only
     * banners currently Started or Paused can be toggled — Under Review,
     * Ended, and Rejected are all off-limits here (Rejected specifically
     * because it's already been refunded; reactivating it belongs to
     * activateBanner()'s explicit "no reactivation" guard, not this one).
     */
    public function toggleBanner(Request $request, $id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized.');
        }

        $request->validate(['action' => 'required|in:activate,pause']);

        $banner = Banner::findOrFail($id);

        if (in_array($banner->live_state, ['Under Review', 'Ended', 'Rejected']) || $banner->live_state === null) {
            return back()->with('error', 'Banner in "' . ($banner->live_state ?? 'Under Review') . '" state cannot be paused or resumed.');
        }

        if ($request->action === 'activate') {
            $banner->live_state = 'Started';
            $banner->status = true;
            $message = 'Banner resumed and is live again.';
        } else {
            $banner->live_state = 'Paused';
            $banner->status = false;
            $message = 'Banner paused.';
        }

        // Deliberately does NOT touch banner_end_date — pausing/resuming should
        // preserve the remaining flight time, not restart the duration clock
        // the way the initial activateBanner() go-live does.
        $banner->save();

        return back()->with('success', $message);
    }
}
