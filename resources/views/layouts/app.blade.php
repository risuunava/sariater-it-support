<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SARIATER IT SUPPORT</title>
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome via CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --color-primary: #3b82f6;
            --color-primary-dark: #1e40af;
            --color-success: #10b981;
            --color-warning: #f59e0b;
            --color-danger: #ef4444;
        }
        
        * {
            font-family: 'Inter', sans-serif;
            scrollbar-width: thin;
            scrollbar-color: var(--color-primary) transparent;
        }
        
        *::-webkit-scrollbar {
            width: 8px;
        }
        
        *::-webkit-scrollbar-track {
            background: transparent;
        }
        
        *::-webkit-scrollbar-thumb {
            background-color: var(--color-primary);
            border-radius: 20px;
        }
        
        .font-heading {
            font-family: 'Poppins', sans-serif;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .glass-dark {
            background: rgba(17, 24, 39, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        }
        
        .gradient-bg-dark {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        }
        
        .gradient-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
        
        .gradient-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .gradient-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        
        .gradient-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .shadow-soft {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }
        
        .shadow-hover {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .shadow-xl-soft {
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        }
        
        .status-badge {
            @apply px-4 py-2 rounded-full text-sm font-semibold transition-all duration-200;
        }
        
        .card-hover {
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }
        
        .card-hover:hover {
            border-color: rgba(59, 130, 246, 0.2);
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.1);
        }
        
        .input-modern {
            @apply w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white/90;
        }
        
        .btn-primary {
            @apply px-6 py-3 gradient-primary text-white font-semibold rounded-xl hover:opacity-90 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center;
        }
        
        .btn-secondary {
            @apply px-6 py-3 bg-white text-gray-700 font-semibold rounded-xl border border-gray-200 hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow flex items-center justify-center;
        }
        
        .btn-outline {
            @apply px-6 py-3 border-2 border-blue-500 text-blue-600 font-semibold rounded-xl hover:bg-blue-50 transition-all duration-200 flex items-center justify-center;
        }
        
        .animate-pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        
        .animate-slide-in {
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .bg-grid-pattern {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(241 245 249 / 0.3)'%3e%3cpath d='M0 .5H31.5V32'/%3e%3c/svg%3e");
        }
        
        /* Premium Table Styles */
        .premium-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .premium-table thead {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        .premium-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }
        
        .premium-table tbody tr:hover {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        }
        
        /* Custom Badges */
        .status-badge.pending {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        
        .status-badge.progress {
            background: linear-gradient(135deg, #dbeafe 0%, #93c5fd 100%);
            color: #1e40af;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        
        .status-badge.done {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        
        /* Loading Animation */
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-white to-blue-50 min-h-screen">
    <!-- Navigation -->
    <nav class="glass-effect shadow-sm fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg">
                            <i class="fas fa-headset text-white text-lg"></i>
                        </div>
                        <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-green-500 border-2 border-white"></div>
                    </div>
                    <div>
                        <h1 class="font-heading text-xl font-bold text-gray-900">SARIATER</h1>
                        <p class="text-xs text-blue-600 font-medium">IT SUPPORT SYSTEM</p>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    @auth
                    <div class="relative group">
                        <button class="flex items-center space-x-3 bg-white/50 px-4 py-2 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300">
                            <div class="relative">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold text-sm">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-3 h-3 rounded-full border-2 border-white 
                                    {{ Auth::user()->role === 'admin' ? 'bg-green-500' : 'bg-blue-500' }}"></div>
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                <div class="flex items-center space-x-1">
                                    <span class="text-xs px-2 py-0.5 rounded-full 
                                        {{ Auth::user()->role === 'admin' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ Auth::user()->role === 'admin' ? 'ADMIN' : 'KARYAWAN' }}
                                    </span>
                                </div>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-56 glass-effect rounded-xl shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                            <div class="p-2">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="space-y-1 p-2">
                                    @if(Auth::user()->role === 'karyawan')
                                    <a href="{{ route('karyawan.profile') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                        <i class="fas fa-user text-sm w-5"></i>
                                        <span class="text-sm">Profil Saya</span>
                                    </a>
                                    @else
                                    <a href="{{ route('admin.settings') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                        <i class="fas fa-cog text-sm w-5"></i>
                                        <span class="text-sm">Pengaturan</span>
                                    </a>
                                    @endif
                                    <form action="{{ route('logout') }}" method="POST" class="pt-2 border-t border-gray-100">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center space-x-3 px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 transition">
                                            <i class="fas fa-sign-out-alt text-sm w-5"></i>
                                            <span class="text-sm font-medium">Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="btn-outline">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="btn-primary">
                            <i class="fas fa-user-plus mr-2"></i>Register
                        </a>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16">
        <!-- Page Header -->
        <div class="relative overflow-hidden bg-gradient-to-br from-blue-50 via-white to-indigo-50">
            <div class="absolute inset-0 bg-grid-pattern opacity-50"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center space-x-2 mb-4">
                            @auth
                            <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                                @if(Auth::user()->role === 'admin')
                                <i class="fas fa-shield-alt mr-1"></i> Admin Dashboard
                                @else
                                <i class="fas fa-user-tie mr-1"></i> Karyawan Dashboard
                                @endif
                            </span>
                            @endauth
                            <span class="text-sm text-gray-500">
                                <i class="far fa-clock mr-1"></i> {{ date('d M Y, H:i') }}
                            </span>
                        </div>
                        <h1 class="font-heading text-3xl lg:text-4xl font-bold text-gray-900 mb-3">
                            <span class="gradient-text">@yield('page-title')</span>
                        </h1>
                        <p class="text-gray-600 leading-relaxed">@yield('page-description')</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        @yield('header-actions')
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 md:pb-8">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="glass-dark border-t border-white/10 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-lg">
                            <i class="fas fa-headset text-xl text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-heading text-xl font-bold text-white">SARIATER</h3>
                            <p class="text-blue-300 text-sm">IT SUPPORT SYSTEM</p>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm">
                        Sistem pelaporan dan manajemen IT Support internal perusahaan.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-heading font-semibold text-white mb-6">Quick Links</h4>
                    <div class="space-y-2">
                        @if(auth()->check() && auth()->user()->role === 'karyawan')
                        <a href="{{ route('karyawan.dashboard') }}" class="block text-gray-300 hover:text-white transition">Dashboard</a>
                        <a href="{{ route('karyawan.tickets.create') }}" class="block text-gray-300 hover:text-white transition">Buat Laporan</a>
                        <a href="{{ route('karyawan.analytics') }}" class="block text-gray-300 hover:text-white transition">Analytics</a>
                        @elseif(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="block text-gray-300 hover:text-white transition">Dashboard</a>
                        <a href="{{ route('admin.tickets') }}" class="block text-gray-300 hover:text-white transition">Manajemen Tiket</a>
                        <a href="{{ route('admin.analytics') }}" class="block text-gray-300 hover:text-white transition">Analytics</a>
                        @endif
                    </div>
                </div>
                
                <div>
                    <h4 class="font-heading font-semibold text-white mb-6">Resources</h4>
                    <div class="space-y-2">
                        <a href="#" class="block text-gray-300 hover:text-white transition">Dokumentasi</a>
                        <a href="#" class="block text-gray-300 hover:text-white transition">Tutorial</a>
                        <a href="#" class="block text-gray-300 hover:text-white transition">FAQ</a>
                        <a href="#" class="block text-gray-300 hover:text-white transition">Updates</a>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-heading font-semibold text-white mb-6">Support</h4>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-600/20 flex items-center justify-center">
                                <i class="fas fa-phone text-blue-400"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-300">Emergency</p>
                                <p class="font-medium text-white">(021) 1234-5678</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-600/20 flex items-center justify-center">
                                <i class="fas fa-envelope text-blue-400"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-300">Email</p>
                                <p class="font-medium text-white">support@sariater.com</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-white/10 mt-8 pt-6 text-center">
                <p class="text-gray-400 text-sm">
                    © {{ date('Y') }} SARIATER IT SUPPORT. All rights reserved. 
                    <span class="text-blue-400">v3.0</span>
                </p>
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom Navigation -->
    @if(auth()->check())
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 py-2 px-4 flex justify-around items-center md:hidden z-40 shadow-lg">
        @if(auth()->user()->role === 'karyawan')
        <!-- Home -->
        <a href="{{ route('karyawan.dashboard') }}" 
           class="flex flex-col items-center {{ request()->routeIs('karyawan.dashboard') ? 'text-blue-600' : 'text-gray-600' }}">
            <div class="w-12 h-12 flex items-center justify-center">
                <i class="fas fa-home text-lg"></i>
            </div>
            <span class="text-xs mt-1 font-medium">Home</span>
        </a>
        
        <!-- Analytics -->
        <a href="{{ route('karyawan.analytics') }}" 
           class="flex flex-col items-center {{ request()->routeIs('karyawan.analytics') ? 'text-blue-600' : 'text-gray-600' }}">
            <div class="w-12 h-12 flex items-center justify-center">
                <i class="fas fa-chart-bar text-lg"></i>
            </div>
            <span class="text-xs mt-1 font-medium">Analytics</span>
        </a>
        
        <!-- Quick Action -->
        <a href="{{ route('karyawan.tickets.create') }}" 
           class="flex flex-col items-center relative -mt-6">
            <div class="w-14 h-14 bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl flex items-center justify-center shadow-lg hover:shadow-xl transition-shadow">
                <i class="fas fa-plus text-white text-lg"></i>
            </div>
            <span class="text-xs mt-1 font-medium text-gray-600">Buat</span>
        </a>
        
        <!-- Tickets/Laporan -->
        <a href="{{ route('karyawan.dashboard') }}" 
           class="flex flex-col items-center {{ request()->routeIs('karyawan.tickets.*') || request()->is('karyawan/dashboard') ? 'text-blue-600' : 'text-gray-600' }}">
            <div class="w-12 h-12 flex items-center justify-center">
                <i class="fas fa-list text-lg"></i>
            </div>
            <span class="text-xs mt-1 font-medium">Laporan</span>
        </a>
        
        <!-- Profile -->
        <a href="{{ route('karyawan.profile') }}" 
           class="flex flex-col items-center {{ request()->routeIs('karyawan.profile') ? 'text-blue-600' : 'text-gray-600' }}">
            <div class="w-12 h-12 flex items-center justify-center">
                <i class="fas fa-user text-lg"></i>
            </div>
            <span class="text-xs mt-1 font-medium">Profil</span>
        </a>
        @elseif(auth()->user()->role === 'admin')
        <!-- Admin Dashboard -->
        <a href="{{ route('admin.dashboard') }}" 
           class="flex flex-col items-center {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-gray-600' }}">
            <div class="w-12 h-12 flex items-center justify-center">
                <i class="fas fa-home text-lg"></i>
            </div>
            <span class="text-xs mt-1 font-medium">Home</span>
        </a>
        
        <!-- Analytics -->
        <a href="{{ route('admin.analytics') }}" 
           class="flex flex-col items-center {{ request()->routeIs('admin.analytics') ? 'text-blue-600' : 'text-gray-600' }}">
            <div class="w-12 h-12 flex items-center justify-center">
                <i class="fas fa-chart-bar text-lg"></i>
            </div>
            <span class="text-xs mt-1 font-medium">Analytics</span>
        </a>
        
        <!-- Tickets -->
        <a href="{{ route('admin.tickets') }}" 
           class="flex flex-col items-center {{ request()->routeIs('admin.tickets.*') ? 'text-blue-600' : 'text-gray-600' }}">
            <div class="w-12 h-12 flex items-center justify-center">
                <i class="fas fa-ticket-alt text-lg"></i>
            </div>
            <span class="text-xs mt-1 font-medium">Tickets</span>
        </a>
        
        <!-- Quick Action -->
        <a href="{{ route('admin.dashboard') }}" 
           class="flex flex-col items-center relative -mt-6">
            <div class="w-14 h-14 bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl flex items-center justify-center shadow-lg hover:shadow-xl transition-shadow">
                <i class="fas fa-cog text-white text-lg"></i>
            </div>
            <span class="text-xs mt-1 font-medium text-gray-600">Admin</span>
        </a>
        
        <!-- Settings -->
        <a href="{{ route('admin.settings') }}" 
           class="flex flex-col items-center {{ request()->routeIs('admin.settings') ? 'text-blue-600' : 'text-gray-600' }}">
            <div class="w-12 h-12 flex items-center justify-center">
                <i class="fas fa-user text-lg"></i>
            </div>
            <span class="text-xs mt-1 font-medium">Profile</span>
        </a>
        @endif
    </div>
    @endif

    <!-- Notifications -->
    @if(session('success'))
    <div id="notification-success" class="fixed bottom-4 right-4 z-50 animate-slide-in">
        <div class="glass-effect border-l-4 border-green-500 px-6 py-4 rounded-xl shadow-xl flex items-center space-x-3 w-96">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                <i class="fas fa-check text-white"></i>
            </div>
            <div class="flex-1">
                <p class="font-semibold text-gray-900">Success!</p>
                <p class="text-sm text-gray-600 mt-1">{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const el = document.getElementById('notification-success');
            if(el) {
                el.style.transform = 'translateX(100%)';
                el.style.opacity = '0';
                el.style.transition = 'all 0.5s ease';
                setTimeout(() => el.remove(), 500);
            }
        }, 5000);
    </script>
    @endif

    @if($errors->any())
    <div id="notification-error" class="fixed bottom-4 right-4 z-50 animate-slide-in">
        <div class="glass-effect border-l-4 border-red-500 px-6 py-4 rounded-xl shadow-xl w-96">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                    <p class="font-semibold text-gray-900">Validation Error</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="space-y-2">
                @foreach($errors->all() as $error)
                <p class="text-sm text-gray-600 bg-red-50 p-2 rounded-lg">{{ $error }}</p>
                @endforeach
            </div>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const el = document.getElementById('notification-error');
            if(el) {
                el.style.transform = 'translateX(100%)';
                el.style.opacity = '0';
                el.style.transition = 'all 0.5s ease';
                setTimeout(() => el.remove(), 500);
            }
        }, 8000);
    </script>
    @endif

    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to cards
            const cards = document.querySelectorAll('.card-hover');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
            
            // Auto-hide notifications after 5 seconds
            setTimeout(() => {
                const notifications = document.querySelectorAll('[id^="notification-"]');
                notifications.forEach(notification => {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => notification.remove(), 500);
                });
            }, 5000);
            
            // Active state for mobile navigation
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.fixed.bottom-0 a[href]');
            
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href && currentPath.includes(href.replace(window.location.origin, ''))) {
                    link.classList.add('text-blue-600');
                    link.classList.remove('text-gray-600');
                }
            });
        });
    </script>
</body>
</html>