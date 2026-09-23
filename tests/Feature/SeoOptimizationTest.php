<?php

namespace Tests\Feature;

use App\Models\Provider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoOptimizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_xml_renders_valid_xml_with_public_urls(): void
    {
        $provider = Provider::create([
            'name' => 'Hostinger',
            'slug' => 'hostinger',
            'plan' => 'Premium Web',
            'price_from' => 2.99,
            'price_before' => 5.99,
            'period' => 'mes',
            'score_precio' => 9.2,
            'score_rendimiento' => 8.9,
            'score_soporte' => 8.5,
            'score_facilidad' => 9.0,
            'uptime' => 99.9,
            'active' => true,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $response->assertSee(route('home'));
        $response->assertSee(route('providers.show', $provider->slug));
        $response->assertSee('<changefreq>daily</changefreq>', false);
        $response->assertSee('<priority>1.0</priority>', false);
    }

    public function test_robots_txt_contains_proper_seo_rules(): void
    {
        $robotsPath = public_path('robots.txt');
        $this->assertFileExists($robotsPath);

        $content = file_get_contents($robotsPath);
        $this->assertStringContainsString('User-agent: *', $content);
        $this->assertStringContainsString('Disallow: /admin/', $content);
        $this->assertStringContainsString('Disallow: /go/', $content);
        $this->assertStringContainsString('Disallow: /api/', $content);
        $this->assertStringContainsString('Sitemap:', $content);
    }

    public function test_affiliate_redirect_sends_noindex_header(): void
    {
        $provider = Provider::create([
            'name' => 'Alexhost',
            'slug' => 'alexhost',
            'plan' => 'VPS KVM',
            'price_from' => 4.00,
            'price_before' => 6.00,
            'period' => 'mes',
            'score_precio' => 8.5,
            'score_rendimiento' => 8.0,
            'score_soporte' => 7.5,
            'score_facilidad' => 7.5,
            'uptime' => 99.9,
            'affiliate_url' => 'https://alexhost.com/?partner=debatehosting',
            'active' => true,
        ]);

        $response = $this->get('/go/'.$provider->slug);

        $response->assertStatus(302);
        $response->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    public function test_homepage_renders_complete_seo_tags_and_schema(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('<meta name="robots"', false);
        $response->assertSee('property="og:title"', false);
        $response->assertSee('property="og:description"', false);
        $response->assertSee('property="og:url"', false);
        $response->assertSee('name="twitter:card"', false);
        $response->assertSee('"@type": "WebSite"', false);
        $response->assertSee('"@type": "Organization"', false);
    }

    public function test_provider_show_renders_rich_snippets_schema(): void
    {
        $provider = Provider::create([
            'name' => 'SiteGround',
            'slug' => 'siteground',
            'plan' => 'StartUp',
            'price_from' => 2.99,
            'price_before' => 17.99,
            'period' => 'mes',
            'score_precio' => 7.5,
            'score_rendimiento' => 9.8,
            'score_soporte' => 9.5,
            'score_facilidad' => 9.0,
            'uptime' => 99.99,
            'description' => 'Alojamiento web de alta gama en Google Cloud.',
            'active' => true,
        ]);

        $response = $this->get('/proveedores/'.$provider->slug);

        $response->assertStatus(200);
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('"@type": "Product"', false);
        $response->assertSee('"@type": "AggregateRating"', false);
        $response->assertSee('"@type": "BreadcrumbList"', false);
        $response->assertSee('"ratingValue": "'.$provider->overall_score.'"', false);
    }
}
