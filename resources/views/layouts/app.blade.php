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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .font-heading {
            font-family: 'Poppins', sans-serif;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
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
        
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .shadow-soft {
            box-shadow: 0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04);
        }
        
        .shadow-hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .status-badge {
            @apply px-4 py-2 rounded-full text-sm font-semibold transition-all duration-200;
        }
        
        .progress-bar {
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.6s ease;
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
        
        .card-hover {
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }
        
        .card-hover:hover {
            border-color: rgba(59, 130, 246, 0.3);
            box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.1), 0 10px 10px -5px rgba(59, 130, 246, 0.04);
        }
        
        .sidebar-link {
            @apply flex items-center px-4 py-3 text-gray-700 rounded-lg transition-all duration-200;
        }
        
        .sidebar-link:hover {
            @apply bg-blue-50 text-blue-600;
        }
        
        .sidebar-link.active {
            @apply gradient-primary text-white shadow-md;
        }
        
        .input-modern {
            @apply w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition;
        }
        
        .btn-primary {
            @apply px-6 py-3 gradient-primary text-white font-semibold rounded-xl hover:opacity-90 transition-all duration-200 shadow-md hover:shadow-lg;
        }
        
        .btn-secondary {
            @apply px-6 py-3 bg-white text-gray-700 font-semibold rounded-xl border border-gray-200 hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow;
        }
        
        .neumorphic {
            background: #f0f0f0;
            border-radius: 20px;
            box-shadow:  20px 20px 60px #cccccc,
                        -20px -20px 60px #ffffff;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="glass-effect shadow-md fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 w-10 h-10 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-headset text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="font-heading text-xl font-bold text-gray-900">SARIATER</h1>
                        <p class="text-xs text-blue-600 font-medium">IT SUPPORT SYSTEM</p>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    @auth
                    <div class="hidden md:flex items-center space-x-3 bg-white/50 px-4 py-2 rounded-xl border border-gray-200">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold text-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="hidden md:block">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <div class="flex items-center space-x-1">
                                <span class="w-2 h-2 rounded-full {{ Auth::user()->role === 'admin' ? 'bg-green-500' : 'bg-blue-500' }}"></span>
                                <p class="text-xs text-gray-500">
                                    {{ Auth::user()->role === 'admin' ? 'Admin IT' : 'Karyawan' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="relative group">
                        <button class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center hover:shadow-md transition">
                            <i class="fas fa-ellipsis-v text-gray-600"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-t-xl flex items-center">
                                    <i class="fas fa-sign-out-alt mr-3"></i>
                                    Logout
                                </button>
                            </form>
                            <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-b-xl flex items-center">
                                <i class="fas fa-cog mr-3"></i>
                                Settings
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="btn-secondary">
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
    <main class="pt-20 pb-8">
        <!-- Page Header -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="font-heading text-3xl font-bold text-gray-900">@yield('page-title')</h1>
                    <p class="text-gray-600 mt-2">@yield('page-description')</p>
                </div>
                <div class="flex items-center space-x-3">
                    @yield('header-actions')
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-gray-900 to-blue-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                            <i class="fas fa-headset text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-heading text-xl font-bold">SARIATER</h3>
                            <p class="text-blue-300 text-sm">IT SUPPORT SYSTEM</p>
                        </div>
                    </div>
                    <p class="text-gray-300 text-sm">
                        Sistem pelaporan dan manajemen IT Support internal perusahaan untuk efisiensi dan produktivitas.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-heading font-semibold mb-4">Quick Links</h4>
                    <div class="space-y-2">
                        <a href="#" class="block text-gray-300 hover:text-white transition">Dashboard</a>
                        <a href="#" class="block text-gray-300 hover:text-white transition">Buat Laporan</a>
                        <a href="#" class="block text-gray-300 hover:text-white transition">Status Laporan</a>
                        <a href="#" class="block text-gray-300 hover:text-white transition">Bantuan</a>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-heading font-semibold mb-4">Contact Support</h4>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-800 flex items-center justify-center">
                                <i class="fas fa-phone text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-300">Emergency</p>
                                <p class="font-medium">(021) 1234-5678</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-800 flex items-center justify-center">
                                <i class="fas fa-envelope text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-300">Email</p>
                                <p class="font-medium">support@sariater.com</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-blue-800 mt-8 pt-6 text-center">
                <p class="text-gray-400 text-sm">
                    © {{ date('Y') }} SARIATER IT SUPPORT. All rights reserved. 
                    <span class="text-blue-400">v2.0</span>
                </p>
            </div>
        </div>
    </footer>

    <!-- Notifications -->
    @if(session('success'))
    <div id="notification-success" class="fixed bottom-4 right-4 z-50">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-4 rounded-xl shadow-lg flex items-center space-x-3 animate-slide-in">
            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                <i class="fas fa-check"></i>
            </div>
            <div>
                <p class="font-semibold">Success!</p>
                <p class="text-sm opacity-90">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const el = document.getElementById('notification-success');
            el.style.transform = 'translateX(100%)';
            el.style.opacity = '0';
            el.style.transition = 'all 0.5s ease';
            setTimeout(() => el.remove(), 500);
        }, 3000);
    </script>
    @endif

    @if($errors->any())
    <div id="notification-error" class="fixed bottom-4 right-4 z-50">
        <div class="bg-gradient-to-r from-red-500 to-rose-600 text-white px-6 py-4 rounded-xl shadow-lg flex items-center space-x-3 animate-slide-in">
            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <p class="font-semibold">Error!</p>
                @foreach($errors->all() as $error)
                <p class="text-sm opacity-90">{{ $error }}</p>
                @endforeach
            </div>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const el = document.getElementById('notification-error');
            el.style.transform = 'translateX(100%)';
            el.style.opacity = '0';
            el.style.transition = 'all 0.5s ease';
            setTimeout(() => el.remove(), 500);
        }, 5000);
    </script>
    @endif

    <!-- Animation CSS -->
    <style>
        @keyframes slide-in {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }
    </style>
</body>
</html>