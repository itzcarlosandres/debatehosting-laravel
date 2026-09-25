<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Provider;
use Illuminate\Http\Request;

class AdminCouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::with('provider')->latest()->get();

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $providers = Provider::orderBy('name')->get();

        return view('admin.coupons.create', compact('providers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'discount' => 'required|string|max:100',
            'condition' => 'nullable|string|max:255',
            'provider_id' => 'required|exists:providers,id',
            'expires_at' => 'nullable|date',
            'verified' => 'boolean',
        ]);

        $validated['verified'] = $request->boolean('verified', true);

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Cupón creado.');
    }

    public function edit(Coupon $coupon)
    {
        $providers = Provider::orderBy('name')->get();

        return view('admin.coupons.edit', compact('coupon', 'providers'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,'.$coupon->id,
            'discount' => 'required|string|max:100',
            'condition' => 'nullable|string|max:255',
            'provider_id' => 'required|exists:providers,id',
            'expires_at' => 'nullable|date',
            'verified' => 'boolean',
        ]);

        $validated['verified'] = $request->boolean('verified', true);

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Cupón actualizado.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Cupón eliminado.');
    }
}
