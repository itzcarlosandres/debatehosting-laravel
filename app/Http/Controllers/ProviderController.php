<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Provider;
use App\Models\Setting;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index(Request $request)
    {
        $query = Provider::where('active', true);

        // Filtro por categoría
        if ($request->filled('categoria') && $request->categoria !== 'todas') {
            $cat = $request->categoria;
            $query->whereJsonContains('categories', $cat);
        }

        // Búsqueda por texto
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('plan', 'like', "%{$search}%");
            });
        }

        // Ordenamiento
        $sort = $request->get('orden', 'recomendados');
        switch ($sort) {
            case 'precio_asc':
                $query->orderBy('price_from', 'asc');
                break;
            case 'precio_desc':
                $query->orderBy('price_from', 'desc');
                break;
            case 'rendimiento':
                $query->orderBy('score_rendimiento', 'desc');
                break;
            case 'soporte':
                $query->orderBy('score_soporte', 'desc');
                break;
            case 'alfabetico':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('score_precio', 'desc');
                break;
        }

        $providers = $query->get();
        $categories = Category::orderBy('order')->get();
        $sectionHeaders = Setting::get('sectionHeaders', []);

        return view('pages.providers.index', compact('providers', 'categories', 'sectionHeaders'));
    }

    public function show(string $slug)
    {
        $provider = Provider::with([
            'products',
            'coupons' => function ($q) {
                $q->where('verified', true);
            },
            'latestReview' => function ($q) {
                $q->published();
            },
        ])->where('slug', $slug)->firstOrFail();

        // Proveedores relacionados para comparar
        $related = Provider::where('active', true)
            ->where('id', '!=', $provider->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('pages.providers.show', compact('provider', 'related'));
    }
}
