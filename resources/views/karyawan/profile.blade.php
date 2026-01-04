@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('page-description', 'Kelola informasi profil dan pengaturan akun')

@section('header-actions')
<div class="flex items-center space-x-3">
    <a href="{{ route('karyawan.dashboard') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Dashboard
    </a>
</div>
@endsection

@section('content')
<div class="pb-6">
    <!-- Profile Header (Desktop & Mobile) -->
    <div class="mb-6 lg:mb-8">
        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-2xl lg:rounded-3xl overflow-hidden shadow-xl">
            <div class="p-6 lg:p-8 text-white">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <div class="w-20 h-20 lg:w-24 lg:h-24 rounded-2xl bg-white/20 backdrop-blur-sm border-2 border-white/30 flex items-center justify-center shadow-2xl">
                                <div class="w-16 h-16 lg:w-20 lg:h-20 rounded-xl bg-gradient-to-br from-white to-blue-100 flex items-center justify-center">
                                    <span class="text-2xl lg:text-3xl font-bold bg-gradient-to-br from-blue-600 to-indigo-700 bg-clip-text text-transparent">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="absolute -bottom-2 -right-2 w-8 h-8 lg:w-10 lg:h-10 bg-gradient-to-br from-emerald-400 to-green-500 rounded-full border-4 border-white flex items-center justify-center">
                                <i class="fas fa-check text-xs text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h1 class="text-2xl lg:text-3xl font-bold mb-1">{{ Auth::user()->name }}</h1>
                            <p class="text-blue-100 text-sm lg:text-base">{{ Auth::user()->email }}</p>
                            <div class="flex items-center space-x-2 mt-2">
                                <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-medium backdrop-blur-sm">
                                    <i class="fas fa-user-tie mr-1"></i>Karyawan
                                </span>
                                <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-medium backdrop-blur-sm">
                                    <i class="fas fa-calendar-alt mr-1"></i>Bergabung {{ $user->created_at->format('M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Member ID Badge -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 lg:p-5 border border-white/20">
                        <p class="text-xs text-blue-100 mb-1">Member ID</p>
                        <p class="text-xl lg:text-2xl font-mono font-bold tracking-wider">
                            {{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Profile & Settings -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Information Card -->
            <div class="bg-white rounded-2xl shadow-soft-xl border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100">
                    <div class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-md">
                                <i class="fas fa-user-cog text-white text-lg"></i>
                            </div>
                            <div>
                                <h2 class="font-heading font-bold text-xl text-gray-900">Informasi Profil</h2>
                                <p class="text-gray-600 text-sm">Kelola data diri dan informasi kontak</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('karyawan.profile.update') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Grid untuk mobile lebih compact -->
                        <div class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-user text-blue-500 mr-2"></i>Nama Lengkap
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           value="{{ old('name', $user->name) }}"
                                           required
                                           class="input-modern w-full px-4 py-3 text-base"
                                           placeholder="Nama lengkap">
                                    @error('name')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                    @enderror
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-envelope text-blue-500 mr-2"></i>Email
                                    </label>
                                    <input type="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}"
                                           required
                                           class="input-modern w-full px-4 py-3 text-base"
                                           placeholder="email@example.com">
                                    @error('email')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-phone text-blue-500 mr-2"></i>Nomor Telepon
                                    </label>
                                    <input type="text" 
                                           name="phone" 
                                           value="{{ old('phone', $user->phone ?? '') }}"
                                           class="input-modern w-full px-4 py-3 text-base"
                                           placeholder="0812-3456-7890">
                                    @error('phone')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                    @enderror
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-building text-blue-500 mr-2"></i>Departemen
                                    </label>
                                    <input type="text" 
                                           name="department" 
                                           value="{{ old('department', $user->department ?? '') }}"
                                           class="input-modern w-full px-4 py-3 text-base"
                                           placeholder="IT, HR, Marketing, dll">
                                    @error('department')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-6 border-t border-gray-100 flex justify-end">
                            <button type="submit" class="btn-primary px-8 py-3">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Card -->
            <div class="bg-white rounded-2xl shadow-soft-xl border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100">
                    <div class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-pink-600 flex items-center justify-center shadow-md">
                                <i class="fas fa-shield-alt text-white text-lg"></i>
                            </div>
                            <div>
                                <h2 class="font-heading font-bold text-xl text-gray-900">Keamanan Akun</h2>
                                <p class="text-gray-600 text-sm">Ubah password untuk keamanan akun</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('karyawan.profile.password') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="space-y-5">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">
                                    <i class="fas fa-key text-red-500 mr-2"></i>Password Saat Ini
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           name="current_password" 
                                           required
                                           class="input-modern w-full px-4 py-3 text-base pr-12"
                                           placeholder="Masukkan password saat ini">
                                    <button type="button" 
                                            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                                            onclick="togglePassword(this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-lock text-red-500 mr-2"></i>Password Baru
                                    </label>
                                    <div class="relative">
                                        <input type="password" 
                                               name="new_password" 
                                               required
                                               class="input-modern w-full px-4 py-3 text-base pr-12"
                                               placeholder="Minimal 8 karakter">
                                        <button type="button" 
                                                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                                                onclick="togglePassword(this)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('new_password')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                    @enderror
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                                        <i class="fas fa-lock text-red-500 mr-2"></i>Konfirmasi Password
                                    </label>
                                    <div class="relative">
                                        <input type="password" 
                                               name="new_password_confirmation" 
                                               required
                                               class="input-modern w-full px-4 py-3 text-base pr-12"
                                               placeholder="Ulangi password baru">
                                        <button type="button" 
                                                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                                                onclick="togglePassword(this)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-6 border-t border-gray-100 flex justify-end">
                            <button type="submit" class="btn-danger px-8 py-3">
                                <i class="fas fa-key mr-2"></i>Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column - Stats & Activities -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-6 text-white shadow-xl">
                <h3 class="font-heading font-bold text-xl mb-6">Statistik Singkat</h3>
                
                <div class="space-y-4">
                    <!-- Total Tickets -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-400 to-blue-500 flex items-center justify-center shadow-md">
                                    <i class="fas fa-ticket-alt text-white"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-100">Total Laporan</p>
                                    <p class="text-2xl font-bold">{{ $ticketStats['total'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white/5 rounded-xl p-3 border border-white/10">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-yellow-400 to-yellow-500 flex items-center justify-center">
                                    <i class="fas fa-clock text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-300">Pending</p>
                                    <p class="text-lg font-bold">{{ $ticketStats['pending'] }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white/5 rounded-xl p-3 border border-white/10">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-green-400 to-green-500 flex items-center justify-center">
                                    <i class="fas fa-check-circle text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-300">Selesai</p>
                                    <p class="text-lg font-bold">{{ $ticketStats['done'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Progress -->
                    <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-sm font-medium">Completion Rate</p>
                            <p class="text-lg font-bold">
                                @php
                                    $rate = $ticketStats['total'] > 0 ? 
                                        round(($ticketStats['done'] / $ticketStats['total']) * 100) : 0;
                                @endphp
                                {{ $rate }}%
                            </p>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-2">
                            <div class="bg-gradient-to-r from-green-400 to-emerald-500 h-2 rounded-full" 
                                 style="width: {{ $rate }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white rounded-2xl shadow-soft-xl border border-gray-100">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-heading font-bold text-xl text-gray-900">Aktivitas Terbaru</h3>
                        <a href="{{ route('karyawan.dashboard') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Lihat semua
                        </a>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach($recentTickets as $ticket)
                        <a href="{{ route('karyawan.tickets.show', $ticket->id) }}" 
                           class="block group">
                            <div class="flex items-center space-x-3 p-3 rounded-xl border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br 
                                        @if($ticket->status == 'done') from-green-100 to-green-200 text-green-600
                                        @elseif($ticket->status == 'progress') from-blue-100 to-blue-200 text-blue-600
                                        @else from-yellow-100 to-yellow-200 text-yellow-600 @endif
                                        flex items-center justify-center">
                                        <i class="fas fa-ticket-alt text-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate group-hover:text-blue-700">
                                        {{ $ticket->title }}
                                    </p>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="text-xs px-2 py-1 rounded-full 
                                            @if($ticket->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($ticket->status == 'progress') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800 @endif font-medium">
                                            {{ $ticket->getStatusText() }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $ticket->created_at->format('d M') }}</span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-600 transition-colors"></i>
                                </div>
                            </div>
                        </a>
                        @endforeach
                        
                        @if($recentTickets->isEmpty())
                        <div class="text-center py-8">
                            <div class="w-12 h-12 mx-auto bg-gray-100 rounded-xl flex items-center justify-center mb-3">
                                <i class="fas fa-inbox text-gray-400"></i>
                            </div>
                            <p class="text-gray-500 text-sm mb-2">Belum ada aktivitas</p>
                            <a href="{{ route('karyawan.tickets.create') }}" 
                               class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fas fa-plus mr-1"></i> Buat laporan
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Account Status -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl">
                <h3 class="font-heading font-bold text-xl mb-6">Status Akun</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-white/10 rounded-xl backdrop-blur-sm">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-user-check text-indigo-200"></i>
                            <span class="text-sm">Status Verifikasi</span>
                        </div>
                        <span class="px-3 py-1 bg-emerald-400/20 text-emerald-300 rounded-full text-xs font-medium border border-emerald-400/30">
                            Terverifikasi
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-white/10 rounded-xl backdrop-blur-sm">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-calendar-alt text-indigo-200"></i>
                            <span class="text-sm">Bergabung</span>
                        </div>
                        <span class="font-medium">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-white/10 rounded-xl backdrop-blur-sm">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-sign-in-alt text-indigo-200"></i>
                            <span class="text-sm">Login Terakhir</span>
                        </div>
                        <span class="font-medium">{{ $user->updated_at->format('H:i') }}</span>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-white/20">
                    <div class="text-center">
                        <p class="text-xs text-indigo-200 mb-2">Sesi Aktif</p>
                        <div class="inline-flex items-center space-x-2 px-4 py-2 bg-white/10 rounded-full backdrop-blur-sm">
                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                            <span class="text-sm font-medium">Aman & Terkoneksi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePassword(button) {
        const input = button.parentElement.querySelector('input');
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
            button.classList.add('text-blue-600');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
            button.classList.remove('text-blue-600');
        }
    }
</script>
@endpush

@push('styles')
<style>
    /* Base Styles */
    .shadow-soft-xl {
        box-shadow: 0 10px 40px -15px rgba(0, 0, 0, 0.05), 0 5px 20px -5px rgba(0, 0, 0, 0.02);
    }
    
    .input-modern {
        @apply border border-gray-300 rounded-xl px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-white;
    }
    
    .btn-primary {
        @apply bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium px-6 py-3 rounded-xl hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed;
    }
    
    .btn-danger {
        @apply bg-gradient-to-r from-red-500 to-red-600 text-white font-medium px-6 py-3 rounded-xl hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200 shadow-md hover:shadow-lg;
    }
    
    .btn-secondary {
        @apply bg-gray-100 text-gray-700 font-medium px-5 py-2.5 rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition duration-200;
    }
    
    /* Mobile Optimization */
    @media (max-width: 768px) {
        .grid-cols-1 {
            grid-template-columns: 1fr;
        }
        
        .md\:grid-cols-2 {
            grid-template-columns: 1fr;
        }
        
        .space-y-6 > * + * {
            margin-top: 1rem;
        }
        
        .p-6 {
            padding: 1rem;
        }
        
        .px-6 {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .py-3 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }
        
        .text-2xl {
            font-size: 1.5rem;
        }
        
        .text-xl {
            font-size: 1.25rem;
        }
        
        /* Compact form spacing */
        .space-y-5 > * + * {
            margin-top: 1rem;
        }
        
        .gap-5 {
            gap: 1rem;
        }
        
        /* Reduce header size on mobile */
        .w-20.h-20 {
            width: 4rem;
            height: 4rem;
        }
        
        .w-16.h-16 {
            width: 3.5rem;
            height: 3.5rem;
        }
        
        /* Make buttons full width on mobile */
        .justify-end {
            justify-content: stretch;
        }
        
        .justify-end button {
            width: 100%;
        }
        
        /* Reduce padding in cards */
        .rounded-2xl {
            border-radius: 1rem;
        }
    }
    
    /* Desktop Enhancement */
    @media (min-width: 1024px) {
        .lg\:grid-cols-3 {
            grid-template-columns: 2fr 1fr;
        }
        
        .lg\:col-span-2 {
            grid-column: span 2;
        }
        
        .lg\:p-8 {
            padding: 2rem;
        }
    }
    
    /* Animation for status indicator */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    /* Smooth transitions */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }
    
    /* Gradient text */
    .bg-clip-text {
        -webkit-background-clip: text;
        background-clip: text;
    }
</style>
@endpush