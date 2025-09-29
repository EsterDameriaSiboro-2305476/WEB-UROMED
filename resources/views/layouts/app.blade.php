<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Uromed - Smart Urine Analysis System')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Styles -->
    <style>
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .menu-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #e2e8f0;
        }
        .menu-card:hover {
            background: linear-gradient(145deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #cbd5e1;
        }
        .icon-bounce {
            transition: transform 0.3s ease;
        }
        .menu-card:hover .icon-bounce {
            transform: scale(1.1) rotate(5deg);
        }
        @yield('custom-styles')
    </style>
</head>
<body class="bg-gradient-to-br from-white to-green-200 min-h-screen">
    <!-- Header -->
    <header class="bg-blue-300 backdrop-blur-md shadow-lg border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo dan Brand -->
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mr-3 shadow-lg p-1">
                                <img src="{{ asset('images/uromed-logo.png') }}" alt="Uromed Logo" class="w-full h-full object-contain">
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-blue-900">Uromed</h1>
                                <p class="text-sm text-gray-500">Smart Urine Analysis System</p>
                            </div>
                        </a>
                    </div>
                </div>
                
                <!-- Navigation Menu -->
                <nav class="hidden md:flex space-x-6">
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'text-blue-600 bg-blue-50' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('test-baru') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('test-baru') ? 'text-blue-600 bg-blue-50' : '' }}">
                        Tes Baru
                    </a>
                    <a href="{{ route('hasil-tes') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('hasil-tes') ? 'text-blue-600 bg-blue-50' : '' }}">
                        Hasil Tes
                    </a>
                    <a href="{{ route('analisis-ai') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('analisis-ai') ? 'text-blue-600 bg-blue-50' : '' }}">
                        Analisis AI
                    </a>
                    <a href="{{ route('data-pasien') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('data-pasien') ? 'text-blue-600 bg-blue-50' : '' }}">
                        Data Pasien
                    </a>
                </nav>
                
                <!-- Right Header Items -->
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" onclick="showNotifications()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </button>
                        <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                    </div>
                    <div class="text-sm text-gray-600 bg-white/60 backdrop-blur px-3 py-2 rounded-lg border border-gray-200">
                        {{ now()->locale('id')->translatedFormat('l, d F Y') }}
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center cursor-pointer hover:shadow-lg transition-shadow" onclick="showUserMenu()">
                        <span class="text-sm font-medium text-white">AD</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white/60 backdrop-blur-md border-t border-gray-200 py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">© 2025 Uromed. Smart Urine Analysis System.</p>
                <div class="flex items-center space-x-6">
                    <button class="text-sm text-gray-500 hover:text-gray-700 transition-colors">Tentang</button>
                    <button class="text-sm text-gray-500 hover:text-gray-700 transition-colors">Dukungan</button>
                    <button class="text-sm text-gray-500 hover:text-gray-700 transition-colors">Kebijakan Privasi</button>
                </div>
            </div>
        </div>
    </footer>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50"></div>

    <!-- Base JavaScript -->
    <script>
        // Toast notification function
        function showToast(message, color = 'blue', duration = 3000) {
            const colors = {
                blue: 'bg-blue-500',
                green: 'bg-green-500',
                purple: 'bg-purple-500',
                orange: 'bg-orange-500',
                red: 'bg-red-500',
                gray: 'bg-gray-500'
            };

            const toast = document.createElement('div');
            toast.className = `${colors[color]} text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 mb-2`;
            toast.textContent = message;
            
            const container = document.getElementById('toast-container');
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);
            
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (container.contains(toast)) {
                        container.removeChild(toast);
                    }
                }, 300);
            }, duration);
        }

        // Notification function
        function showNotifications() {
            showToast('3 notifikasi baru: 2 tes selesai, 1 peringatan sistem', 'blue', 4000);
        }

        // User menu function
        function showUserMenu() {
            showToast('Menu profil pengguna - Admin Dashboard', 'gray');
        }

        // CSRF Token setup for AJAX
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };

        // Setup CSRF token for all AJAX requests
        if (typeof $ !== 'undefined') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }
    </script>

    @yield('scripts')
</body>
</html>