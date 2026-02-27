<?php

return [
    'ga4_measurement_id' => env('SEO_GA4_MEASUREMENT_ID', ''),
    'gtm_container_id' => env('SEO_GTM_CONTAINER_ID', ''),
    'gsc_verification' => env('SEO_GSC_VERIFICATION', ''),
    'bing_verification' => env('SEO_BING_VERIFICATION', ''),

    'default_og_image' => env('SEO_DEFAULT_OG_IMAGE', '/images/og/home.png'),

    'social' => [
        'facebook' => env('SEO_SOCIAL_FACEBOOK_URL', ''),
        'instagram' => env('SEO_SOCIAL_INSTAGRAM_URL', ''),
        'youtube' => env('SEO_SOCIAL_YOUTUBE_URL', ''),
        'x' => env('SEO_SOCIAL_X_URL', ''),
        'linkedin' => env('SEO_SOCIAL_LINKEDIN_URL', ''),
    ],

    'indexable_routes' => [
        'home',
        'about',
        'contact',
        'services',
        'how-it-works',
        'benefits',
        'faq',
        'author',
        'terms',
        'privacy',
        'refund',
        'disclaimer',
        'bandkam-info',
        'vle.directory',
        'vle.show',
        'farmer-card-public',
        'reviews.index',
        'reviews.show',
        'services.landing.show',
    ],

    'route_og_images' => [
        'home' => '/images/og/home.png',
        'about' => '/images/og/about.png',
        'contact' => '/images/og/contact.png',
        'services' => '/images/og/services.png',
        'how-it-works' => '/images/og/how-it-works.png',
        'benefits' => '/images/og/benefits.png',
        'faq' => '/images/og/faq.png',
        'author' => '/images/og/author.png',
        'reviews.index' => '/images/og/reviews-index.png',
        'farmer-card-public' => '/images/og/farmer-id-online.png',
    ],
];

