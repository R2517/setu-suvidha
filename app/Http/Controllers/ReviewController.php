<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    private function articles(): array
    {
        return config('reviews.articles', []);
    }

    public function index(Request $request)
    {
        $searchQuery = trim((string) $request->query('q', ''));

        $articles = collect($this->articles())
            ->filter(function (array $article) use ($searchQuery) {
                if ($searchQuery === '') {
                    return true;
                }

                $haystack = implode(' ', [
                    (string) ($article['title'] ?? ''),
                    (string) ($article['title_en'] ?? ''),
                    (string) ($article['description'] ?? ''),
                    (string) ($article['excerpt'] ?? ''),
                    (string) ($article['category'] ?? ''),
                    (string) ($article['focus'] ?? ''),
                ]);

                return mb_stripos($haystack, $searchQuery) !== false;
            })
            ->sortByDesc(static fn (array $article) => (string) ($article['date_modified'] ?? ''))
            ->values()
            ->all();

        return view('reviews.index', compact('articles', 'searchQuery'));
    }

    public function show(string $slug)
    {
        $articles = $this->articles();
        $article = $articles[$slug] ?? null;

        if (!$article) {
            abort(404, 'Article not found.');
        }

        $view = $article['view'] ?? null;
        if (!$view || !view()->exists($view)) {
            abort(404, 'Article view is missing.');
        }

        return view($view, compact('article', 'articles'));
    }
}
