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
        $providers = Provider::latest()->get();

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
            'badge_color' => 'nullable|string|max:50',
            'active' => 'boolean',
            'description' => 'nullable|string',
            'pros' => 'nullable|string',
            'cons' => 'nullable|string',
            'verdict' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);
        $validated['active'] = $request->boolean('active');
        $validated['categories'] = array_values(array_filter($request->input('categories', [])));

        // Subida de imagen nativa y segura en storage/logos
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo_'.time().'_'.Str::slug($validated['name']).'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('logos', $filename, 'public');
            $validated['logo_url'] = '/storage/'.$path;
        }

        $provider = Provider::create($validated);
        $this->syncProducts($provider, $request->input('products', []));

        return redirect()->route('admin.providers.index')->with('success', 'Proveedor creado exitosamente con su catálogo de productos.');
    }

    public function edit(Provider $provider)
    {
        $provider->load('products');
        $categories = Category::orderBy('order')->get();
        $badges = Badge::orderBy('order')->get();

        return view('admin.providers.edit', compact('provider', 'categories', 'badges'));
    }

    public function update(Request $request, Provider $provider)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:providers,slug,'.$provider->id,
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
            'badge_color' => 'nullable|string|max:50',
            'active' => 'boolean',
            'description' => 'nullable|string',
            'pros' => 'nullable|string',
            'cons' => 'nullable|string',
            'verdict' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);
        $validated['active'] = $request->boolean('active');
        $validated['categories'] = array_values(array_filter($request->input('categories', [])));

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo_'.time().'_'.Str::slug($validated['name']).'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('logos', $filename, 'public');
            $validated['logo_url'] = '/storage/'.$path;
        }

        $provider->update($validated);
        $this->syncProducts($provider, $request->input('products', []));

        return redirect()->route('admin.providers.index')->with('success', 'Proveedor y catálogo de productos actualizados exitosamente.');
    }

    /**
     * Sincronizar los productos/planes asociados al proveedor
     */
    protected function syncProducts(Provider $provider, array $productsInput): void
    {
        $provider->products()->delete();
        $order = 0;
        $hasProducts = false;

        foreach ($productsInput as $item) {
            $planName = trim($item['plan_name'] ?? '');
            if (empty($planName)) {
                continue;
            }

            $rawSpecs = $item['specs'] ?? [];
            $specs = ! empty($rawSpecs)
                ? (is_array($rawSpecs) ? $rawSpecs : array_map('trim', explode(',', (string) $rawSpecs)))
                : [];

            $provider->products()->create([
                'category_slug' => ! empty($item['category_slug']) ? trim($item['category_slug']) : null,
                'plan_name' => $planName,
                'price_from' => floatval($item['price_from'] ?? 0),
                'price_before' => floatval($item['price_before'] ?? 0),
                'period' => in_array($item['period'] ?? '', ['mes', 'año']) ? $item['period'] : 'mes',
                'specs' => array_values(array_filter($specs)),
                'affiliate_url' => ! empty($item['affiliate_url']) ? trim($item['affiliate_url']) : null,
                'is_featured' => ! empty($item['is_featured']),
                'order' => $order++,
            ]);
            $hasProducts = true;
        }

        // Si no se recibieron productos explícitos pero hay plan auditado, asegurar el plan base
        if (! $hasProducts && (! empty($provider->plan) || $provider->price_from > 0)) {
            $categories = $provider->categories ?? [];
            $firstCategory = is_array($categories) && count($categories) > 0 ? $categories[0] : null;

            $provider->products()->create([
                'category_slug' => $firstCategory,
                'plan_name' => $provider->plan ?: 'Plan Estándar',
                'price_from' => $provider->price_from ?: 0,
                'price_before' => $provider->price_before ?: 0,
                'period' => $provider->period ?: 'mes',
                'affiliate_url' => $provider->affiliate_url,
                'is_featured' => true,
                'order' => 0,
            ]);
        }
    }

    public function destroy(Provider $provider)
    {
        $provider->delete();

        return redirect()->route('admin.providers.index')->with('success', 'Proveedor eliminado.');
    }
}
