<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoMetaTest extends TestCase
{
    use RefreshDatabase;

    public function test_key_pages_include_core_seo_tags_and_hreflang(): void
    {
        $paths = [
            '/',
            '/services',
            '/reviews',
            '/services/hamipatra-format-marathi',
            '/reviews/pm-kisan-status-check-2026',
        ];

        foreach ($paths as $path) {
            $response = $this->get($path);

            $response->assertStatus(200);
            $response->assertSee('<link rel="canonical"', false);
            $response->assertSee('property="og:title"', false);
            $response->assertSee('property="og:description"', false);
            $response->assertSee('name="twitter:card"', false);
            $response->assertSee('name="twitter:title"', false);
            $response->assertSee('name="twitter:description"', false);
            $response->assertSee('hreflang="mr-IN"', false);
            $response->assertSee('hreflang="en-IN"', false);
            $response->assertSee('hreflang="x-default"', false);
        }
    }

    public function test_homepage_contains_website_and_faq_schema_blocks(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('"@type":"WebSite"', false);
        $response->assertSee('"@type":"FAQPage"', false);
    }
}

