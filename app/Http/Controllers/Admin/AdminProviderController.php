<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\Category;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProviderController extends Controller
{
    public function index()
    {
        $providers = Provider::orderBy('name')->get();
        return view('admin.providers.index', compact('providers'));
    }

    public function create()
    {
        $categories = Category::orderBy('order')->get();
        $badges = Badge::orderBy('order')->get();
        return view('admin.providers.create', compact('categories', 'badges'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:providers,slug',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:5120',
            'logo_url' => 'nullable|string',
            'categories' => 'nullable|array',
            'plan' => 'required|string|max:255',
            'price_from' => 'required|numeric|min:0',
            'price_before' => 'required|numeric|min:0',
            'period' => 'required|string|in:mes,año',
            'score_precio' => 'required|numeric|min:0|max:10',
            'score_rendimiento' => 'required|numeric|min:0|max:10',
            'score_soporte' => 'required|numeric|min:0|max:10',
            'score_facilidad' => 'required|numeric|min:0|max:10',
            'uptime' => 'required|numeric|min:90|max:100',
            'affiliate_url' => 'nullable|string|max:500',
            'badge' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|in:green,gold,red,dark',
            'active' => 'boolean',
            'description' => 'nullable|string',
            'pros' => 'nullable|string',
            'cons' => 'nullable|string',
            'verdict' => 'nullable|string',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);
        $validated['active'] = $request->boolean('active');

        // Subida de imagen nativa y segura en storage/logos
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '_' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('logos', $filename, 'public');
            $validated['logo_url'] = '/storage/' . $path;
        }

        Provider::create($validated);

        return redirect()->route('admin.providers.index')->with('success', 'Proveedor creado exitosamente.');
    }

    public function edit(Provider $provider)
    {
        $categories = Category::orderBy('order')->get();
        $badges = Badge::orderBy('order')->get();
        return view('admin.providers.edit', compact('provider', 'categories', 'badges'));
    }

    public function update(Request $request, Provider $provider)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:providers,slug,' . $provider->id,
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:5120',
            'logo_url' => 'nullable|string',
            'categories' => 'nullable|array',
            'plan' => 'required|string|max:255',
            'price_from' => 'required|numeric|min:0',
            'price_before' => 'required|numeric|min:0',
            'period' => 'required|string|in:mes,año',
            'score_precio' => 'required|numeric|min:0|max:10',
            'score_rendimiento' => 'required|numeric|min:0|max:10',
            'score_soporte' => 'required|numeric|min:0|max:10',
            'score_facilidad' => 'required|numeric|min:0|max:10',
            'uptime' => 'required|numeric|min:90|max:100',
            'affiliate_url' => 'nullable|string|max:500',
            'badge' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|in:green,gold,red,dark',
            'active' => 'boolean',
            'description' => 'nullable|string',
            'pros' => 'nullable|string',
            'cons' => 'nullable|string',
            'verdict' => 'nullable|string',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);
        $validated['active'] = $request->boolean('active');

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '_' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('logos', $filename, 'public');
            $validated['logo_url'] = '/storage/' . $path;
        }

        $provider->update($validated);

        return redirect()->route('admin.providers.index')->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy(Provider $provider)
    {
        $provider->delete();
        return redirect()->route('admin.providers.index')->with('success', 'Proveedor eliminado.');
    }
}
