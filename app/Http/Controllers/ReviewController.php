<?php

namespace App\Http\Controllers;

class ReviewController extends Controller
{
    /**
     * Static article registry.
     * Each key is the slug used in the URL.
     */
    private function articles(): array
    {
        return [
            'mahabocw-bandkam-kamgar-yojana-2026' => [
                'slug'        => 'mahabocw-bandkam-kamgar-yojana-2026',
                'title'       => 'महाराष्ट्र बांधकाम कामगार योजना (MAHABOCW) २०२६: संपूर्ण मार्गदर्शक',
                'title_en'    => 'MAHABOCW Bandkam Kamgar Yojana 2026: Complete Guide',
                'excerpt'     => 'महाराष्ट्र इमारत व इतर बांधकाम कामगार कल्याणकारी मंडळ (MAHABOCW) च्या सर्व योजना, नोंदणी प्रक्रिया, पात्रता निकष आणि लाभार्थी सांख्यिकी — फेब्रुवारी २०२६ पर्यंत अद्ययावत.',
                'icon'        => 'hard-hat',
                'color'       => 'amber',
                'category'    => 'शासकीय योजना',
                'date'        => '२० फेब्रुवारी २०२६',
                'read_time'   => '२५ मिनिटे',
                'view'        => 'reviews.mahabocw-bandkam-kamgar',
            ],
        ];
    }

    public function index()
    {
        $articles = $this->articles();
        return view('reviews.index', compact('articles'));
    }

    public function show(string $slug)
    {
        $articles = $this->articles();

        if (!isset($articles[$slug])) {
            abort(404, 'लेख सापडला नाही.');
        }

        $article = $articles[$slug];

        if (!view()->exists($article['view'])) {
            abort(404, 'लेख दृश्य सापडले नाही.');
        }

        return view($article['view'], compact('article', 'articles'));
    }
}
