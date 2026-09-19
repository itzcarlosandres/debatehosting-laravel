<?php

namespace App\Http\Controllers;

use App\Models\ClickEvent;
use App\Models\Coupon;
use App\Models\Provider;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function redirect(Request $request, string $slug)
    {
        $provider = Provider::where('slug', $slug)->firstOrFail();

        // Incrementar clics
        $provider->increment('clicks');

        // Registrar evento
        ClickEvent::create([
            'provider_id' => $provider->id,
            'type' => 'affiliate_link',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $destination = $provider->affiliate_url ?: 'https://' . $provider->slug . '.com';
        return redirect()->away($destination);
    }

    public function trackCoupon(Request $request)
    {
        $request->validate([
            'coupon_id' => 'nullable|integer',
            'provider_id' => 'nullable|integer',
        ]);

        if ($request->filled('coupon_id')) {
            $coupon = Coupon::find($request->coupon_id);
            if ($coupon) {
                $coupon->increment('clicks');
            }
        }

        ClickEvent::create([
            'provider_id' => $request->provider_id,
            'coupon_id' => $request->coupon_id,
            'type' => 'coupon_copy',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json(['success' => true]);
    }
}
