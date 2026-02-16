<footer class="footer-section no-print bg-gray-950 text-gray-400 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
            {{-- Brand --}}
            <div>
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                        <i data-lucide="landmark" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <span class="text-lg font-bold text-white">SETU Suvidha</span>
                        <span class="block text-[10px] text-gray-500">सेतू सुविधा — ई-सेवा पोर्टल</span>
                    </div>
                </div>
                <p class="text-sm leading-relaxed">महाराष्ट्रातील सेतु केंद्र, CSC केंद्र आणि ई-सेवा दुकानदारांसाठी — सर्व सरकारी फॉर्म्स एकाच ठिकाणी.</p>
            </div>
            {{-- Pages --}}
            <div>
                <h4 class="text-white font-semibold mb-4">पृष्ठे</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-amber-400 transition">मुख्यपृष्ठ</a></li>
                    <li><a href="{{ route('services') }}" class="hover:text-amber-400 transition">सेवा</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-amber-400 transition">आमच्याबद्दल</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-amber-400 transition">संपर्क</a></li>
                    <li><a href="{{ url('/faq') }}" class="hover:text-amber-400 transition">FAQ</a></li>
                </ul>
            </div>
            {{-- Legal --}}
            <div>
                <h4 class="text-white font-semibold mb-4">कायदेशीर</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('terms') }}" class="hover:text-amber-400 transition">अटी व शर्ती</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-amber-400 transition">गोपनीयता धोरण</a></li>
                    <li><a href="{{ route('refund') }}" class="hover:text-amber-400 transition">परतावा धोरण</a></li>
                    <li><a href="{{ route('disclaimer') }}" class="hover:text-amber-400 transition">अस्वीकरण</a></li>
                </ul>
            </div>
            {{-- Contact --}}
            <div>
                <h4 class="text-white font-semibold mb-4">संपर्क</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center gap-2"><i data-lucide="mail" class="w-4 h-4 text-amber-500"></i> support@setusuvidha.com</li>
                    <li class="flex items-center gap-2"><i data-lucide="phone" class="w-4 h-4 text-amber-500"></i> +91 XXXXX XXXXX</li>
                    <li class="flex items-center gap-2"><i data-lucide="map-pin" class="w-4 h-4 text-amber-500"></i> महाराष्ट्र, भारत</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 pt-6 flex flex-col md:flex-row items-center justify-between text-xs text-gray-500">
            <p>© {{ date('Y') }} SETU Suvidha — सेतु सुविधा महा ई-सेवा पोर्टल. सर्व हक्क राखीव.</p>
            <div class="flex gap-4 mt-2 md:mt-0">
                <a href="{{ route('terms') }}" class="hover:text-amber-400 transition">Terms</a>
                <a href="{{ route('privacy') }}" class="hover:text-amber-400 transition">Privacy</a>
                <a href="{{ route('refund') }}" class="hover:text-amber-400 transition">Refund</a>
            </div>
        </div>
    </div>
</footer>
