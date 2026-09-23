<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminBadgeController extends Controller
{
    public function index()
    {
        $badges = Badge::orderBy('order')->orderBy('label')->get();

        $providersCounts = [];
        foreach ($badges as $badge) {
            $providersCounts[$badge->label] = Provider::where('badge', $badge->label)->count();
        }

        return view('admin.badges.index', compact('badges', 'providersCounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:50',
            'slug' => 'nullable|string|max:50|unique:badges,slug',
            'color' => 'required|string|in:green,gold,red,dark,sky,rose',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['label']);
        $validated['order'] = $validated['order'] ?? 0;

        Badge::create($validated);

        return redirect()->route('admin.badges.index')->with('success', 'Badge / Distintivo creado exitosamente.');
    }

    public function update(Request $request, Badge $badge)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:50',
            'slug' => 'nullable|string|max:50|unique:badges,slug,'.$badge->id,
            'color' => 'required|string|in:green,gold,red,dark,sky,rose',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['label']);
        $validated['order'] = $validated['order'] ?? 0;

        $badge->update($validated);

        return redirect()->route('admin.badges.index')->with('success', 'Badge / Distintivo actualizado.');
    }

    public function destroy(Badge $badge)
    {
        $badge->delete();

        return redirect()->route('admin.badges.index')->with('success', 'Badge / Distintivo eliminado.');
    }
}
