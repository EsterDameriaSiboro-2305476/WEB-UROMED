@extends('layouts.app')

@section('title', 'Dashboard - Uromed')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Welcome Section -->
    <div class="mb-12">
        <div class="gradient-bg rounded-3xl p-12 text-blue-900 shadow-xl bg-gradient-to-r from-yellow-100 to-blue-200">
            <div class="max-w-2xl">
                <h2 class="text-4xl font-bold mb-4">Selamat Datang di Uromed!</h2>
                <p class="text-gray-600 text-xl mb-6">Sistem analisis urin pintar yang membantu Anda melakukan diagnosa dengan akurat dan efisien</p>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                        <span class="text-sm text-gray-600">Sistem Online</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-400 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600">AI Engine Ready</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="bg-white/80 backdrop-blur rounded-2xl p-6 shadow-lg border border-white/20">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Tes Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">24</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white/80 backdrop-blur rounded-2xl p-6 shadow-lg border border-white/20">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tes Normal</p>
                    <p class="text-2xl font-bold text-gray-900">18</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white/80 backdrop-blur rounded-2xl p-6 shadow-lg border border-white/20">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.168 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Perlu Perhatian</p>
                    <p class="text-2xl font-bold text-gray-900">4</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white/80 backdrop-blur rounded-2xl p-6 shadow-lg border border-white/20">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Abnormal</p>
                    <p class="text-2xl font-bold text-gray-900">2</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Menu -->
    <div class="mb-8">
        <div class="text-center mb-10">
            <h3 class="text-3xl font-bold text-gray-900 mb-4">Pilih Menu Utama</h3>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Akses semua fitur sistem analisis urin Uromed dengan mudah dan cepat</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8">
            <!-- Tes Baru -->
            <a href="{{ route('test-baru') }}" class="menu-card rounded-2xl p-8 card-hover cursor-pointer group shadow-lg bg-gradient-to-r from-blue-200 to-yellow-100 block">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 icon-bounce shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Tes Baru</h4>
                    <p class="text-gray-600 mb-6 leading-relaxed">Mulai analisis urin baru dengan sensor otomatis dan teknologi AI terdepan</p>
                    <div class="flex flex-col space-y-2 text-sm text-gray-500">
                        <div class="flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Analisis pH otomatis</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Deteksi warna & densitas</span>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Hasil Tes -->
            <a href="{{ route('hasil-tes') }}" class="menu-card rounded-2xl p-8 card-hover cursor-pointer group shadow-lg bg-gradient-to-r from-yellow-100 to-blue-200 block">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6 icon-bounce shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Hasil Tes</h4>
                    <p class="text-gray-600 mb-6 leading-relaxed">Lihat, kelola, dan analisis semua hasil tes yang telah dilakukan dengan detail lengkap</p>
                    <div class="flex flex-col space-y-2 text-sm text-gray-500">
                        <div class="flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Riwayat tes lengkap</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Export laporan PDF</span>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Analisis AI -->
            <a href="{{ route('analisis-ai') }}" class="menu-card rounded-2xl p-8 card-hover cursor-pointer group shadow-lg bg-gradient-to-r from-blue-200 to-yellow-100 block">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-purple-500 to-violet-600 rounded-2xl flex items-center justify-center mx-auto mb-6 icon-bounce shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Analisis AI</h4>
                    <p class="text-gray-600 mb-6 leading-relaxed">Dapatkan insight mendalam dan prediksi akurat dengan kecerdasan buatan terdepan</p>
                    <div class="flex flex-col space-y-2 text-sm text-gray-500">
                        <div class="flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Machine Learning</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Deteksi anomali otomatis</span>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Data Pasien -->
            <a href="{{ route('data-pasien') }}" class="menu-card rounded-2xl p-8 card-hover cursor-pointer group shadow-lg bg-gradient-to-r from-yellow-100 to-blue-200 block">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-orange-500 to-red-500 rounded-2xl flex items-center justify-center mx-auto mb-6 icon-bounce shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Data Pasien</h4>
                    <p class="text-gray-600 mb-6 leading-relaxed">Kelola informasi dan riwayat medis pasien dengan sistem yang aman dan terorganisir</p>
                    <div class="flex flex-col space-y-2 text-sm text-gray-500">
                        <div class="flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Database terenkripsi</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Riwayat medis lengkap</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white/80 backdrop-blur rounded-2xl p-8 shadow-lg border border-white/20">
        <h4 class="text-xl font-bold text-gray-900 mb-6">Aktivitas Terbaru</h4>
        <div class="space-y-4">
            <div class="flex items-center p-4 bg-blue-50 rounded-lg">
                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Tes Urin Pasien #UR-2025-001 selesai</p>
                    <p class="text-sm text-gray-500">Hasil normal - pH: 6.2, Protein: Negatif</p>
                </div>
                <span class="text-xs text-gray-400">2 menit lalu</span>
            </div>
            
            <div class="flex items-center p-4 bg-yellow-50 rounded-lg">
                <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.168 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Peringatan AI: Anomali terdeteksi</p>
                    <p class="text-sm text-gray-500">Pasien #UR-2025-002 - Perlu review manual</p>
                </div>
                <span class="text-xs text-gray-400">5 menit lalu</span>
            </div>
            
            <div class="flex items-center p-4 bg-green-50 rounded-lg">
                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Data pasien baru ditambahkan</p>
                    <p class="text-sm text-gray-500">Ahmad Pratama - ID: PAT-2025-156</p>
                </div>
                <span class="text-xs text-gray-400">15 menit lalu</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Dashboard Uromed - Menu Utama berhasil dimuat!');
        
        // Auto-refresh statistics every 30 seconds
        setInterval(function() {
            // Simulate real-time updates
            updateStatistics();
        }, 30000);
    });

    function updateStatistics() {
        // This would typically fetch from your API
        console.log('Updating statistics...');
        showToast('Statistik diperbarui', 'blue', 2000);
    }
</script>
@endsection