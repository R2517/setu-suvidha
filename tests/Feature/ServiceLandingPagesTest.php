<?php

namespace Tests\Feature;

use Tests\TestCase;

class ServiceLandingPagesTest extends TestCase
{
    public function test_all_service_landing_pages_render_with_expected_schema_and_single_h1(): void
    {
        foreach (array_keys(config('service_pages.pages', [])) as $slug) {
            $response = $this->get(route('services.landing.show', ['slug' => $slug]));

            $response->assertStatus(200);
            $response->assertSee('FAQPage', false);
            $response->assertSee('BreadcrumbList', false);
            $response->assertSee(route('register'), false);

            $html = $response->getContent();
            preg_match_all('/<h1\\b[^>]*>/i', $html, $matches);
            $this->assertCount(1, $matches[0], "Expected exactly one H1 on service page: {$slug}");
        }
    }
}
