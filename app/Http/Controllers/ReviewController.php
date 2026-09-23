<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Review::with('provider')->published();

        // Filtro por categoría
        if ($request->filled('categoria') && $request->categoria !== 'todas') {
            $cat = $request->categoria;
            $query->where(function ($q) use ($cat) {
                $q->where('target_category', $cat)
                  ->orWhereHas('provider', function ($pq) use ($cat) {
                      $pq->whereJsonContains('categories', $cat);
                  });
            });
        }

        // Búsqueda por texto
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('provider_name', 'like', "%{$search}%")
                  ->orWhereHas('provider', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Ordenamiento
        $sort = $request->get('orden', 'recientes');
        if ($sort === 'puntuacion') {
            $query->orderByDesc('rating');
        } else {
            $query->orderByDesc('published_at');
        }

        $reviews = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('order')->get();
        $featuredReviews = Review::with('provider')->published()->featured()->take(3)->get();

        return view('pages.reviews.index', compact('reviews', 'categories', 'featuredReviews'));
    }

    public function show(string $slug): View
    {
        $query = Review::with([
            'provider' => function ($q) {
                $q->with(['coupons' => function ($cq) {
                    $cq->where('verified', true);
                }]);
            },
        ])->where('slug', $slug);

        // Si no está autenticado como administrador, solo puede ver publicadas
        if (!auth()->check()) {
            $query->published();
        }

        $review = $query->firstOrFail();

        // Determinar tipo de widget
        $hasActiveProvider = $review->hasActiveProvider();
        $alternatives = $hasActiveProvider ? collect() : $review->getRecommendedAlternatives(3);

        // Otras reseñas relacionadas para leer
        $relatedReviews = Review::published()
            ->where('id', '!=', $review->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('pages.reviews.show', compact(
            'review',
            'hasActiveProvider',
            'alternatives',
            'relatedReviews'
        ));
    }
}
