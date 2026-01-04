@extends('layouts.app')

@section('title', 'Buat Laporan Baru')
@section('page-title', 'Buat Laporan Baru')
@section('page-description', 'Buat laporan IT Support baru untuk masalah teknis yang Anda hadapi')

@section('header-actions')
<div class="flex items-center space-x-3">
    <a href="{{ route('karyawan.dashboard') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6">
    <!-- Mobile Progress Bar -->
    <div class="lg:hidden mb-6">
        <div class="flex justify-between text-sm text-gray-600 mb-2">
            <span>Progress: 25%</span>
            <span>Langkah 1 dari 4</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-blue-600 h-2 rounded-full" style="width: 25%" id="progressBar"></div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-5 border-b border-blue-200">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-lg bg-blue-600 flex items-center justify-center">
                    <i class="fas fa-plus text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Form Laporan IT Support</h1>
                    <p class="text-gray-600 text-sm mt-1">Isi dengan detail untuk proses cepat</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('karyawan.tickets.store') }}" method="POST" class="p-6" id="ticketForm">
            @csrf
            
            <div class="space-y-6">
                <!-- Title -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block font-medium text-gray-900">
                            <i class="fas fa-heading text-blue-500 mr-2"></i>
                            Judul Laporan
                        </label>
                        <span class="text-xs text-gray-500">Wajib diisi</span>
                    </div>
                    <input type="text" 
                           name="title" 
                           value="{{ old('title') }}"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="Contoh: Printer tidak bisa mencetak"
                           oninput="updateProgress()">
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                        Buat judul yang jelas dan spesifik
                    </p>
                </div>

                <!-- Description -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block font-medium text-gray-900">
                            <i class="fas fa-align-left text-blue-500 mr-2"></i>
                            Deskripsi Masalah
                        </label>
                        <span class="text-xs text-gray-500">Wajib diisi</span>
                    </div>
                    <textarea name="description" 
                              rows="6"
                              required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-none"
                              placeholder="Jelaskan masalah secara detail..."
                              oninput="updateCharCount(this); updateProgress()">{{ old('description') }}</textarea>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">
                            <i class="fas fa-ruler mr-1"></i>
                            <span id="charCount">0 karakter</span>
                        </span>
                        <span class="text-blue-600 font-medium hidden sm:block">
                            Semakin detail, semakin cepat penyelesaian
                        </span>
                    </div>
                </div>

                <!-- Category & Priority - Mobile Accordion -->
                <div class="lg:hidden">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <button type="button" onclick="toggleAccordion('mobileCategories')" class="w-full flex justify-between items-center">
                            <div class="flex items-center">
                                <i class="fas fa-tags text-blue-500 mr-2"></i>
                                <span class="font-medium">Kategori & Prioritas</span>
                            </div>
                            <i class="fas fa-chevron-down" id="accordionArrow"></i>
                        </button>
                        
                        <div id="mobileCategories" class="mt-4 space-y-4 hidden">
                            <!-- Categories -->
                            <div class="space-y-3">
                                <h4 class="font-medium text-gray-900">Kategori Masalah</h4>
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="block">
                                        <input type="radio" name="category" value="hardware" class="hidden peer" {{ old('category') == 'hardware' ? 'checked' : '' }}>
                                        <div class="p-3 border-2 border-gray-200 rounded-lg text-center peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                            <i class="fas fa-desktop text-blue-500 text-lg mb-1"></i>
                                            <p class="text-sm font-medium">Hardware</p>
                                        </div>
                                    </label>
                                    <label class="block">
                                        <input type="radio" name="category" value="software" class="hidden peer" {{ old('category') == 'software' ? 'checked' : '' }}>
                                        <div class="p-3 border-2 border-gray-200 rounded-lg text-center peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                            <i class="fas fa-code text-blue-500 text-lg mb-1"></i>
                                            <p class="text-sm font-medium">Software</p>
                                        </div>
                                    </label>
                                    <label class="block">
                                        <input type="radio" name="category" value="network" class="hidden peer" {{ old('category') == 'network' ? 'checked' : '' }}>
                                        <div class="p-3 border-2 border-gray-200 rounded-lg text-center peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                            <i class="fas fa-wifi text-blue-500 text-lg mb-1"></i>
                                            <p class="text-sm font-medium">Network</p>
                                        </div>
                                    </label>
                                    <label class="block">
                                        <input type="radio" name="category" value="account" class="hidden peer" {{ old('category') == 'account' ? 'checked' : '' }}>
                                        <div class="p-3 border-2 border-gray-200 rounded-lg text-center peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                            <i class="fas fa-user-shield text-blue-500 text-lg mb-1"></i>
                                            <p class="text-sm font-medium">Account</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Priority -->
                            <div class="space-y-3">
                                <h4 class="font-medium text-gray-900">Prioritas</h4>
                                <div class="space-y-2">
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg">
                                        <input type="radio" name="priority" value="low" class="mr-3" {{ old('priority') == 'low' ? 'checked' : 'checked' }}>
                                        <div>
                                            <div class="flex items-center">
                                                <span class="font-medium">Rendah</span>
                                                <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Bisa menunggu</span>
                                            </div>
                                            <p class="text-xs text-gray-500">Masalah tidak mengganggu</p>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg">
                                        <input type="radio" name="priority" value="medium" class="mr-3" {{ old('priority') == 'medium' ? 'checked' : '' }}>
                                        <div>
                                            <div class="flex items-center">
                                                <span class="font-medium">Sedang</span>
                                                <span class="ml-2 px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Perlu ditangani</span>
                                            </div>
                                            <p class="text-xs text-gray-500">Mengganggu pekerjaan</p>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg">
                                        <input type="radio" name="priority" value="high" class="mr-3" {{ old('priority') == 'high' ? 'checked' : '' }}>
                                        <div>
                                            <div class="flex items-center">
                                                <span class="font-medium">Tinggi</span>
                                                <span class="ml-2 px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Mendesak</span>
                                            </div>
                                            <p class="text-xs text-gray-500">Butuh tindakan cepat</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category & Priority - Desktop -->
                <div class="hidden lg:grid grid-cols-2 gap-6">
                    <!-- Category -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <i class="fas fa-tags text-blue-500 mr-2"></i>
                            <h3 class="font-medium text-gray-900">Kategori Masalah</h3>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="block">
                                <input type="radio" name="category" value="hardware" class="hidden peer" {{ old('category') == 'hardware' ? 'checked' : '' }}>
                                <div class="p-4 border-2 border-gray-200 rounded-lg text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition">
                                    <i class="fas fa-desktop text-blue-500 text-xl mb-2"></i>
                                    <p class="font-medium">Hardware</p>
                                    <p class="text-xs text-gray-500">Printer, laptop, monitor</p>
                                </div>
                            </label>
                            <label class="block">
                                <input type="radio" name="category" value="software" class="hidden peer" {{ old('category') == 'software' ? 'checked' : '' }}>
                                <div class="p-4 border-2 border-gray-200 rounded-lg text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition">
                                    <i class="fas fa-code text-blue-500 text-xl mb-2"></i>
                                    <p class="font-medium">Software</p>
                                    <p class="text-xs text-gray-500">Windows, Office, app</p>
                                </div>
                            </label>
                            <label class="block">
                                <input type="radio" name="category" value="network" class="hidden peer" {{ old('category') == 'network' ? 'checked' : '' }}>
                                <div class="p-4 border-2 border-gray-200 rounded-lg text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition">
                                    <i class="fas fa-wifi text-blue-500 text-xl mb-2"></i>
                                    <p class="font-medium">Network</p>
                                    <p class="text-xs text-gray-500">WiFi, internet, server</p>
                                </div>
                            </label>
                            <label class="block">
                                <input type="radio" name="category" value="account" class="hidden peer" {{ old('category') == 'account' ? 'checked' : '' }}>
                                <div class="p-4 border-2 border-gray-200 rounded-lg text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition">
                                    <i class="fas fa-user-shield text-blue-500 text-xl mb-2"></i>
                                    <p class="font-medium">Account</p>
                                    <p class="text-xs text-gray-500">Password, login, akses</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Priority -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-blue-500 mr-2"></i>
                            <h3 class="font-medium text-gray-900">Prioritas</h3>
                        </div>
                        <div class="space-y-3">
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                <input type="radio" name="priority" value="low" class="mr-3" {{ old('priority') == 'low' ? 'checked' : 'checked' }}>
                                <div>
                                    <div class="flex items-center">
                                        <span class="font-medium">Rendah</span>
                                        <span class="ml-3 px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">Bisa menunggu</span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">Masalah tidak mengganggu pekerjaan utama</p>
                                </div>
                            </label>
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                <input type="radio" name="priority" value="medium" class="mr-3" {{ old('priority') == 'medium' ? 'checked' : '' }}>
                                <div>
                                    <div class="flex items-center">
                                        <span class="font-medium">Sedang</span>
                                        <span class="ml-3 px-3 py-1 bg-yellow-100 text-yellow-800 text-sm rounded-full">Perlu ditangani</span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">Mengganggu pekerjaan, perlu ditangani hari ini</p>
                                </div>
                            </label>
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                <input type="radio" name="priority" value="high" class="mr-3" {{ old('priority') == 'high' ? 'checked' : '' }}>
                                <div>
                                    <div class="flex items-center">
                                        <span class="font-medium">Tinggi</span>
                                        <span class="ml-3 px-3 py-1 bg-red-100 text-red-800 text-sm rounded-full">🚨 MENDESAK</span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">Menghentikan pekerjaan, butuh tindakan cepat</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Testing Mode -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-5">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-lg bg-yellow-500 flex items-center justify-center">
                                <i class="fas fa-flask text-white"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-start justify-between">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="simulate_urgent" value="1" class="w-4 h-4">
                                    <div>
                                        <span class="font-bold text-yellow-900">🧪 Simulasi Laporan Mendesak</span>
                                        <p class="text-sm text-yellow-700 mt-1">Untuk testing fitur urgent tickets</p>
                                    </div>
                                </label>
                                <button type="button" onclick="toggleTestingInfo()" class="text-yellow-600">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </div>
                            
                            <div id="testingInfo" class="mt-3 bg-white p-4 rounded-lg border border-yellow-300 hidden">
                                <ul class="space-y-2 text-sm text-yellow-800">
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-yellow-500 mr-2 mt-0.5"></i>
                                        <span>Laporan dibuat 3 hari lalu</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-yellow-500 mr-2 mt-0.5"></i>
                                        <span>Langsung muncul di "Laporan Mendesak"</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-2 mt-0.5"></i>
                                        <span>Hanya untuk testing development</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tips -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-5">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center">
                                <i class="fas fa-lightbulb text-white"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900 mb-3">Tips Laporan yang Baik</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="flex items-start space-x-2">
                                    <i class="fas fa-check text-green-500 mt-0.5"></i>
                                    <span class="text-sm">Sertakan nomor aset/perangkat</span>
                                </div>
                                <div class="flex items-start space-x-2">
                                    <i class="fas fa-check text-green-500 mt-0.5"></i>
                                    <span class="text-sm">Foto/screenshot masalah</span>
                                </div>
                                <div class="flex items-start space-x-2">
                                    <i class="fas fa-check text-green-500 mt-0.5"></i>
                                    <span class="text-sm">Sebutkan lokasi perangkat</span>
                                </div>
                                <div class="flex items-start space-x-2">
                                    <i class="fas fa-check text-green-500 mt-0.5"></i>
                                    <span class="text-sm">Catat pesan error lengkap</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('karyawan.dashboard') }}" class="btn-secondary order-2 sm:order-1">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <div class="order-1 sm:order-2">
                        <button type="submit" class="btn-primary w-full sm:w-auto">
                            <i class="fas fa-paper-plane mr-2"></i>Kirim Laporan
                        </button>
                        <p class="text-xs text-gray-500 mt-2 text-center sm:text-right">
                            Diproses dalam 1x24 jam
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Mobile Floating Button -->
    <div class="fixed bottom-6 right-6 lg:hidden">
        <button type="submit" form="ticketForm" class="w-14 h-14 rounded-full bg-blue-600 text-white shadow-lg flex items-center justify-center hover:bg-blue-700 transition">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>

<script>
    // Character Count
    function updateCharCount(textarea) {
        const count = textarea.value.length;
        document.getElementById('charCount').textContent = `${count} karakter`;
        
        if (count > 500) {
            document.getElementById('charCount').classList.add('text-green-600', 'font-medium');
        } else {
            document.getElementById('charCount').classList.remove('text-green-600', 'font-medium');
        }
    }
    
    // Progress Bar
    function updateProgress() {
        const title = document.querySelector('input[name="title"]').value.length;
        const desc = document.querySelector('textarea[name="description"]').value.length;
        
        let progress = 0;
        if (title > 0) progress += 25;
        if (desc > 50) progress += 25;
        
        document.getElementById('progressBar').style.width = `${progress}%`;
        
        // Update progress text
        const progressText = document.querySelector('.lg\\:hidden .text-sm span:first-child');
        if (progressText) {
            progressText.textContent = `Progress: ${progress}%`;
        }
    }
    
    // Accordion Toggle
    function toggleAccordion(id) {
        const element = document.getElementById(id);
        const arrow = document.getElementById('accordionArrow');
        
        if (element.classList.contains('hidden')) {
            element.classList.remove('hidden');
            arrow.classList.add('rotate-180');
        } else {
            element.classList.add('hidden');
            arrow.classList.remove('rotate-180');
        }
    }
    
    // Testing Info Toggle
    function toggleTestingInfo() {
        const element = document.getElementById('testingInfo');
        element.classList.toggle('hidden');
    }
    
    // Auto-detect priority
    document.querySelector('textarea[name="description"]').addEventListener('input', function() {
        const text = this.value.toLowerCase();
        const highKeywords = ['darurat', 'mendesak', 'urgent', 'kritis', 'down', 'berhenti', 'gagal total'];
        const mediumKeywords = ['lambat', 'error', 'trouble', 'masalah', 'gangguan'];
        
        if (highKeywords.some(keyword => text.includes(keyword))) {
            document.querySelector('input[name="priority"][value="high"]').checked = true;
        } else if (mediumKeywords.some(keyword => text.includes(keyword))) {
            document.querySelector('input[name="priority"][value="medium"]').checked = true;
        }
    });
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updateCharCount(document.querySelector('textarea[name="description"]'));
        updateProgress();
        
        // Auto-open mobile accordion if needed
        if (window.innerWidth < 1024) {
            setTimeout(() => toggleAccordion('mobileCategories'), 500);
        }
    });
</script>

<style>
    .btn-primary {
        @apply bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2;
    }
    
    .btn-secondary {
        @apply bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-6 py-3 rounded-lg border border-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2;
    }
    
    .rotate-180 {
        transform: rotate(180deg);
        transition: transform 0.3s ease;
    }
    
    input[type="radio"]:checked + div {
        @apply border-blue-500 bg-blue-50;
    }
    
    @media (max-width: 640px) {
        .space-y-6 > * + * {
            margin-top: 1.5rem;
        }
        
        .p-6 {
            padding: 1.5rem;
        }
        
        input, textarea {
            font-size: 16px; /* Prevent zoom on iOS */
        }
    }
</style>
@endsection