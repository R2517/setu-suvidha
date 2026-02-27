<?php

namespace Tests\Feature;

use Tests\TestCase;

class SitemapTest extends TestCase
{
    public function test_sitemap_is_served_as_xml_and_includes_core_routes(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $response->assertSee('<?xml version="1.0" encoding="UTF-8"?>', false);

        $response->assertSee(route('home'), false);
        $response->assertSee(route('services'), false);
        $response->assertSee(route('reviews.index'), false);
        $response->assertSee(route('farmer-card-public'), false);
    }

    public function test_sitemap_contains_all_service_pages_and_reviews(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);

        foreach (array_keys(config('service_pages.pages', [])) as $slug) {
            $response->assertSee(route('services.landing.show', ['slug' => $slug]), false);
        }

        foreach (array_keys(config('reviews.articles', [])) as $slug) {
            $response->assertSee(route('reviews.show', ['slug' => $slug]), false);
        }
    }
}

