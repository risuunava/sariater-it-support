@extends('layouts.app')

@section('title', 'Dashboard Karyawan')
@section('page-title', 'Dashboard Saya')
@section('page-description', 'Kelola semua laporan dan masalah IT Anda')

@section('header-actions')
<div class="flex items-center space-x-2">
    <!-- Mobile Quick Actions -->
    <button id="mobile-quick-menu" 
            class="md:hidden w-12 h-12 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 text-white flex items-center justify-center shadow-lg hover:shadow-xl active:scale-95 transition-all"
            aria-label="Menu cepat">
        <i class="fas fa-bolt text-base"></i>
    </button>
    
    <!-- Desktop Buttons -->
    <div class="hidden md:flex items-center space-x-3">
        <a href="{{ route('karyawan.analytics') }}" 
           class="flex items-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-medium shadow hover:shadow-md transition-all hover:-translate-y-0.5">
            <i class="fas fa-chart-bar text-sm"></i>
            <span>Analytics</span>
        </a>
        
        <a href="{{ route('karyawan.tickets.create') }}" 
           class="flex items-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-medium shadow hover:shadow-md transition-all hover:-translate-y-0.5">
            <i class="fas fa-plus text-sm"></i>
            <span>Buat Laporan</span>
        </a>
        
        <!-- Desktop Search Bar -->
        <div class="relative">
            <form action="{{ route('karyawan.dashboard') }}" method="GET" id="desktop-search-form">
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           id="desktop-search-input"
                           class="w-64 px-4 py-2.5 pl-11 bg-white border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Cari laporan..."
                           autocomplete="off">
                    <i class="fas fa-search absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                    @if(request('search'))
                    <button type="button" 
                            onclick="clearSearch()"
                            class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('content')
<!-- Quick Actions Menu (Mobile) -->
<div id="quick-actions-menu" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/60" onclick="closeQuickMenu()"></div>
    <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl p-6 shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-xl text-gray-900">Quick Actions</h3>
            <button onclick="closeQuickMenu()" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Mobile Search -->
        <div class="mb-4">
            <form action="{{ route('karyawan.dashboard') }}" method="GET">
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           class="w-full p-4 pl-12 bg-gray-50 rounded-2xl text-base border-0 focus:ring-2 focus:ring-blue-500 focus:bg-white"
                           placeholder="Cari laporan...">
                    <i class="fas fa-search absolute left-4 top-4 text-gray-400"></i>
                    @if(request('search'))
                    <button type="button" 
                            onclick="clearSearch()"
                            class="absolute right-4 top-4 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                    @endif
                </div>
            </form>
        </div>
        
        <div class="grid grid-cols-2 gap-3 mb-6">
            <a href="{{ route('karyawan.tickets.create') }}" 
               class="p-4 bg-blue-600 rounded-2xl text-white flex flex-col items-center justify-center">
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center mb-2">
                    <i class="fas fa-plus text-lg"></i>
                </div>
                <span class="text-sm font-medium">Buat Laporan</span>
            </a>
            
            <a href="{{ route('karyawan.analytics') }}" 
               class="p-4 bg-green-600 rounded-2xl text-white flex flex-col items-center justify-center">
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center mb-2">
                    <i class="fas fa-chart-bar text-lg"></i>
                </div>
                <span class="text-sm font-medium">Analytics</span>
            </a>
            
            <a href="{{ route('karyawan.profile') }}"
               class="p-4 bg-purple-600 rounded-2xl text-white flex flex-col items-center justify-center">
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center mb-2">
                    <i class="fas fa-user text-lg"></i>
                </div>
                <span class="text-sm font-medium">Profil</span>
            </a>
            
            <button onclick="showMobileFilter()"
               class="p-4 bg-yellow-600 rounded-2xl text-white flex flex-col items-center justify-center">
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center mb-2">
                    <i class="fas fa-filter text-lg"></i>
                </div>
                <span class="text-sm font-medium">Filter</span>
            </button>
        </div>
    </div>
</div>

<!-- Mobile Filter Panel -->
<div id="mobileFilterPanel" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/60" onclick="closeMobileFilter()"></div>
    <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl p-6 shadow-lg max-h-[85vh] overflow-y-auto">
        <div class="flex items-center space-x-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center">
                <i class="fas fa-filter text-white"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-lg text-gray-900">Filter Laporan</h3>
                <p class="text-sm text-gray-500">Sesuaikan tampilan Anda</p>
            </div>
            <button onclick="closeMobileFilter()" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="{{ route('karyawan.dashboard') }}" method="GET" class="space-y-6">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Cari Laporan</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="w-full p-4 pl-12 bg-gray-50 rounded-2xl text-base border-0 focus:ring-2 focus:ring-blue-500 focus:bg-white"
                           placeholder="Ketik judul atau deskripsi...">
                    <i class="fas fa-search absolute left-4 top-4 text-gray-400"></i>
                </div>
            </div>
            
            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Status Laporan</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="status" value="all" class="sr-only peer" 
                               {{ !request('status') || request('status') == 'all' ? 'checked' : '' }}>
                        <div class="p-4 text-center border-2 border-gray-200 rounded-2xl peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition-colors">
                            <i class="fas fa-layer-group text-lg mb-1"></i>
                            <div class="text-sm font-medium">Semua</div>
                        </div>
                    </label>
                    
                    <label class="relative cursor-pointer">
                        <input type="radio" name="status" value="pending" class="sr-only peer"
                               {{ request('status') == 'pending' ? 'checked' : '' }}>
                        <div class="p-4 text-center border-2 border-gray-200 rounded-2xl peer-checked:border-yellow-500 peer-checked:bg-yellow-50 peer-checked:text-yellow-700 transition-colors">
                            <i class="fas fa-clock text-lg mb-1"></i>
                            <div class="text-sm font-medium">Pending</div>
                        </div>
                    </label>
                    
                    <label class="relative cursor-pointer">
                        <input type="radio" name="status" value="progress" class="sr-only peer"
                               {{ request('status') == 'progress' ? 'checked' : '' }}>
                        <div class="p-4 text-center border-2 border-gray-200 rounded-2xl peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition-colors">
                            <i class="fas fa-cogs text-lg mb-1"></i>
                            <div class="text-sm font-medium">Progress</div>
                        </div>
                    </label>
                    
                    <label class="relative cursor-pointer">
                        <input type="radio" name="status" value="done" class="sr-only peer"
                               {{ request('status') == 'done' ? 'checked' : '' }}>
                        <div class="p-4 text-center border-2 border-gray-200 rounded-2xl peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-700 transition-colors">
                            <i class="fas fa-check-circle text-lg mb-1"></i>
                            <div class="text-sm font-medium">Selesai</div>
                        </div>
                    </label>
                </div>
            </div>
            
            <!-- Date Range -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Dari Tanggal</label>
                    <input type="date" 
                           name="date_from" 
                           value="{{ request('date_from') }}"
                           class="w-full p-3 bg-gray-50 rounded-xl text-base border-0 focus:ring-2 focus:ring-blue-500 focus:bg-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Sampai Tanggal</label>
                    <input type="date" 
                           name="date_to" 
                           value="{{ request('date_to') }}"
                           class="w-full p-3 bg-gray-50 rounded-xl text-base border-0 focus:ring-2 focus:ring-blue-500 focus:bg-white">
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex space-x-3 pt-2">
                <a href="{{ route('karyawan.dashboard') }}" 
                   class="flex-1 py-4 px-6 bg-gray-100 text-gray-700 font-semibold rounded-2xl text-center">
                    Reset
                </a>
                <button type="submit" 
                        class="flex-1 py-4 px-6 bg-blue-600 text-white font-semibold rounded-2xl shadow">
                    Terapkan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Main Content -->
<div>
    <!-- MOBILE VIEW (md:hidden) -->
    <div class="md:hidden space-y-4">
        <!-- Welcome Card - Mobile -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-3xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-xl font-bold">Halo, {{ Str::limit(auth()->user()->name, 15) }}</h1>
                    <p class="text-blue-100 text-sm">Kelola laporan IT Anda</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-headset text-lg"></i>
                </div>
            </div>
            
            <!-- Quick Stats - Mobile -->
            <div class="grid grid-cols-4 gap-2 mb-4">
                <div class="bg-white/10 rounded-xl p-3 text-center">
                    <p class="text-lg font-bold">{{ $statistics['total'] ?? 0 }}</p>
                    <p class="text-xs mt-1">Total</p>
                </div>
                
                <div class="bg-white/10 rounded-xl p-3 text-center">
                    <p class="text-lg font-bold">{{ $statistics['pending'] ?? 0 }}</p>
                    <p class="text-xs mt-1">Pending</p>
                </div>
                
                <div class="bg-white/10 rounded-xl p-3 text-center">
                    <p class="text-lg font-bold">{{ $statistics['progress'] ?? 0 }}</p>
                    <p class="text-xs mt-1">Progress</p>
                </div>
                
                <div class="bg-white/10 rounded-xl p-3 text-center">
                    <p class="text-lg font-bold">{{ $statistics['done'] ?? 0 }}</p>
                    <p class="text-xs mt-1">Selesai</p>
                </div>
            </div>
            
            <!-- Quick Actions - Mobile -->
            <div class="flex justify-between">
                <button onclick="openMobileSearch()" 
                       class="flex-1 bg-white/20 hover:bg-white/30 rounded-xl p-3 text-center transition mx-1 active:scale-95">
                    <i class="fas fa-search text-sm mb-1 block"></i>
                    <span class="text-xs">Cari</span>
                </button>
                
                <a href="{{ route('karyawan.tickets.create') }}" 
                   class="flex-1 bg-white/20 hover:bg-white/30 rounded-xl p-3 text-center transition mx-1 active:scale-95">
                    <i class="fas fa-plus text-sm mb-1 block"></i>
                    <span class="text-xs">Buat</span>
                </a>
                
                <a href="{{ route('karyawan.analytics') }}" 
                   class="flex-1 bg-white/20 hover:bg-white/30 rounded-xl p-3 text-center transition mx-1 active:scale-95">
                    <i class="fas fa-chart-bar text-sm mb-1 block"></i>
                    <span class="text-xs">Analytics</span>
                </a>
                
                <button onclick="showMobileFilter()"
                   class="flex-1 bg-white/20 hover:bg-white/30 rounded-xl p-3 text-center transition mx-1 active:scale-95">
                    <i class="fas fa-filter text-sm mb-1 block"></i>
                    <span class="text-xs">Filter</span>
                </button>
            </div>
        </div>

        <!-- Active Filters Badges - Mobile -->
        @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']) && !(request('status') == 'all' || !request('status')))
        <div class="px-2">
            <div class="bg-white rounded-xl p-3 shadow">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Filter Aktif:</span>
                    <a href="{{ route('karyawan.dashboard') }}" class="text-xs text-blue-600 hover:text-blue-800">
                        Hapus Semua
                    </a>
                </div>
                <div class="flex flex-wrap gap-2">
                    @if(request('search'))
                    <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                        Cari: "{{ request('search') }}"
                        <a href="{{ route('karyawan.dashboard', array_merge(request()->except('search'), ['status' => request('status')])) }}" 
                           class="ml-1.5 text-blue-600 hover:text-blue-800">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif
                    
                    @if(request('status') && request('status') != 'all')
                    <span class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                        Status: {{ ucfirst(request('status')) }}
                        <a href="{{ route('karyawan.dashboard', array_merge(request()->except('status'), ['search' => request('search')])) }}" 
                           class="ml-1.5 text-yellow-600 hover:text-yellow-800">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Access Cards - Horizontal Scroll Mobile -->
        <div class="overflow-x-auto pb-2 -mx-4 px-4">
            <div class="flex space-x-3 min-w-max">
                <!-- Analytics Card -->
                <a href="{{ route('karyawan.analytics') }}" 
                   class="w-40 flex-shrink-0 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl p-4 text-white shadow hover-lift">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center mr-2">
                            <i class="fas fa-chart-line text-sm"></i>
                        </div>
                        <span class="text-xs font-medium">Analytics</span>
                    </div>
                    <h3 class="text-sm font-bold mb-1">Statistik</h3>
                    <p class="text-green-100 text-xs">Lihat performa laporan</p>
                    <div class="mt-3 text-xs flex items-center">
                        <span>Detail</span>
                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </div>
                </a>
                
                <!-- Profile Card -->
                <a href="{{ route('karyawan.profile') }}" 
                   class="w-40 flex-shrink-0 bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl p-4 text-white shadow hover-lift">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center mr-2">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <span class="text-xs font-medium">Profil</span>
                    </div>
                    <h3 class="text-sm font-bold mb-1">Akun Saya</h3>
                    <p class="text-purple-100 text-xs">Kelola data akun</p>
                    <div class="mt-3 text-xs flex items-center">
                        <span>Edit</span>
                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </div>
                </a>
                
                <!-- Help Card -->
                <a href="{{ route('karyawan.help') }}" 
                   class="w-40 flex-shrink-0 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-2xl p-4 text-white shadow hover-lift">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center mr-2">
                            <i class="fas fa-question-circle text-sm"></i>
                        </div>
                        <span class="text-xs font-medium">Bantuan</span>
                    </div>
                    <h3 class="text-sm font-bold mb-1">Panduan</h3>
                    <p class="text-yellow-100 text-xs">FAQ & Support</p>
                    <div class="mt-3 text-xs flex items-center">
                        <span>Buka</span>
                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </div>
                </a>
            </div>
        </div>

        <!-- Tickets Section - Mobile -->
        <div class="bg-white rounded-2xl shadow">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-gray-900">Laporan Terbaru</h2>
                    <p class="text-xs text-gray-500">{{ $tickets->count() }} laporan ditemukan</p>
                </div>
                <div class="flex items-center space-x-2">
                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                    <a href="{{ route('karyawan.dashboard') }}" class="text-xs text-blue-600 font-medium">
                        Reset
                    </a>
                    @endif
                </div>
            </div>
            
            <!-- Tickets List - Mobile -->
            @if($tickets->isEmpty())
            <!-- Empty State - Mobile -->
            <div class="text-center py-8 px-4">
                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-inbox text-gray-400"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 mb-2">Belum ada laporan</h3>
                <p class="text-gray-500 text-xs mb-4">Buat laporan pertama Anda</p>
                <a href="{{ route('karyawan.tickets.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded-xl font-medium active:scale-95">
                    <i class="fas fa-plus mr-2 text-xs"></i> Buat Laporan
                </a>
            </div>
            @else
            <!-- Mobile Tickets List -->
            <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
                @foreach($tickets->take(6) as $ticket)
                <a href="{{ route('karyawan.tickets.show', $ticket->id) }}" 
                   class="block p-3 hover:bg-gray-50 active:bg-gray-100">
                    <div class="flex items-center space-x-3">
                        <!-- Status Dot -->
                        @php
                            $statusColor = match($ticket->status) {
                                'pending' => 'bg-yellow-500',
                                'progress' => 'bg-blue-500',
                                'done' => 'bg-green-500',
                                default => 'bg-gray-500'
                            };
                            $statusIcon = match($ticket->status) {
                                'pending' => 'clock',
                                'progress' => 'cogs',
                                'done' => 'check',
                                default => 'circle'
                            };
                        @endphp
                        <div class="relative">
                            <div class="w-10 h-10 rounded-lg {{ $statusColor }} flex items-center justify-center">
                                <i class="fas fa-{{ $statusIcon }} text-white text-xs"></i>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="text-sm font-medium text-gray-900 truncate">
                                    {{ Str::limit($ticket->title, 25) }}
                                </h3>
                                <span class="text-xs text-gray-500 ml-2 flex-shrink-0">
                                    {{ $ticket->created_at->format('d M') }}
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">
                                    #{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}
                                </span>
                                @php
                                    $statusBadgeColor = match($ticket->status) {
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'progress' => 'bg-blue-100 text-blue-800',
                                        'done' => 'bg-green-100 text-green-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $statusBadgeColor }}">
                                    {{ $ticket->getStatusText() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            
            <!-- View More Link - Mobile -->
            @if($tickets->count() > 6)
            <div class="px-4 py-3 border-t border-gray-100 text-center">
                <a href="{{ route('karyawan.dashboard') }}" class="text-sm text-blue-600 font-medium">
                    Lihat {{ $tickets->count() - 6 }} laporan lainnya →
                </a>
            </div>
            @endif
            @endif
        </div>
    </div>

    <!-- DESKTOP VIEW (hidden md:block) -->
    <div class="hidden md:block space-y-6">
        <!-- Welcome Section - Desktop -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-3xl p-8 text-white shadow-xl">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-3xl font-bold mb-3">Halo, {{ auth()->user()->name }}! 👋</h1>
                    <p class="text-blue-100 text-lg">Selamat datang di dashboard IT Support</p>
                    <p class="text-blue-200 mt-2">Kelola semua laporan dan masalah IT Anda dengan mudah</p>
                </div>
                <div class="w-20 h-20 rounded-2xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-headset text-2xl"></i>
                </div>
            </div>
            
            <!-- Quick Stats Grid - Desktop -->
            <div class="grid grid-cols-4 gap-4 mb-6">
                <div class="bg-white/10 rounded-2xl p-5 backdrop-blur-sm hover:bg-white/15 transition">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium">Total Laporan</span>
                        <i class="fas fa-ticket-alt text-sm"></i>
                    </div>
                    <p class="text-3xl font-bold">{{ $statistics['total'] ?? 0 }}</p>
                    <p class="text-xs text-blue-200 mt-2">Semua laporan Anda</p>
                </div>
                
                <div class="bg-white/10 rounded-2xl p-5 backdrop-blur-sm hover:bg-white/15 transition">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium">Pending</span>
                        <i class="fas fa-clock text-sm"></i>
                    </div>
                    <p class="text-3xl font-bold">{{ $statistics['pending'] ?? 0 }}</p>
                    <p class="text-xs text-blue-200 mt-2">Menunggu tindakan</p>
                </div>
                
                <div class="bg-white/10 rounded-2xl p-5 backdrop-blur-sm hover:bg-white/15 transition">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium">Progress</span>
                        <i class="fas fa-cogs text-sm"></i>
                    </div>
                    <p class="text-3xl font-bold">{{ $statistics['progress'] ?? 0 }}</p>
                    <p class="text-xs text-blue-200 mt-2">Sedang diproses</p>
                </div>
                
                <div class="bg-white/10 rounded-2xl p-5 backdrop-blur-sm hover:bg-white/15 transition">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium">Selesai</span>
                        <i class="fas fa-check-circle text-sm"></i>
                    </div>
                    <p class="text-3xl font-bold">{{ $statistics['done'] ?? 0 }}</p>
                    <p class="text-xs text-blue-200 mt-2">Terselesaikan</p>
                </div>
            </div>
            
            <!-- Active Filters Badges - Desktop -->
            @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']) && !(request('status') == 'all' || !request('status')))
            <div class="mt-4 p-4 bg-white/10 rounded-2xl backdrop-blur-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-white">Filter Aktif:</span>
                    <a href="{{ route('karyawan.dashboard') }}" class="text-xs text-blue-200 hover:text-white">
                        Hapus Semua
                    </a>
                </div>
                <div class="flex flex-wrap gap-2">
                    @if(request('search'))
                    <span class="inline-flex items-center px-3 py-1.5 bg-white/20 text-white rounded-full text-xs">
                        <i class="fas fa-search mr-1.5 text-xs"></i>
                        "{{ request('search') }}"
                        <a href="{{ route('karyawan.dashboard', array_merge(request()->except('search'), ['status' => request('status')])) }}" 
                           class="ml-2 hover:text-blue-200">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif
                    
                    @if(request('status') && request('status') != 'all')
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-500/20 text-yellow-200',
                            'progress' => 'bg-blue-500/20 text-blue-200',
                            'done' => 'bg-green-500/20 text-green-200'
                        ];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1.5 {{ $statusColors[request('status')] ?? 'bg-white/20 text-white' }} rounded-full text-xs">
                        <i class="fas fa-filter mr-1.5 text-xs"></i>
                        Status: {{ ucfirst(request('status')) }}
                        <a href="{{ route('karyawan.dashboard', array_merge(request()->except('status'), ['search' => request('search')])) }}" 
                           class="ml-2 hover:opacity-75">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif
                    
                    @if(request('date_from') && request('date_to'))
                    <span class="inline-flex items-center px-3 py-1.5 bg-white/20 text-white rounded-full text-xs">
                        <i class="fas fa-calendar mr-1.5 text-xs"></i>
                        {{ request('date_from') }} - {{ request('date_to') }}
                        <a href="{{ route('karyawan.dashboard', array_merge(request()->except(['date_from', 'date_to']))) }}" 
                           class="ml-2 hover:text-blue-200">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Quick Access Cards - Desktop -->
        <div class="grid grid-cols-3 gap-6">
            <!-- Analytics Card - Desktop -->
            <a href="{{ route('karyawan.analytics') }}" 
               class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-3xl p-6 text-white shadow-xl hover-lift">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <span class="text-sm font-medium bg-white/20 px-4 py-1.5 rounded-full">Analytics</span>
                </div>
                <h3 class="text-xl font-bold mb-3">Statistik Lengkap</h3>
                <p class="text-green-100 mb-4">Lihat analisis mendalam tentang performa, tren, dan waktu penyelesaian laporan Anda</p>
                <div class="flex items-center text-sm font-medium">
                    <span>Lihat Detail Analytics</span>
                    <i class="fas fa-arrow-right ml-3"></i>
                </div>
            </a>
            
            <!-- Profile Card - Desktop -->
            <a href="{{ route('karyawan.profile') }}" 
               class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-3xl p-6 text-white shadow-xl hover-lift">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center">
                        <i class="fas fa-user text-xl"></i>
                    </div>
                    <span class="text-sm font-medium bg-white/20 px-4 py-1.5 rounded-full">Profil</span>
                </div>
                <h3 class="text-xl font-bold mb-3">Kelola Akun</h3>
                <p class="text-purple-100 mb-4">Update informasi pribadi, ubah password, dan lihat riwayat aktivitas akun Anda</p>
                <div class="flex items-center text-sm font-medium">
                    <span>Edit Profil</span>
                    <i class="fas fa-arrow-right ml-3"></i>
                </div>
            </a>
            
            <!-- Help Card - Desktop -->
            <a href="{{ route('karyawan.help') }}" 
               class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-3xl p-6 text-white shadow-xl hover-lift">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center">
                        <i class="fas fa-question-circle text-xl"></i>
                    </div>
                    <span class="text-sm font-medium bg-white/20 px-4 py-1.5 rounded-full">Bantuan</span>
                </div>
                <h3 class="text-xl font-bold mb-3">Panduan & Dukungan</h3>
                <p class="text-yellow-100 mb-4">Akses FAQ lengkap, panduan penggunaan, dan hubungi tim support untuk bantuan teknis</p>
                <div class="flex items-center text-sm font-medium">
                    <span>Lihat Panduan</span>
                    <i class="fas fa-arrow-right ml-3"></i>
                </div>
            </a>
        </div>

        <!-- Tickets Section - Desktop -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Laporan Terkini</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $tickets->count() }} laporan ditemukan
                        @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                        <span class="text-blue-600">(difilter)</span>
                        @endif
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Filter Button Container with Dropdown -->
                    <div class="relative" id="ticket-filter-container">
                        <button id="desktop-filter-btn-header" 
                                class="px-4 py-2 bg-white border border-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors flex items-center">
                            <i class="fas fa-filter mr-2"></i>Filter
                            @if(request()->hasAny(['status', 'priority', 'category', 'date_from', 'date_to']) && !(request('status') == 'all' || !request('status')))
                            <span class="ml-1 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                            @endif
                        </button>
                        
                        <!-- Filter Dropdown for Desktop (Click to Open) - PLACED INSIDE TICKET SECTION -->
                        <div id="desktop-filter-dropdown" 
                             class="absolute right-0 mt-2 w-96 bg-white rounded-xl shadow-xl border border-gray-200 opacity-0 invisible transition-all duration-300 z-[9999] transform -translate-y-2"
                             style="will-change: transform, opacity;">
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-filter text-blue-600"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">Filter Laporan</h3>
                                            <p class="text-xs text-gray-500">Sesuaikan tampilan Anda</p>
                                        </div>
                                    </div>
                                    <button type="button" 
                                            onclick="closeDesktopFilter()"
                                            class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-times text-gray-500"></i>
                                    </button>
                                </div>
                                
                                <form action="{{ route('karyawan.dashboard') }}" method="GET" id="desktop-filter-form" class="space-y-4">
                                    <!-- Search inside filter -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari Laporan</label>
                                        <div class="relative">
                                            <input type="text" 
                                                   name="search" 
                                                   value="{{ request('search') }}"
                                                   class="w-full p-3 pl-10 bg-gray-50 rounded-lg text-sm border-0 focus:ring-2 focus:ring-blue-500 focus:bg-white"
                                                   placeholder="Ketik judul atau deskripsi...">
                                            <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-sm"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Status Filter -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                        <div class="grid grid-cols-3 gap-2">
                                            <label class="relative cursor-pointer">
                                                <input type="radio" name="status" value="all" class="sr-only peer" 
                                                       {{ !request('status') || request('status') == 'all' ? 'checked' : '' }}>
                                                <div class="p-2 text-center border border-gray-200 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 text-xs transition-colors">
                                                    Semua
                                                </div>
                                            </label>
                                            
                                            <label class="relative cursor-pointer">
                                                <input type="radio" name="status" value="pending" class="sr-only peer"
                                                       {{ request('status') == 'pending' ? 'checked' : '' }}>
                                                <div class="p-2 text-center border border-gray-200 rounded-lg peer-checked:border-yellow-500 peer-checked:bg-yellow-50 peer-checked:text-yellow-700 text-xs transition-colors">
                                                    Pending
                                                </div>
                                            </label>
                                            
                                            <label class="relative cursor-pointer">
                                                <input type="radio" name="status" value="progress" class="sr-only peer"
                                                       {{ request('status') == 'progress' ? 'checked' : '' }}>
                                                <div class="p-2 text-center border border-gray-200 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 text-xs transition-colors">
                                                    Progress
                                                </div>
                                            </label>
                                            
                                            <label class="relative cursor-pointer">
                                                <input type="radio" name="status" value="done" class="sr-only peer"
                                                       {{ request('status') == 'done' ? 'checked' : '' }}>
                                                <div class="p-2 text-center border border-gray-200 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-700 text-xs transition-colors">
                                                    Selesai
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <!-- Date Range Filter -->
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                                            <input type="date" 
                                                   name="date_from" 
                                                   value="{{ request('date_from') }}"
                                                   class="w-full p-2.5 bg-gray-50 rounded-lg text-sm border-0 focus:ring-2 focus:ring-blue-500 focus:bg-white">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                                            <input type="date" 
                                                   name="date_to" 
                                                   value="{{ request('date_to') }}"
                                                   class="w-full p-2.5 bg-gray-50 rounded-lg text-sm border-0 focus:ring-2 focus:ring-blue-500 focus:bg-white">
                                        </div>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="flex space-x-2 pt-2">
                                        <a href="{{ route('karyawan.dashboard') }}" 
                                           class="flex-1 py-2.5 px-4 bg-gray-100 text-gray-700 font-medium rounded-lg text-center text-sm hover:bg-gray-200 transition-colors">
                                            Reset Semua
                                        </a>
                                        <button type="submit" 
                                                class="flex-1 py-2.5 px-4 bg-blue-600 text-white font-medium rounded-lg text-sm shadow hover:bg-blue-700 transition-colors">
                                            Terapkan Filter
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('karyawan.tickets.create') }}" 
                       class="px-4 py-2 bg-blue-600 text-white font-medium rounded-xl shadow hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Buat Baru
                    </a>
                </div>
            </div>
            
            <!-- Active Filters Badges - Desktop -->
            @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']) && !(request('status') == 'all' || !request('status')))
            <div class="px-6 py-3 bg-blue-50 border-b border-blue-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-filter text-blue-500 text-sm"></i>
                        <span class="text-sm font-medium text-blue-700">Filter Aktif:</span>
                    </div>
                    <a href="{{ route('karyawan.dashboard') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                        Hapus Semua
                    </a>
                </div>
                <div class="flex flex-wrap gap-2 mt-2">
                    @if(request('search'))
                    <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                        <i class="fas fa-search mr-1.5 text-xs"></i>
                        "{{ request('search') }}"
                        <a href="{{ route('karyawan.dashboard', array_merge(request()->except('search'), ['status' => request('status')])) }}" 
                           class="ml-1.5 text-blue-600 hover:text-blue-800">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif
                    
                    @if(request('status') && request('status') != 'all')
                    @php
                        $statusBadgeColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'progress' => 'bg-blue-100 text-blue-800',
                            'done' => 'bg-green-100 text-green-800'
                        ];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 {{ $statusBadgeColors[request('status')] ?? 'bg-gray-100 text-gray-800' }} rounded-full text-xs">
                        <i class="fas fa-circle mr-1.5 text-xs"></i>
                        Status: {{ ucfirst(request('status')) }}
                        <a href="{{ route('karyawan.dashboard', array_merge(request()->except('status'), ['search' => request('search')])) }}" 
                           class="ml-1.5 hover:opacity-75">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif
                    
                    @if(request('date_from') && request('date_to'))
                    <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">
                        <i class="fas fa-calendar mr-1.5 text-xs"></i>
                        {{ \Carbon\Carbon::parse(request('date_from'))->format('d M') }} - {{ \Carbon\Carbon::parse(request('date_to'))->format('d M Y') }}
                        <a href="{{ route('karyawan.dashboard', array_merge(request()->except(['date_from', 'date_to']))) }}" 
                           class="ml-1.5 text-purple-600 hover:text-purple-800">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif
                </div>
            </div>
            @endif
            
            <!-- Tickets List - Desktop -->
            @if($tickets->isEmpty())
            <!-- Empty State - Desktop -->
            <div class="text-center py-16 px-4">
                <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-3">
                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                    Tidak ada laporan yang sesuai dengan filter
                    @else
                    Belum ada laporan
                    @endif
                </h3>
                <p class="text-gray-500 text-lg mb-8">
                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                    Coba ubah filter atau hapus filter untuk melihat semua laporan
                    @else
                    Mulai dengan membuat laporan pertama Anda
                    @endif
                </p>
                <div class="flex justify-center space-x-4">
                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                    <a href="{{ route('karyawan.dashboard') }}" 
                       class="px-8 py-3 bg-blue-600 text-white rounded-xl font-semibold shadow hover:bg-blue-700">
                        <i class="fas fa-times mr-2"></i> Hapus Filter
                    </a>
                    @endif
                    <a href="{{ route('karyawan.tickets.create') }}" 
                       class="px-8 py-3 bg-blue-600 text-white rounded-xl font-semibold shadow hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i> Buat Laporan
                    </a>
                </div>
            </div>
            @else
            <!-- Desktop Tickets Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 uppercase">ID</th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Judul</th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Status</th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Tanggal</th>
                            <th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($tickets->take(8) as $ticket)
                        @php
                            $statusColor = match($ticket->status) {
                                'pending' => 'bg-yellow-500',
                                'progress' => 'bg-blue-500',
                                'done' => 'bg-green-500',
                                default => 'bg-gray-500'
                            };
                            $statusBadgeColor = match($ticket->status) {
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'progress' => 'bg-blue-100 text-blue-800',
                                'done' => 'bg-green-100 text-green-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-8 py-4 whitespace-nowrap">
                                <div class="text-sm font-mono font-semibold text-gray-900">
                                    #{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}
                                </div>
                            </td>
                            <td class="px-8 py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 rounded-xl {{ $statusColor }} flex items-center justify-center">
                                            <i class="fas fa-ticket-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-base font-semibold text-gray-900">{{ Str::limit($ticket->title, 50) }}</div>
                                        <div class="text-sm text-gray-500 mt-1">{{ Str::limit($ticket->description, 60) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $statusBadgeColor }}">
                                    <i class="fas fa-circle text-xs mr-2"></i>
                                    {{ $ticket->getStatusText() }}
                                </span>
                            </td>
                            <td class="px-8 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $ticket->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $ticket->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-8 py-4 whitespace-nowrap">
                                <a href="{{ route('karyawan.tickets.show', $ticket->id) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 rounded-lg font-medium hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-eye mr-2"></i>Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- View All Link - Desktop -->
            @if($tickets->count() > 8)
            <div class="px-8 py-6 border-t border-gray-100 text-center">
                <a href="{{ route('karyawan.dashboard') }}" 
                   class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-800">
                    <span>Lihat semua {{ $tickets->count() }} laporan</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            @endif
            @endif
        </div>

        <!-- Performance Overview - Desktop -->
        <div class="grid grid-cols-3 gap-6">
            <!-- Completion Rate -->
            <div class="bg-white rounded-3xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-gray-900">Tingkat Penyelesaian</h3>
                    <span class="text-sm font-medium px-3 py-1 bg-green-100 text-green-800 rounded-full">
                        @php
                            $completionRate = $statistics['total'] > 0 
                                ? round(($statistics['done'] / $statistics['total']) * 100) 
                                : 0;
                        @endphp
                        {{ $completionRate }}%
                    </span>
                </div>
                <div class="mb-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-full rounded-full bg-gradient-to-r from-green-400 to-emerald-500" 
                             style="width: {{ $completionRate }}%"></div>
                    </div>
                </div>
                <p class="text-sm text-gray-500">Persentase laporan yang telah diselesaikan</p>
            </div>
            
            <!-- Status Distribution -->
            <div class="bg-white rounded-3xl p-6 shadow-lg">
                <h3 class="font-bold text-gray-900 mb-4">Distribusi Status</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-yellow-100 flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                            <span class="text-sm font-medium">Pending</span>
                        </div>
                        <span class="font-bold text-gray-900">{{ $statistics['pending'] ?? 0 }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-cogs text-blue-600"></i>
                            </div>
                            <span class="text-sm font-medium">Progress</span>
                        </div>
                        <span class="font-bold text-gray-900">{{ $statistics['progress'] ?? 0 }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                            <span class="text-sm font-medium">Selesai</span>
                        </div>
                        <span class="font-bold text-gray-900">{{ $statistics['done'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl p-6 shadow-lg">
                <h3 class="font-bold text-gray-900 mb-4">Quick Links</h3>
                <div class="space-y-3">
                    <a href="{{ route('karyawan.analytics') }}" 
                       class="flex items-center justify-between p-3 bg-white rounded-xl hover:shadow transition">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-chart-bar text-blue-600"></i>
                            </div>
                            <span class="font-medium">Analytics</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>
                    
                    <a href="{{ route('karyawan.profile') }}" 
                       class="flex items-center justify-between p-3 bg-white rounded-xl hover:shadow transition">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                <i class="fas fa-user text-purple-600"></i>
                            </div>
                            <span class="font-medium">Profil Saya</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>
                    
                    <a href="{{ route('karyawan.help') }}" 
                       class="flex items-center justify-between p-3 bg-white rounded-xl hover:shadow transition">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                                <i class="fas fa-question-circle text-yellow-600"></i>
                            </div>
                            <span class="font-medium">Bantuan</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Mobile Optimizations */
@media (max-width: 768px) {
    button, 
    a.button-like, 
    input, 
    select, 
    label.clickable {
        min-height: 44px;
        min-width: 44px;
    }
    
    input, 
    select, 
    textarea {
        font-size: 16px;
    }
    
    /* Smooth scrolling for horizontal cards */
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    
    .overflow-x-auto::-webkit-scrollbar {
        display: none;
    }
    
    /* Better touch feedback */
    .active\:scale-95:active {
        transform: scale(0.95);
    }
    
    .active\:bg-gray-100:active {
        background-color: #f3f4f6;
    }
}

/* Desktop Optimizations */
@media (min-width: 768px) {
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    /* Smooth dropdown animation */
    .dropdown-enter {
        opacity: 0;
        transform: translateY(-10px);
    }
    
    .dropdown-enter-active {
        opacity: 1;
        transform: translateY(0);
        transition: opacity 300ms, transform 300ms;
    }
    
    .dropdown-exit {
        opacity: 1;
        transform: translateY(0);
    }
    
    .dropdown-exit-active {
        opacity: 0;
        transform: translateY(-10px);
        transition: opacity 300ms, transform 300ms;
    }
}

/* Animations */
@keyframes slideUp {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.slide-up {
    animation: slideUp 0.3s ease-out;
}

.fade-in {
    animation: fadeIn 0.2s ease-out;
}

.slide-down {
    animation: slideDown 0.3s ease-out;
}

/* Status Colors */
.status-badge-pending {
    background-color: #fef3c7;
    color: #92400e;
}

.status-badge-progress {
    background-color: #dbeafe;
    color: #1e40af;
}

.status-badge-done {
    background-color: #d1fae5;
    color: #065f46;
}

/* Smooth transitions */
.transition {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.transition-all {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.transition-colors {
    transition: background-color 0.2s, color 0.2s, border-color 0.2s;
}

/* Card hover effects for desktop */
@media (min-width: 768px) {
    .card-hover {
        transition: all 0.3s ease;
    }
    
    .card-hover:hover {
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    }
}

/* Better focus states */
*:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Glass effect */
.backdrop-blur-sm {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

/* Dropdown shadow */
.dropdown-shadow {
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
}

/* FIXED: Ensure dropdown appears above content */
#desktop-filter-dropdown {
    z-index: 9999 !important;
    position: absolute !important;
}

/* Ensure main content is below dropdown */
#ticket-filter-container {
    position: relative !important;
    z-index: 9999 !important;
}
</style>

<script>
// Desktop Filter Dropdown - NOW INSIDE TICKET SECTION
const desktopFilterBtnHeader = document.getElementById('desktop-filter-btn-header');
const desktopFilterDropdown = document.getElementById('desktop-filter-dropdown');
const ticketFilterContainer = document.getElementById('ticket-filter-container');
let isFilterOpen = false;

// Function to toggle filter dropdown
function toggleDesktopFilter() {
    if (isFilterOpen) {
        closeDesktopFilter();
    } else {
        openDesktopFilter();
    }
}

function openDesktopFilter() {
    desktopFilterDropdown.classList.remove('opacity-0', 'invisible', '-translate-y-2');
    desktopFilterDropdown.classList.add('opacity-100', 'visible', 'translate-y-0', 'slide-down');
    isFilterOpen = true;
    
    // Close other dropdowns
    closeQuickMenu();
    
    // Ensure dropdown is on top
    desktopFilterDropdown.style.zIndex = '9999';
    desktopFilterDropdown.style.position = 'absolute';
}

function closeDesktopFilter() {
    desktopFilterDropdown.classList.remove('opacity-100', 'visible', 'translate-y-0');
    desktopFilterDropdown.classList.add('opacity-0', 'invisible', '-translate-y-2');
    isFilterOpen = false;
}

// Add event listener for filter button in ticket section
if (desktopFilterBtnHeader) {
    desktopFilterBtnHeader.addEventListener('click', function(e) {
        e.stopPropagation();
        toggleDesktopFilter();
    });
}

// Close filter dropdown when clicking outside
document.addEventListener('click', function(event) {
    if (isFilterOpen && ticketFilterContainer && !ticketFilterContainer.contains(event.target)) {
        closeDesktopFilter();
    }
});

// Mobile Quick Menu
const mobileQuickMenu = document.getElementById('mobile-quick-menu');
const quickActionsMenu = document.getElementById('quick-actions-menu');

if (mobileQuickMenu) {
    mobileQuickMenu.addEventListener('click', function() {
        quickActionsMenu.classList.remove('hidden');
        quickActionsMenu.classList.add('slide-up');
        document.body.style.overflow = 'hidden';
    });
}

function closeQuickMenu() {
    quickActionsMenu.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Mobile Filter
function showMobileFilter() {
    closeQuickMenu();
    setTimeout(() => {
        const panel = document.getElementById('mobileFilterPanel');
        panel.classList.remove('hidden');
        panel.classList.add('slide-up');
        document.body.style.overflow = 'hidden';
    }, 300);
}

function closeMobileFilter() {
    const panel = document.getElementById('mobileFilterPanel');
    panel.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Mobile Search
function openMobileSearch() {
    closeQuickMenu();
    const searchInput = document.querySelector('#quick-actions-menu input[name="search"]');
    setTimeout(() => {
        quickActionsMenu.classList.remove('hidden');
        quickActionsMenu.classList.add('slide-up');
        document.body.style.overflow = 'hidden';
        if (searchInput) {
            searchInput.focus();
        }
    }, 300);
}

// Clear search function
function clearSearch() {
    // Clear search input
    const searchInputs = document.querySelectorAll('input[name="search"]');
    searchInputs.forEach(input => {
        input.value = '';
    });
    
    // Submit form to reload without search parameter
    const forms = document.querySelectorAll('form[action="{{ route("karyawan.dashboard") }}"]');
    forms.forEach(form => {
        const formData = new FormData(form);
        formData.delete('search');
        
        // Create new URL without search parameter
        const url = new URL(form.action);
        const params = new URLSearchParams(formData);
        window.location.href = url.pathname + '?' + params.toString();
    });
}

// Auto-submit search on desktop when typing stops (debounced)
let searchTimeout;
const desktopSearchInput = document.getElementById('desktop-search-input');
if (desktopSearchInput) {
    desktopSearchInput.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('desktop-search-form').submit();
        }, 800); // 800ms delay
    });
}

// Submit filter form on status change
const statusRadios = document.querySelectorAll('input[name="status"]');
statusRadios.forEach(radio => {
    radio.addEventListener('change', function() {
        if (this.checked && this.value !== 'all') {
            // Submit the closest form
            const form = this.closest('form');
            if (form) {
                form.submit();
            }
        }
    });
});

// Mobile-specific touch optimizations
if (window.innerWidth < 768) {
    document.addEventListener('DOMContentLoaded', function() {
        // Add active states for better touch feedback
        const touchElements = document.querySelectorAll('a, button');
        touchElements.forEach(el => {
            el.addEventListener('touchstart', function() {
                this.classList.add('active:scale-95');
            });
            
            el.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.classList.remove('active:scale-95');
                }, 150);
            });
        });
        
        // Prevent double-tap zoom
        let lastTouchEnd = 0;
        document.addEventListener('touchend', function(event) {
            const now = (new Date()).getTime();
            if (now - lastTouchEnd <= 300) {
                event.preventDefault();
            }
            lastTouchEnd = now;
        }, false);
        
        // Smooth horizontal scrolling for cards
        const cardContainer = document.querySelector('.overflow-x-auto');
        if (cardContainer) {
            cardContainer.style.scrollBehavior = 'smooth';
        }
    });
}

// Desktop-specific optimizations
if (window.innerWidth >= 768) {
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover effects to cards
        const cards = document.querySelectorAll('.hover-lift');
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
            });
        });
        
        // Focus search input when clicking search icon
        const searchIcon = document.querySelector('.fa-search');
        if (searchIcon) {
            searchIcon.addEventListener('click', function() {
                const searchInput = this.closest('.relative').querySelector('input');
                if (searchInput) {
                    searchInput.focus();
                }
            });
        }
    });
}

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeQuickMenu();
        closeMobileFilter();
        closeDesktopFilter();
    }
});

// Click outside to close modals
document.addEventListener('click', function(e) {
    if (quickActionsMenu && !quickActionsMenu.contains(e.target) && 
        mobileQuickMenu && !mobileQuickMenu.contains(e.target) && 
        !quickActionsMenu.classList.contains('hidden')) {
        closeQuickMenu();
    }
});

// Initialize tooltips for filter badges
document.addEventListener('DOMContentLoaded', function() {
    const filterBadges = document.querySelectorAll('[title]');
    filterBadges.forEach(badge => {
        badge.addEventListener('mouseenter', function() {
            this.style.cursor = 'pointer';
        });
    });
});
</script>
@endsection