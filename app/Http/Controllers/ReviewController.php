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
            'farmer-id-card-online-guide-2026' => [
                'slug'        => 'farmer-id-card-online-guide-2026',
                'title'       => 'Farmer ID Card Online 2026: शेतकरी ओळखपत्र संपूर्ण मार्गदर्शक',
                'title_en'    => 'Farmer ID Card Online Guide 2026: How to Make, Benefits, PM Kisan',
                'excerpt'     => 'Farmer ID Card कसे बनवायचे — PM Kisan, Agristack, crop insurance, KCC loan. Maharashtra, West Bengal, UP, MP सर्व राज्यांसाठी step-by-step guide.',
                'icon'        => 'leaf',
                'color'       => 'green',
                'category'    => 'शेतकरी सेवा',
                'date'        => '१९ फेब्रुवारी २०२६',
                'read_time'   => '१२ मिनिटे',
                'view'        => 'reviews.farmer-id-card-guide',
            ],
            'ladki-bahin-yojana-maharashtra-2026' => [
                'slug'        => 'ladki-bahin-yojana-maharashtra-2026',
                'title'       => 'मुख्यमंत्री माझी लाडकी बहीण योजना २०२६: संपूर्ण माहिती',
                'title_en'    => 'Ladki Bahin Yojana Maharashtra 2026: Complete Guide',
                'excerpt'     => 'लाडकी बहीण योजना पात्रता, कागदपत्रे, अर्ज प्रक्रिया, DBT स्टेटस, आधार सीडिंग, लाभार्थी यादी — फेब्रुवारी २०२६ अद्ययावत.',
                'icon'        => 'heart',
                'color'       => 'pink',
                'category'    => 'महिला कल्याण योजना',
                'date'        => '१९ फेब्रुवारी २०२६',
                'read_time'   => '१५ मिनिटे',
                'view'        => 'reviews.ladki-bahin-yojana',
            ],
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
            'nirgam-utara-meaning-application-format-2026' => [
                'slug'        => 'nirgam-utara-meaning-application-format-2026',
                'title'       => 'निर्गम उतारा अर्ज व वापर — संपूर्ण मार्गदर्शक २०२६',
                'title_en'    => 'Nirgam Utara Meaning, Application & Use 2026',
                'excerpt'     => 'निर्गम उतारा म्हणजे काय, कसा मिळवायचा, अर्ज कसा लिहावा, TC/LC शी फरक — शाळा निर्गम दाखला संपूर्ण माहिती. Application format PDF download.',
                'icon'        => 'file-text',
                'color'       => 'amber',
                'category'    => 'शैक्षणिक कागदपत्रे',
                'date'        => '२१ फेब्रुवारी २०२६',
                'read_time'   => '१४ मिनिटे',
                'view'        => 'reviews.nirgam-utara-guide',
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
