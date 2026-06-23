<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']); // adjust guard as needed
    }

    /**
     * List all currencies.
     */
    public function index()
    {
        $currencies = Currency::orderBy('id')->get();
        return view('admin.currency.index', compact('currencies'));
    }

    /**
     * Store a new currency.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code'                       => 'required|string|max:10|unique:currencies,code',
            'country'                    => 'required|string|max:100',
            'base_rate'                  => 'required|numeric|min:0',
            'upgrade_fee'                => 'nullable|numeric|min:0',
            'min_upgrade_amount'         => 'nullable|numeric|min:0',
            'referral_commission'        => 'nullable|numeric|min:0',
            'allow_upload'               => 'nullable|numeric|min:0',
            'priotize'                   => 'nullable|numeric|min:0',
            'min_withdrawal_amount'      => 'nullable|numeric|min:0',
            'withdrawal_percent'         => 'nullable|numeric|min:0|max:100',
            'freebyz_withdrawal_percent' => 'nullable|numeric|min:0|max:100',
            'referral_withdrawal_percent'=> 'nullable|numeric|min:0|max:100',
            'banner_clicks_amount'       => 'nullable|numeric|min:0',
            'hire_worker_points_amount'  => 'nullable|numeric|min:0',
            'job_points_amount'          => 'nullable|numeric|min:0',
            'is_active'                  => 'boolean',
        ]);

        Currency::create(array_merge(
            $request->only([
                'code', 'country', 'base_rate', 'upgrade_fee', 'min_upgrade_amount',
                'referral_commission', 'allow_upload', 'priotize', 'min_withdrawal_amount',
                'withdrawal_percent', 'freebyz_withdrawal_percent', 'referral_withdrawal_percent',
                'banner_clicks_amount', 'hire_worker_points_amount', 'job_points_amount',
            ]),
            ['is_active' => $request->boolean('is_active', true)]
        ));

        return redirect()->route('admin.currencies.index')
                         ->with('success', "Currency '{$request->code}' created successfully.");
    }

    /**
     * Update an existing currency.
     */
    public function update(Request $request, Currency $currency)
    {
        $request->validate([
            'code'                       => 'required|string|max:10|unique:currencies,code,' . $currency->id,
            'country'                    => 'required|string|max:100',
            'base_rate'                  => 'required|numeric|min:0',
            'upgrade_fee'                => 'nullable|numeric|min:0',
            'min_upgrade_amount'         => 'nullable|numeric|min:0',
            'referral_commission'        => 'nullable|numeric|min:0',
            'allow_upload'               => 'nullable|numeric|min:0',
            'priotize'                   => 'nullable|numeric|min:0',
            'min_withdrawal_amount'      => 'nullable|numeric|min:0',
            'withdrawal_percent'         => 'nullable|numeric|min:0|max:100',
            'freebyz_withdrawal_percent' => 'nullable|numeric|min:0|max:100',
            'referral_withdrawal_percent'=> 'nullable|numeric|min:0|max:100',
            'banner_clicks_amount'       => 'nullable|numeric|min:0',
            'hire_worker_points_amount'  => 'nullable|numeric|min:0',
            'job_points_amount'          => 'nullable|numeric|min:0',
            'is_active'                  => 'boolean',
        ]);

        $currency->update(array_merge(
            $request->only([
                'code', 'country', 'base_rate', 'upgrade_fee', 'min_upgrade_amount',
                'referral_commission', 'allow_upload', 'priotize', 'min_withdrawal_amount',
                'withdrawal_percent', 'freebyz_withdrawal_percent', 'referral_withdrawal_percent',
                'banner_clicks_amount', 'hire_worker_points_amount', 'job_points_amount',
            ]),
            ['is_active' => $request->boolean('is_active')]
        ));

        return redirect()->route('admin.currencies.index')
                         ->with('success', "Currency '{$currency->code}' updated successfully.");
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus(Currency $currency)
    {
        $currency->update(['is_active' => !$currency->is_active]);

        $status = $currency->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Currency '{$currency->code}' {$status}.");
    }

    /**
     * Delete a currency (only if no conversion rates reference it).
     */
    public function destroy(Currency $currency)
    {
        // Guard: prevent deletion if conversion rates exist for this currency
        $hasRates = \App\Models\ConversionRate::where('from', $currency->code)
                        ->orWhere('to', $currency->code)
                        ->exists();

        if ($hasRates) {
            return back()->with('error', "Cannot delete '{$currency->code}': it has linked conversion rates. Remove those first.");
        }

        $code = $currency->code;
        $currency->delete();

        return redirect()->route('admin.currencies.index')
                         ->with('success', "Currency '{$code}' deleted.");
    }
}
