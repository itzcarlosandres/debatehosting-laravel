<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Provider;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generar el XML Sitemap dinámico de DebateHosting
     */
    public function index(): Response
    {
        $providers = Provider::where('active', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        $categories = Category::orderBy('order')->get();

        $reviews = \App\Models\Review::where('published', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        $xml = view('pages.sitemap', compact('providers', 'categories', 'reviews'))->render();

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }
}
