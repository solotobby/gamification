<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConversionRate;
use App\Models\Currency;
use Illuminate\Http\Request;

class ConversionRateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']); // adjust guard as needed
    }

    /**
     * List all conversion rates with the currency selector form.
     */
    public function index()
    {
        $rates      = ConversionRate::orderBy('from')->orderBy('to')->get();
        $currencies = Currency::where('is_active', true)->orderBy('code')->get();

        return view('admin.conversion.index', compact('rates', 'currencies'));
    }

    /**
     * Store a new currency-pair rate.
     */
    public function store(Request $request)
    {
        $request->validate([
            'from'   => 'required|string|exists:currencies,code',
            'to'     => 'required|string|exists:currencies,code|different:from',
            'rate'      => 'required|numeric|min:0.000001',
            'status' => 'nullable|boolean',
        ]);

        // Prevent duplicate pairs
        $exists = ConversionRate::where('from', $request->from)
                                ->where('to',   $request->to)
                                ->exists();

        if ($exists) {
            return back()->with('error', "A rate for {$request->from} → {$request->to} already exists. Update it instead.");
        }

        ConversionRate::create([
            'from'      => strtoupper($request->from),
            'to'        => strtoupper($request->to),
            'rate'      => $request->rate,
            'amount'    => $request->rate,
            'status' => $request->boolean('status', true),
        ]);

        return back()->with('success', "Rate {$request->from} → {$request->to} created successfully.");
    }

    /**
     * Update an existing conversion rate.
     */
    public function update(Request $request, ConversionRate $conversionRate)
    {
        $request->validate([
            'rate'      => 'required|numeric|min:0.000001',
            'status' => 'nullable|boolean',
        ]);

        $conversionRate->update([
            'rate'      => $request->rate,
            'amount'    => $request->rate,
            'status' => $request->boolean('status', $conversionRate->status),
        ]);

        return back()->with('success', "Rate {$conversionRate->from} → {$conversionRate->to} updated successfully.");
    }

    /**
     * Delete a conversion rate pair.
     */
    public function destroy(ConversionRate $conversionRate)
    {
        $label = "{$conversionRate->from} → {$conversionRate->to}";
        $conversionRate->delete();

        return back()->with('success', "Rate {$label} deleted.");
    }

    /**
     * Bulk-generate all possible pairs from active currencies.
     * Useful for initialising a fresh set of rates.
     */
    public function generateAll(Request $request)
    {
        $request->validate([
            'default_rate' => 'required|numeric|min:0.000001',
        ]);

        $codes   = Currency::where('is_active', true)->pluck('code');
        $created = 0;

        foreach ($codes as $from) {
            foreach ($codes as $to) {
                if ($from === $to) {
                    continue;
                }

                $exists = ConversionRate::where('from', $from)->where('to', $to)->exists();
                if (!$exists) {
                    ConversionRate::create([
                        'from'      => $from,
                        'to'        => $to,
                        'rate'      => $request->default_rate,
                        'amount'    => $request->default_rate,
                        'status' => true,
                    ]);
                    $created++;
                }
            }
        }

        return back()->with('success', "{$created} new currency pair(s) generated. Update individual rates as needed.");
    }
}
