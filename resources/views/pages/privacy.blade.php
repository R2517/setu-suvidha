@extends('layouts.app')
@section('title', 'गोपनीयता धोरण — SETU Suvidha')
@section('content')
<section class="py-20 bg-white dark:bg-gray-950">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 prose dark:prose-invert max-w-none">
        <h1 class="text-3xl font-bold">गोपनीयता धोरण (Privacy Policy)</h1>
        <p class="text-gray-500">शेवटचे अपडेट: {{ date('d M Y') }}</p>
        <h3>1. माहिती संकलन</h3>
        <p>आम्ही तुमचे नाव, ईमेल, मोबाईल नंबर आणि दुकान माहिती संकलित करतो. फॉर्म डेटा JSON स्वरूपात सुरक्षितपणे साठवला जातो.</p>
        <h3>2. माहितीचा वापर</h3>
        <p>तुमची माहिती फक्त सेवा प्रदान करण्यासाठी, वॉलेट व्यवहार प्रक्रिया करण्यासाठी आणि फॉर्म जनरेशनसाठी वापरली जाते.</p>
        <h3>3. डेटा सुरक्षा</h3>
        <p>SSL एन्क्रिप्शन, CSRF प्रोटेक्शन, bcrypt पासवर्ड हॅशिंग, Razorpay HMAC-SHA256 सिग्नेचर वेरिफिकेशन वापरले जाते.</p>
        <h3>4. कुकीज</h3>
        <p>आम्ही सेशन कुकीज आणि localStorage (थीम प्राधान्य, डार्क मोड) वापरतो.</p>
        <h3>5. तृतीय पक्ष</h3>
        <p>तुमचा डेटा कोणत्याही तृतीय पक्षासोबत विक्री किंवा शेअर केला जात नाही. Razorpay पेमेंट प्रक्रियेसाठी वापरला जातो.</p>
    </div>
</section>
@endsection
