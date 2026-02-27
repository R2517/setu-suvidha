<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    public function index()
    {
        $items = [];
        $today = now()->toDateString();

        $staticRoutes = [
            ['name' => 'home', 'changefreq' => 'weekly', 'priority' => '1.0', 'lastmod' => $today],
            ['name' => 'about', 'changefreq' => 'monthly', 'priority' => '0.8', 'lastmod' => $today],
            ['name' => 'services', 'changefreq' => 'weekly', 'priority' => '0.9', 'lastmod' => $today],
            ['name' => 'contact', 'changefreq' => 'monthly', 'priority' => '0.7', 'lastmod' => $today],
            ['name' => 'how-it-works', 'changefreq' => 'monthly', 'priority' => '0.7', 'lastmod' => $today],
            ['name' => 'benefits', 'changefreq' => 'monthly', 'priority' => '0.7', 'lastmod' => $today],
            ['name' => 'faq', 'changefreq' => 'monthly', 'priority' => '0.7', 'lastmod' => $today],
            ['name' => 'author', 'changefreq' => 'monthly', 'priority' => '0.7', 'lastmod' => $today],
            ['name' => 'bandkam-info', 'changefreq' => 'monthly', 'priority' => '0.7', 'lastmod' => $today],
            ['name' => 'reviews.index', 'changefreq' => 'weekly', 'priority' => '0.9', 'lastmod' => $today],
            ['name' => 'farmer-card-public', 'changefreq' => 'weekly', 'priority' => '0.9', 'lastmod' => $today],
            ['name' => 'terms', 'changefreq' => 'yearly', 'priority' => '0.4', 'lastmod' => $today],
            ['name' => 'privacy', 'changefreq' => 'yearly', 'priority' => '0.4', 'lastmod' => $today],
            ['name' => 'refund', 'changefreq' => 'yearly', 'priority' => '0.4', 'lastmod' => $today],
            ['name' => 'disclaimer', 'changefreq' => 'yearly', 'priority' => '0.4', 'lastmod' => $today],
            ['name' => 'vle.directory', 'changefreq' => 'weekly', 'priority' => '0.7', 'lastmod' => $today],
        ];

        foreach ($staticRoutes as $routeMeta) {
            if (!Route::has($routeMeta['name'])) {
                continue;
            }

            $items[] = [
                'loc' => route($routeMeta['name']),
                'lastmod' => Carbon::parse($routeMeta['lastmod'])->toDateString(),
                'changefreq' => $routeMeta['changefreq'],
                'priority' => $routeMeta['priority'],
            ];
        }

        foreach (config('service_pages.pages', []) as $slug => $page) {
            $routeName = $page['route_name'] ?? 'services.landing.show';
            if (!Route::has($routeName)) {
                continue;
            }

            $items[] = [
                'loc' => route($routeName, ['slug' => $slug]),
                'lastmod' => Carbon::parse($page['updated_at'] ?? now())->toDateString(),
                'changefreq' => 'weekly',
                'priority' => '0.85',
            ];
        }

        foreach (config('reviews.articles', []) as $slug => $article) {
            if (!Route::has('reviews.show')) {
                continue;
            }

            $items[] = [
                'loc' => route('reviews.show', ['slug' => $slug]),
                'lastmod' => Carbon::parse($article['date_modified'] ?? now())->toDateString(),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ];
        }

        $items = collect($items)
            ->unique('loc')
            ->values()
            ->all();

        return response()
            ->view('sitemap.xml', ['items' => $items])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
