<?php

namespace App\Http\Controllers;

class PublicServicePageController extends Controller
{
    public function show(string $slug)
    {
        $pages = config('service_pages.pages', []);
        $page = $pages[$slug] ?? null;

        if (!$page) {
            abort(404, 'Service page not found.');
        }

        $view = $page['view'] ?? null;
        if (!$view || !view()->exists($view)) {
            abort(404, 'Service page view is missing.');
        }

        return view($view, compact('page'));
    }
}

