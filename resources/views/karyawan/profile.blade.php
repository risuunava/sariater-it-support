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
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Profile Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Profile Card -->
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
            <div class="px-6 py-5 bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                <div class="flex items-center space-x-3">
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center text-2xl font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="font-heading text-xl font-bold">{{ Auth::user()->name }}</h2>
                        <p class="text-blue-100">{{ Auth::user()->email }}</p>
                        <p class="text-blue-100 text-sm mt-1">Karyawan • Bergabung {{ $user->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <form action="{{ route('karyawan.profile.update') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap *
                                </label>
                                <input type="text" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}"
                                       required
                                       class="input-modern w-full">
                                @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email *
                                </label>
                                <input type="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}"
                                       required
                                       class="input-modern w-full">
                                @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Telepon
                                </label>
                                <input type="text" 
                                       name="phone" 
                                       value="{{ old('phone', $user->phone ?? '') }}"
                                       class="input-modern w-full"
                                       placeholder="0812-3456-7890">
                                @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Departemen
                                </label>
                                <input type="text" 
                                       name="department" 
                                       value="{{ old('department', $user->department ?? '') }}"
                                       class="input-modern w-full"
                                       placeholder="IT, HR, Marketing, dll">
                                @error('department')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="pt-6 border-t border-gray-200">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Change Password -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center">
                    <i class="fas fa-lock text-white"></i>
                </div>
                <div>
                    <h3 class="font-heading font-semibold text-gray-900">Ubah Password</h3>
                    <p class="text-sm text-gray-600">Perbarui password akun Anda</p>
                </div>
            </div>
            
            <form action="{{ route('karyawan.profile.password') }}" method="POST">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password Saat Ini *
                        </label>
                        <input type="password" 
                               name="current_password" 
                               required
                               class="input-modern w-full">
                        @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password Baru *
                            </label>
                            <input type="password" 
                                   name="new_password" 
                                   required
                                   class="input-modern w-full">
                            @error('new_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password Baru *
                            </label>
                            <input type="password" 
                                   name="new_password_confirmation" 
                                   required
                                   class="input-modern w-full">
                        </div>
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-key mr-2"></i>Ubah Password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Right Column - Stats & Info -->
    <div class="space-y-6">
        <!-- Stats Card -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <h3 class="font-heading font-bold text-gray-900 mb-6">Statistik Saya</h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                            <i class="fas fa-ticket-alt text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Total Laporan</p>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $ticketStats['total'] }}</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Pending</p>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $ticketStats['pending'] }}</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Selesai</p>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $ticketStats['done'] }}</span>
                </div>
                
                <div class="pt-4 border-t border-gray-200">
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Completion Rate</p>
                        <p class="text-2xl font-bold text-gray-900">
                            @php
                                $rate = $ticketStats['total'] > 0 ? 
                                    round(($ticketStats['done'] / $ticketStats['total']) * 100) : 0;
                            @endphp
                            {{ $rate }}%
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Tickets -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-heading font-bold text-gray-900">Laporan Terbaru</h3>
                <a href="{{ route('karyawan.dashboard') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    Lihat semua
                </a>
            </div>
            
            <div class="space-y-4">
                @foreach($recentTickets as $ticket)
                <div class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $ticket->title }}</p>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="text-xs px-2 py-1 rounded-full 
                                    @if($ticket->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($ticket->status == 'progress') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ $ticket->getStatusText() }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $ticket->created_at->format('d M') }}</span>
                            </div>
                        </div>
                        <a href="{{ route('karyawan.tickets.show', $ticket->id) }}" 
                           class="ml-2 text-blue-600 hover:text-blue-800">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($recentTickets->isEmpty())
            <div class="text-center py-4">
                <p class="text-gray-500 text-sm">Belum ada laporan</p>
                <a href="{{ route('karyawan.tickets.create') }}" class="text-blue-600 hover:text-blue-800 text-sm mt-2 inline-block">
                    Buat laporan pertama
                </a>
            </div>
            @endif
        </div>
        
        <!-- Account Info -->
        <div class="bg-gradient-to-br from-gray-900 to-blue-900 rounded-2xl shadow-lg p-6 text-white">
            <h3 class="font-heading font-bold text-lg mb-4">Informasi Akun</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-blue-200">Role</span>
                    <span class="font-medium">Karyawan</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-blue-200">Status Akun</span>
                    <span class="px-2 py-1 bg-green-500 text-white text-xs rounded-full">Aktif</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-blue-200">Bergabung</span>
                    <span class="font-medium">{{ $user->created_at->format('d M Y') }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-blue-200">Last Login</span>
                    <span class="font-medium">{{ $user->updated_at->format('d M Y H:i') }}</span>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-blue-800">
                <div class="text-center">
                    <p class="text-sm text-blue-300">Member ID</p>
                    <p class="font-mono font-bold">{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection