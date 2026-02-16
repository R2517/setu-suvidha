@extends('layouts.app')
@section('title', 'परतावा धोरण — SETU Suvidha')
@section('content')
<section class="py-20 bg-white dark:bg-gray-950">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 prose dark:prose-invert max-w-none">
        <h1 class="text-3xl font-bold">परतावा धोरण (Refund Policy)</h1>
        <p class="text-gray-500">शेवटचे अपडेट: {{ date('d M Y') }}</p>
        <h3>1. वॉलेट रिचार्ज</h3>
        <p>वॉलेट रिचार्ज रक्कम फॉर्म शुल्कासाठी वापरली जाते. तांत्रिक अडचणीमुळे रिचार्ज अयशस्वी झाल्यास पूर्ण रिफंड दिला जातो.</p>
        <h3>2. फॉर्म शुल्क</h3>
        <p>एकदा फॉर्म यशस्वीरित्या सबमिट आणि प्रिंट झाल्यावर शुल्क परतावा उपलब्ध नाही.</p>
        <h3>3. सबस्क्रिप्शन</h3>
        <p>सबस्क्रिप्शन प्लॅन खरेदीनंतर 7 दिवसांच्या आत रद्द केल्यास प्रो-रेटा रिफंड दिला जातो.</p>
        <h3>4. रिफंड प्रक्रिया</h3>
        <p>रिफंड 5-7 कामकाजाच्या दिवसांत मूळ पेमेंट पद्धतीद्वारे परत केला जातो. support@setusuvidha.com वर संपर्क करा.</p>
    </div>
</section>
@endsection
