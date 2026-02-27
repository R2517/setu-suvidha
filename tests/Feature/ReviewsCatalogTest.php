<?php

namespace Tests\Feature;

use Tests\TestCase;

class ReviewsCatalogTest extends TestCase
{
    public function test_reviews_index_renders_and_links_to_review_articles(): void
    {
        $response = $this->get(route('reviews.index'));

        $response->assertStatus(200);

        foreach (config('reviews.articles', []) as $slug => $article) {
            $response->assertSee(route('reviews.show', ['slug' => $slug]), false);
            $response->assertSee((string) ($article['title_en'] ?? $article['title'] ?? $slug), false);
        }
    }

    public function test_each_review_article_contains_expected_article_schema_and_single_h1(): void
    {
        foreach (array_keys(config('reviews.articles', [])) as $slug) {
            $response = $this->get(route('reviews.show', ['slug' => $slug]));

            $response->assertStatus(200);
            $response->assertSee('"@type":"Article"', false);
            $response->assertSee('"datePublished"', false);
            $response->assertSee('"dateModified"', false);
            $response->assertSee('"mainEntityOfPage"', false);

            $html = $response->getContent();
            preg_match_all('/<h1\\b[^>]*>/i', $html, $matches);
            $this->assertCount(1, $matches[0], "Expected exactly one H1 on review page: {$slug}");
        }
    }
}

