<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;
        $walletBalance = $profile?->wallet_balance ?? 0;

        $allCards = $this->getServiceCards();
        $config = $profile?->dashboard_config;
        $order = $config['order'] ?? [];
        $hidden = $config['hidden'] ?? [];

        // Separate live and upcoming
        $liveCards = array_filter($allCards, fn($c) => $c['ready']);
        $upcomingCards = array_filter($allCards, fn($c) => !$c['ready']);

        // Apply custom order to live cards
        if (!empty($order)) {
            usort($liveCards, function ($a, $b) use ($order) {
                $posA = array_search($a['id'], $order);
                $posB = array_search($b['id'], $order);
                if ($posA === false) $posA = 999;
                if ($posB === false) $posB = 999;
                return $posA - $posB;
            });
        }

        // Apply hidden filter
        $liveCards = array_values(array_filter($liveCards, fn($c) => !in_array($c['id'], $hidden)));
        $upcomingCards = array_values($upcomingCards);

        $totalServices = count($allCards);
        $readyServices = count(array_filter($allCards, fn($c) => $c['ready']));

        // All cards for customize modal (with visibility state)
        $allCardsWithState = array_map(function ($c) use ($hidden) {
            $c['visible'] = !in_array($c['id'], $hidden);
            return $c;
        }, array_filter($allCards, fn($c) => $c['ready']));

        // Apply order for customize modal too
        if (!empty($order)) {
            usort($allCardsWithState, function ($a, $b) use ($order) {
                $posA = array_search($a['id'], $order);
                $posB = array_search($b['id'], $order);
                if ($posA === false) $posA = 999;
                if ($posB === false) $posB = 999;
                return $posA - $posB;
            });
        }
        $allCardsWithState = array_values($allCardsWithState);

        return view('dashboard.index', compact(
            'user', 'profile', 'walletBalance',
            'liveCards', 'upcomingCards', 'allCardsWithState',
            'totalServices', 'readyServices'
        ));
    }

    public function saveConfig(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'hidden' => 'present|array',
        ]);

        $profile = $request->user()->profile;
        $profile->update([
            'dashboard_config' => [
                'order' => $request->order,
                'hidden' => $request->hidden,
            ],
        ]);

        return response()->json(['success' => true]);
    }

    private function getServiceCards(): array
    {
        return [
            ['id' => 'hamipatra', 'title' => 'हमीपत्र (Disclaimer)', 'icon' => 'file-text', 'iconBg' => 'linear-gradient(135deg, #DBEAFE, #BFDBFE)', 'iconColor' => '#2563EB', 'path' => '/hamipatra', 'ready' => true, 'badge' => 'READY', 'badgeType' => 'ready'],
            ['id' => 'self-declaration', 'title' => 'स्वयंघोषणापत्र', 'icon' => 'shield', 'iconBg' => 'linear-gradient(135deg, #D1FAE5, #A7F3D0)', 'iconColor' => '#059669', 'path' => '/self-declaration', 'ready' => true, 'badge' => 'READY', 'badgeType' => 'ready'],
            ['id' => 'grievance', 'title' => 'तक्रार नोंदणी (Grievance)', 'icon' => 'alert-triangle', 'iconBg' => 'linear-gradient(135deg, #FEF3C7, #FDE68A)', 'iconColor' => '#D97706', 'path' => '/grievance', 'ready' => true, 'badge' => 'READY', 'badgeType' => 'ready'],
            ['id' => 'new-application', 'title' => 'नवीन अर्ज (New Application)', 'icon' => 'file-plus', 'iconBg' => 'linear-gradient(135deg, #EDE9FE, #DDD6FE)', 'iconColor' => '#7C3AED', 'path' => '/new-application', 'ready' => true, 'badge' => 'READY', 'badgeType' => 'ready'],
            ['id' => 'farmer-id', 'title' => 'शेतकरी ओळखपत्र (FARMER ID CARD)', 'icon' => 'leaf', 'iconBg' => 'linear-gradient(135deg, #DCFCE7, #BBF7D0)', 'iconColor' => '#16A34A', 'path' => '/farmer-id-card', 'ready' => true, 'badge' => 'NEW', 'badgeType' => 'new'],
            ['id' => 'passport-photo', 'title' => 'पासपोर्ट फोटो मेकर (Photo Maker)', 'icon' => 'camera', 'iconBg' => 'linear-gradient(135deg, #E0E7FF, #C7D2FE)', 'iconColor' => '#4F46E5', 'path' => '/passport-photo-maker', 'ready' => true, 'badge' => 'NEW', 'badgeType' => 'new'],
            ['id' => 'aadhaar-hub', 'title' => 'आधार सेवा केंद्र (Aadhaar Hub)', 'icon' => 'fingerprint', 'iconBg' => 'linear-gradient(135deg, #FFE4E6, #FECDD3)', 'iconColor' => '#E11D48', 'path' => '/aadhaar-hub', 'ready' => true, 'badge' => 'NEW', 'badgeType' => 'new'],
            ['id' => 'pan-card', 'title' => 'पॅन कार्ड सेवा (PAN Card)', 'icon' => 'credit-card', 'iconBg' => 'linear-gradient(135deg, #E0E7FF, #C7D2FE)', 'iconColor' => '#4338CA', 'path' => '/pan-card', 'ready' => false, 'badge' => 'FAST', 'badgeType' => 'fast'],
            ['id' => 'bond-format', 'title' => 'बॉण्ड फॉर्मॅट (Legal Bonds)', 'icon' => 'file-text', 'iconBg' => 'linear-gradient(135deg, #FFF7ED, #FED7AA)', 'iconColor' => '#EA580C', 'path' => '/bond-formats', 'ready' => true, 'badge' => 'NEW', 'badgeType' => 'new'],
            ['id' => 'income-cert', 'title' => 'उत्पन्नाचे स्वयंघोषणापत्र', 'icon' => 'landmark', 'iconBg' => 'linear-gradient(135deg, #FCE7F3, #FBCFE8)', 'iconColor' => '#DB2777', 'path' => '/income-cert', 'ready' => true, 'badge' => 'READY', 'badgeType' => 'ready'],
            ['id' => 'revenue-notice', 'title' => 'राजपत्र नमुना नोटीस', 'icon' => 'scale', 'iconBg' => 'linear-gradient(135deg, #ECFDF5, #BBF7D0)', 'iconColor' => '#16A34A', 'path' => '/rajpatra', 'ready' => true, 'badge' => 'READY', 'badgeType' => 'ready'],
            ['id' => 'caste-cert', 'title' => 'जात प्रमाणपत्रासाठीचे शपथपत्र', 'icon' => 'users', 'iconBg' => 'linear-gradient(135deg, #FDF4FF, #F5D0FE)', 'iconColor' => '#A855F7', 'path' => '/caste-cert', 'ready' => false, 'badge' => '', 'badgeType' => ''],
            ['id' => 'ews', 'title' => 'EWS प्रमाणपत्रासाठीचा अर्ज', 'icon' => 'book-open', 'iconBg' => 'linear-gradient(135deg, #F0FDF4, #BBF7D0)', 'iconColor' => '#15803D', 'path' => '/ews', 'ready' => false, 'badge' => '', 'badgeType' => ''],
            ['id' => 'landless', 'title' => 'भूमिहीन प्रमाणपत्रासाठी अर्ज', 'icon' => 'leaf', 'iconBg' => 'linear-gradient(135deg, #ECFCCB, #BEF264)', 'iconColor' => '#4D7C0F', 'path' => '/landless', 'ready' => false, 'badge' => '', 'badgeType' => ''],
            ['id' => 'annasaheb', 'title' => 'अण्णासाहेब पाटील योजनेचा अर्ज', 'icon' => 'award', 'iconBg' => 'linear-gradient(135deg, #FFE4E6, #FDA4AF)', 'iconColor' => '#BE123C', 'path' => '/annasaheb', 'ready' => false, 'badge' => '', 'badgeType' => ''],
            ['id' => 'minority', 'title' => 'अल्पभूधारक प्रमाणपत्रासाठी अर्ज', 'icon' => 'file-check', 'iconBg' => 'linear-gradient(135deg, #F3E8FF, #E9D5FF)', 'iconColor' => '#9333EA', 'path' => '/minority', 'ready' => false, 'badge' => '', 'badgeType' => ''],
            ['id' => 'non-creamy', 'title' => 'नॉन क्रिमिलीयर प्रमाणपत्रासाठी शपथपत्र', 'icon' => 'graduation-cap', 'iconBg' => 'linear-gradient(135deg, #FEF9C3, #FDE047)', 'iconColor' => '#A16207', 'path' => '/non-creamy', 'ready' => false, 'badge' => '', 'badgeType' => ''],
            ['id' => 'caste-validity', 'title' => 'जात पडताळणी', 'icon' => 'badge-check', 'iconBg' => 'linear-gradient(135deg, #CCFBF1, #99F6E4)', 'iconColor' => '#0D9488', 'path' => '/caste-validity', 'ready' => true, 'badge' => 'READY', 'badgeType' => 'ready'],
            ['id' => 'domicile', 'title' => 'अधिवास प्रमाणपत्रासाठी स्वयंघोषणापत्र', 'icon' => 'home', 'iconBg' => 'linear-gradient(135deg, #DBEAFE, #93C5FD)', 'iconColor' => '#1D4ED8', 'path' => '/domicile', 'ready' => false, 'badge' => '', 'badgeType' => ''],
        ];
    }
}
