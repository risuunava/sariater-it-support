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
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <!-- Form Header -->
        <div class="px-6 py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                    <i class="fas fa-plus-circle text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="font-heading text-xl font-bold text-gray-900">Form Laporan IT Support</h2>
                    <p class="text-sm text-gray-600">Isi form dengan detail untuk proses yang lebih cepat</p>
                </div>
            </div>
        </div>
        
        <!-- Form Content -->
        <form action="{{ route('karyawan.tickets.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="space-y-8">
                <!-- Title -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="title" class="block text-sm font-medium text-gray-900">
                            Judul Laporan *
                        </label>
                        <span class="text-xs text-gray-500">Wajib diisi</span>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-heading text-gray-400"></i>
                        </div>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               required
                               class="input-modern pl-10"
                               placeholder="Contoh: Printer tidak bisa mencetak di ruang Marketing"
                               value="{{ old('title') }}">
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        Buat judul yang jelas dan spesifik untuk membantu teknisi memahami masalah
                    </p>
                </div>

                <!-- Description -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="description" class="block text-sm font-medium text-gray-900">
                            Deskripsi Detail Masalah *
                        </label>
                        <span class="text-xs text-gray-500">Wajib diisi</span>
                    </div>
                    <div class="relative">
                        <div class="absolute top-3 left-3 pointer-events-none">
                            <i class="fas fa-align-left text-gray-400"></i>
                        </div>
                        <textarea id="description" 
                                  name="description" 
                                  rows="10"
                                  required
                                  class="input-modern pl-10 pt-3"
                                  placeholder="Jelaskan masalah secara detail:
1. Kapan masalah mulai terjadi?
2. Apa yang Anda lakukan sebelum masalah muncul?
3. Pesan error yang muncul (jika ada)?
4. Dampak terhadap pekerjaan?
5. Sudah dicoba apa saja?

Contoh: Printer Epson L3210 di ruang Marketing tiba-tiba tidak bisa mencetak sejak pagi tadi. Lampu indikator warna merah berkedip. Sudah dicoba restart printer dan ganti kabel USB, tetap tidak berfungsi. Membutuhkan cetak dokumen penting untuk meeting siang ini.">{{ old('description') }}</textarea>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-sm text-gray-500">
                        <span>Semakin detail, semakin cepat penyelesaian</span>
                        <span id="char-count">0 karakter</span>
                    </div>
                </div>

                <!-- Category & Priority -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-3">
                            <i class="fas fa-tags mr-2"></i>Kategori Masalah
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="category" value="hardware" class="sr-only peer" {{ old('category') == 'hardware' ? 'checked' : '' }}>
                                <div class="p-4 border-2 border-gray-200 rounded-xl text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition">
                                    <i class="fas fa-desktop text-blue-500 text-xl mb-2"></i>
                                    <p class="font-medium text-gray-900">Hardware</p>
                                    <p class="text-xs text-gray-500 mt-1">Printer, laptop, monitor, dll</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="category" value="software" class="sr-only peer" {{ old('category') == 'software' ? 'checked' : '' }}>
                                <div class="p-4 border-2 border-gray-200 rounded-xl text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition">
                                    <i class="fas fa-code text-blue-500 text-xl mb-2"></i>
                                    <p class="font-medium text-gray-900">Software</p>
                                    <p class="text-xs text-gray-500 mt-1">Windows, Office, antivirus, dll</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="category" value="network" class="sr-only peer" {{ old('category') == 'network' ? 'checked' : '' }}>
                                <div class="p-4 border-2 border-gray-200 rounded-xl text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition">
                                    <i class="fas fa-wifi text-blue-500 text-xl mb-2"></i>
                                    <p class="font-medium text-gray-900">Network</p>
                                    <p class="text-xs text-gray-500 mt-1">WiFi, internet, email, server</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="category" value="account" class="sr-only peer" {{ old('category') == 'account' ? 'checked' : '' }}>
                                <div class="p-4 border-2 border-gray-200 rounded-xl text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition">
                                    <i class="fas fa-user-shield text-blue-500 text-xl mb-2"></i>
                                    <p class="font-medium text-gray-900">Account</p>
                                    <p class="text-xs text-gray-500 mt-1">Password, login, akses</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-3">
                            <i class="fas fa-exclamation-circle mr-2"></i>Prioritas
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center p-3 border border-gray-200 rounded-xl hover:bg-gray-50 cursor-pointer transition">
                                <input type="radio" name="priority" value="low" class="h-4 w-4 text-blue-600" {{ old('priority') == 'low' ? 'checked' : 'checked' }}>
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-gray-900">Rendah</span>
                                        <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Bisa menunggu</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Masalah tidak mengganggu pekerjaan utama</p>
                                </div>
                            </label>
                            <label class="flex items-center p-3 border border-gray-200 rounded-xl hover:bg-gray-50 cursor-pointer transition">
                                <input type="radio" name="priority" value="medium" class="h-4 w-4 text-blue-600" {{ old('priority') == 'medium' ? 'checked' : '' }}>
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-gray-900">Sedang</span>
                                        <span class="ml-2 px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Perlu ditangani</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Mengganggu pekerjaan, perlu ditangani hari ini</p>
                                </div>
                            </label>
                            <label class="flex items-center p-3 border border-gray-200 rounded-xl hover:bg-gray-50 cursor-pointer transition">
                                <input type="radio" name="priority" value="high" class="h-4 w-4 text-blue-600" {{ old('priority') == 'high' ? 'checked' : '' }}>
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-gray-900">Tinggi</span>
                                        <span class="ml-2 px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">🚨 MENDESAK</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Menghentikan pekerjaan, butuh tindakan cepat</p>
                                </div>
                            </label>
                        </div>
                        <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <i class="fas fa-info-circle mr-1"></i>
                                <strong>Prioritas Tinggi:</strong> Akan langsung muncul di bagian "Laporan Mendesak" di dashboard
                            </p>
                        </div>
                    </div>
                </div>

                <!-- ============================================================ -->
                <!-- CHECKBOX UNTUK SIMULASI LAPORAN MENDESAK -->
                <!-- ============================================================ -->
                <div class="mt-6 p-5 bg-gradient-to-r from-yellow-50 to-amber-50 border-2 border-yellow-200 rounded-2xl">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 pt-1">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center">
                                <i class="fas fa-flask text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           name="simulate_urgent" 
                                           value="1"
                                           id="simulate_urgent"
                                           class="h-5 w-5 text-yellow-600 rounded border-2 border-yellow-400 focus:ring-yellow-500 focus:ring-2">
                                    <span class="ml-3 text-lg font-bold text-yellow-800">
                                        🧪 TESTING MODE: Simulasi Laporan Mendesak
                                    </span>
                                </label>
                            </div>
                            <div class="mt-3 pl-8">
                                <p class="text-sm text-yellow-700">
                                    <span class="font-semibold">Fitur ini untuk testing saja!</span> Centang kotak di atas jika ingin:
                                </p>
                                <ul class="mt-2 space-y-1 text-sm text-yellow-700">
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-yellow-500 mt-1 mr-2 text-xs"></i>
                                        <span>Mensimulasikan laporan yang dibuat <strong>3 hari lalu</strong></span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-yellow-500 mt-1 mr-2 text-xs"></i>
                                        <span>Laporan akan <strong>langsung muncul</strong> di bagian "Laporan Mendesak" di dashboard</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-yellow-500 mt-1 mr-2 text-xs"></i>
                                        <span>Cocok untuk <strong>testing fitur urgent tickets</strong> tanpa menunggu 24 jam</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-2 text-xs"></i>
                                        <span><strong>Catatan:</strong> Hanya untuk keperluan testing development</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================ -->

                <!-- Tips Card -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                <i class="fas fa-lightbulb text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-heading font-semibold text-gray-900 mb-2">Tips Membuat Laporan yang Baik</h4>
                            <ul class="space-y-2 text-sm text-gray-700">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Sertakan nomor aset/perangkat (jika ada)</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Foto/screenshot masalah sangat membantu</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Sebutkan lokasi perangkat yang bermasalah</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Catat pesan error lengkap jika ada</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Jelaskan langkah yang sudah dicoba</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-8 border-t border-gray-200">
                    <a href="{{ route('karyawan.dashboard') }}" class="btn-secondary">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Laporan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Character Count Script -->
<script>
    const textarea = document.getElementById('description');
    const charCount = document.getElementById('char-count');
    
    textarea.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = `${length} karakter`;
        
        if (length > 500) {
            charCount.classList.remove('text-gray-500');
            charCount.classList.add('text-green-600');
        } else {
            charCount.classList.remove('text-green-600');
            charCount.classList.add('text-gray-500');
        }
    });
    
    // Trigger initial count
    textarea.dispatchEvent(new Event('input'));

    // Auto-check priority based on keywords
    document.getElementById('description').addEventListener('input', function() {
        const text = this.value.toLowerCase();
        const highPriorityKeywords = ['darurat', 'mendesak', 'urgent', 'kritis', 'critical', 'server down', 'tidak bisa kerja', 'berhenti total', 'gangguan parah'];
        const mediumPriorityKeywords = ['lambat', 'error', 'trouble', 'masalah', 'gangguan', 'tidak normal'];
        
        let hasHighPriority = highPriorityKeywords.some(keyword => text.includes(keyword));
        let hasMediumPriority = mediumPriorityKeywords.some(keyword => text.includes(keyword));
        
        if (hasHighPriority) {
            document.querySelector('input[name="priority"][value="high"]').checked = true;
        } else if (hasMediumPriority) {
            document.querySelector('input[name="priority"][value="medium"]').checked = true;
        }
    });
</script>

<!-- Priority Badge CSS -->
<style>
.priority-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.priority-badge.low {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    border: 1px solid #10b981;
}

.priority-badge.medium {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
    border: 1px solid #fbbf24;
}

.priority-badge.high {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
    border: 1px solid #ef4444;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

.urgent-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 700;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border: 1px solid #b91c1c;
    animation: pulse 1.5s infinite;
}
</style>
@endsection