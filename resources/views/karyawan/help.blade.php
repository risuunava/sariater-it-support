@extends('layouts.app')

@section('title', 'Bantuan & Support')
@section('page-title', 'Bantuan & Support')
@section('page-description', 'Dapatkan bantuan dan panduan penggunaan sistem')

@section('header-actions')
<div class="flex items-center space-x-3">
    <a href="{{ route('karyawan.dashboard') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Dashboard
    </a>
</div>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Quick Help Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Emergency Contact -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-phone-alt text-xl"></i>
                </div>
                <div>
                    <h3 class="font-heading font-bold text-lg">Emergency Contact</h3>
                    <p class="text-red-100 text-sm">Darurat & Prioritas Tinggi</p>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="p-4 rounded-xl bg-white/10">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-phone"></i>
                        <div>
                            <p class="font-medium">Hotline IT Support</p>
                            <p class="text-red-100">(021) 1234-5678</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 rounded-xl bg-white/10">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <p class="font-medium">Email Darurat</p>
                            <p class="text-red-100">emergency@sariater.com</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 rounded-xl bg-white/10">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-clock"></i>
                        <div>
                            <p class="font-medium">Jam Operasional</p>
                            <p class="text-red-100">24/7 untuk masalah kritis</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Support Channels -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                    <i class="fas fa-headset text-white"></i>
                </div>
                <div>
                    <h3 class="font-heading font-semibold text-gray-900">Support Channels</h3>
                    <p class="text-sm text-gray-600">Pilih cara menghubungi kami</p>
                </div>
            </div>
            
            <div class="space-y-3">
                <a href="#" 
                   class="flex items-center justify-between p-4 rounded-xl bg-blue-50 hover:bg-blue-100 transition group">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center">
                            <i class="fas fa-comments text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Live Chat</p>
                            <p class="text-sm text-gray-600">Chat langsung dengan IT Support</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-600"></i>
                </a>
                
                <a href="mailto:support@sariater.com" 
                   class="flex items-center justify-between p-4 rounded-xl bg-green-50 hover:bg-green-100 transition group">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-green-500 flex items-center justify-center">
                            <i class="fas fa-envelope text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Email Support</p>
                            <p class="text-sm text-gray-600">support@sariater.com</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-green-600"></i>
                </a>
                
                <a href="#" 
                   class="flex items-center justify-between p-4 rounded-xl bg-purple-50 hover:bg-purple-100 transition group">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-500 flex items-center justify-center">
                            <i class="fas fa-video text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Video Call</p>
                            <p class="text-sm text-gray-600">Meeting virtual dengan teknisi</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-purple-600"></i>
                </a>
            </div>
        </div>
        
        <!-- System Status -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-heading font-bold text-gray-900">System Status</h3>
                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                    <i class="fas fa-circle mr-1"></i> Operational
                </span>
            </div>
            
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Ticket System</span>
                        <span class="font-medium text-green-600">100%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-full rounded-full bg-green-500" style="width: 100%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Response Time</span>
                        <span class="font-medium text-green-600">Normal</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-full rounded-full bg-green-500" style="width: 85%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Support Availability</span>
                        <span class="font-medium text-green-600">High</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-full rounded-full bg-green-500" style="width: 90%"></div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-600">Last Updated: {{ now()->format('d M Y, H:i') }}</p>
                <p class="text-xs text-gray-500 mt-1">Updated every 15 minutes</p>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <div class="flex items-center space-x-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center">
                <i class="fas fa-question-circle text-white"></i>
            </div>
            <div>
                <h2 class="font-heading text-xl font-bold text-gray-900">Frequently Asked Questions</h2>
                <p class="text-sm text-gray-600 mt-1">Pertanyaan umum tentang sistem</p>
            </div>
        </div>
        
        <div class="space-y-4">
            @foreach($faqs as $faq)
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <button class="w-full flex justify-between items-center p-4 text-left hover:bg-gray-50 transition faq-toggle">
                    <span class="font-medium text-gray-900">{{ $faq['question'] }}</span>
                    <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                </button>
                <div class="faq-content hidden px-4 pb-4">
                    <p class="text-gray-600">{{ $faq['answer'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Guides & Tutorials -->
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-heading text-xl font-bold text-gray-900">Guides & Tutorials</h2>
                <p class="text-sm text-gray-600 mt-1">Panduan lengkap penggunaan sistem</p>
            </div>
            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Lihat semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($guides as $guide)
            <a href="#" class="group">
                <div class="bg-gray-50 rounded-xl p-6 hover:bg-blue-50 transition">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                        <i class="{{ $guide['icon'] }} text-white text-lg"></i>
                    </div>
                    <h4 class="font-medium text-gray-900 group-hover:text-blue-600">{{ $guide['title'] }}</h4>
                    <div class="mt-2 text-sm text-gray-500 flex items-center">
                        <span>Baca panduan</span>
                        <i class="fas fa-arrow-right ml-2 opacity-0 group-hover:opacity-100 transition"></i>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Quick Tips -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Best Practices -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                    <i class="fas fa-lightbulb text-white"></i>
                </div>
                <div>
                    <h3 class="font-heading font-semibold text-gray-900">Best Practices</h3>
                    <p class="text-sm text-blue-600">Tips membuat laporan efektif</p>
                </div>
            </div>
            
            <ul class="space-y-3">
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                    <span class="text-sm text-gray-700">Sertakan detail spesifik (model perangkat, nomor aset)</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                    <span class="text-sm text-gray-700">Lampirkan screenshot atau foto jika memungkinkan</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                    <span class="text-sm text-gray-700">Deskripsikan langkah-langkah yang sudah dicoba</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                    <span class="text-sm text-gray-700">Tentukan prioritas yang sesuai dengan urgensi</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                    <span class="text-sm text-gray-700">Update status laporan jika ada perkembangan</span>
                </li>
            </ul>
        </div>
        
        <!-- Common Issues -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                    <i class="fas fa-tools text-white"></i>
                </div>
                <div>
                    <h3 class="font-heading font-semibold text-gray-900">Common Issues</h3>
                    <p class="text-sm text-green-600">Solusi masalah umum</p>
                </div>
            </div>
            
            <div class="space-y-4">
                <div>
                    <p class="font-medium text-gray-900 text-sm">Printer tidak bisa mencetak</p>
                    <p class="text-xs text-gray-600 mt-1">Cek kabel, restart printer, update driver</p>
                </div>
                <div>
                    <p class="font-medium text-gray-900 text-sm">Koneksi WiFi lemah</p>
                    <p class="text-xs text-gray-600 mt-1">Restart router, cek sinyal, hubungi IT untuk survey</p>
                </div>
                <div>
                    <p class="font-medium text-gray-900 text-sm">Email tidak terkirim</p>
                    <p class="text-xs text-gray-600 mt-1">Cek koneksi internet, ukuran attachment, quota email</p>
                </div>
                <div>
                    <p class="font-medium text-gray-900 text-sm">Software error</p>
                    <p class="text-xs text-gray-600 mt-1">Update software, clear cache, reinstall aplikasi</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FAQ JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ toggle
    document.querySelectorAll('.faq-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const icon = this.querySelector('i');
            
            // Toggle content
            content.classList.toggle('hidden');
            
            // Rotate icon
            if (content.classList.contains('hidden')) {
                icon.style.transform = 'rotate(0deg)';
            } else {
                icon.style.transform = 'rotate(180deg)';
            }
            
            // Close other FAQs
            document.querySelectorAll('.faq-toggle').forEach(otherButton => {
                if (otherButton !== this) {
                    const otherContent = otherButton.nextElementSibling;
                    const otherIcon = otherButton.querySelector('i');
                    otherContent.classList.add('hidden');
                    otherIcon.style.transform = 'rotate(0deg)';
                }
            });
        });
    });
    
    // Emergency contact click
    document.querySelectorAll('a[href^="tel:"]').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('Apakah Anda ingin menghubungi IT Support?')) {
                e.preventDefault();
            }
        });
    });
});
</script>

<style>
.faq-toggle i {
    transition: transform 0.3s ease;
}

.faq-content {
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection