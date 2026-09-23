<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Provider;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Review::with('provider')->latest();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('provider_name', 'like', "%{$search}%")
                  ->orWhereHas('provider', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('estado')) {
            if ($request->estado === 'publicadas') {
                $query->where('published', true);
            } elseif ($request->estado === 'borradores') {
                $query->where('published', false);
            }
        }

        $reviews = $query->paginate(15)->withQueryString();
        $totalCount = Review::count();
        $publishedCount = Review::where('published', true)->count();
        $draftCount = Review::where('published', false)->count();

        return view('admin.reviews.index', compact(
            'reviews',
            'totalCount',
            'publishedCount',
            'draftCount'
        ));
    }

    public function create(): View
    {
        $providers = Provider::orderBy('name')->get();
        $categories = Category::orderBy('order')->get();

        return view('admin.reviews.create', compact('providers', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'provider_id' => 'nullable|exists:providers,id',
            'provider_name' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:reviews,slug',
            'target_category' => 'nullable|string|max:100',
            'rating' => 'required|numeric|min:1|max:10',
            'summary' => 'nullable|string',
            'content' => 'required|string',
            'pros' => 'nullable|string',
            'cons' => 'nullable|string',
            'verdict' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'published' => 'boolean',
            'featured' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        
        // Asegurar unicidad si el slug generado colisiona
        $baseSlug = $validated['slug'];
        $counter = 1;
        while (Review::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $validated['published'] = $request->boolean('published');
        $validated['featured'] = $request->boolean('featured');

        if ($validated['published'] && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        Review::create($validated);

        return redirect()->route('admin.reviews.index')->with('success', 'Reseña editorial creada correctamente.');
    }

    public function edit(Review $review): View
    {
        $providers = Provider::orderBy('name')->get();
        $categories = Category::orderBy('order')->get();

        return view('admin.reviews.edit', compact('review', 'providers', 'categories'));
    }

    public function update(Request $request, Review $review): RedirectResponse
    {
        $validated = $request->validate([
            'provider_id' => 'nullable|exists:providers,id',
            'provider_name' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:reviews,slug,'.$review->id,
            'target_category' => 'nullable|string|max:100',
            'rating' => 'required|numeric|min:1|max:10',
            'summary' => 'nullable|string',
            'content' => 'required|string',
            'pros' => 'nullable|string',
            'cons' => 'nullable|string',
            'verdict' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'published' => 'boolean',
            'featured' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        $validated['published'] = $request->boolean('published');
        $validated['featured'] = $request->boolean('featured');

        if ($validated['published'] && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $review->update($validated);

        return redirect()->route('admin.reviews.index')->with('success', 'Reseña actualizada correctamente.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Reseña eliminada.');
    }

    public function togglePublish(Review $review): RedirectResponse
    {
        $newStatus = !$review->published;
        $review->update([
            'published' => $newStatus,
            'published_at' => $newStatus && !$review->published_at ? now() : $review->published_at,
        ]);

        $statusText = $newStatus ? 'publicada' : 'cambiada a borrador';

        return back()->with('success', "La reseña ha sido {$statusText}.");
    }
}
