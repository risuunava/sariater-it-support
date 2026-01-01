<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SARIATER IT SUPPORT</title>
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome via CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        }
        .hover-scale {
            transition: transform 0.3s ease;
        }
        .hover-scale:hover {
            transform: translateY(-2px);
        }
        .status-badge {
            @apply px-3 py-1 rounded-full text-sm font-semibold;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="gradient-bg text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <i class="fas fa-headset text-2xl"></i>
                    <a href="{{ Auth::check() ? (Auth::user()->role === 'admin' ? route('admin.dashboard') : route('karyawan.dashboard')) : '/' }}" 
                       class="text-xl font-bold">
                        SARIATER IT SUPPORT
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="flex items-center space-x-4">
                    @auth
                        <span class="hidden md:inline">
                            <i class="fas fa-user mr-1"></i>
                            {{ Auth::user()->name }}
                            <span class="ml-2 px-2 py-1 bg-blue-800 rounded text-xs">
                                {{ Auth::user()->role === 'admin' ? 'Admin' : 'Karyawan' }}
                            </span>
                        </span>
                        
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="hover:bg-blue-700 px-4 py-2 rounded transition">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:bg-blue-700 px-4 py-2 rounded transition">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-white text-blue-600 hover:bg-gray-100 px-4 py-2 rounded transition">
                            <i class="fas fa-user-plus mr-2"></i>Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <div class="flex justify-center items-center space-x-2 mb-4">
                    <i class="fas fa-headset text-2xl text-blue-400"></i>
                    <h3 class="text-xl font-bold">SARIATER IT SUPPORT</h3>
                </div>
                <p class="text-gray-400 mb-4">
                    Sistem Pelaporan IT Support Internal Perusahaan
                </p>
                <div class="text-gray-500 text-sm">
                    <p>© {{ date('Y') }} SARIATER IT SUPPORT. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div id="success-message" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        <script>
            setTimeout(() => {
                document.getElementById('success-message').style.opacity = '0';
                document.getElementById('success-message').style.transition = 'opacity 0.5s';
            }, 3000);
        </script>
    @endif

    @if($errors->any())
        <div id="error-message" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
        <script>
            setTimeout(() => {
                document.getElementById('error-message').style.opacity = '0';
                document.getElementById('error-message').style.transition = 'opacity 0.5s';
            }, 5000);
        </script>
    @endif
</body>
</html>