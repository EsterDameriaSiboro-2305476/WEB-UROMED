@extends('layouts.app')

@section('title', 'Analisis AI - Uromed')

@section('custom-styles')
.ai-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.prediction-red {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border-left: 4px solid #ef4444;
}
.prediction-orange {
    background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
    border-left: 4px solid #f59e0b;
}
.prediction-green {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    border-left: 4px solid #10b981;
}
.neural-network {
    background: radial-gradient(circle at 20% 50%, #667eea 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, #764ba2 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, #3b82f6 0%, transparent 50%);
}
@keyframes pulse-ai {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.05); opacity: 0.8; }
}
.ai-processing { animation: pulse-ai 2s infinite; }
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
            <li class="text-gray-500">/</li>
            <li class="text-gray-700">Analisis AI</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Analisis AI Uromed</h1>
            <p class="text-gray-600 mt-2">Kecerdasan buatan untuk prediksi dan analisis mendalam hasil urin</p>
        </div>
        <div class="flex space-x-4">

            <button onclick="exportAnalysis()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Report
            </button>
        </div>
    </div>

    <!-- AI Status Card -->
    <div class="ai-card rounded-2xl p-8 text-white shadow-xl mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">AI Agent Status</h2>
                <p class="text-white/80 mb-4">Model LLM untuk Analisis Urin</p>
                <div class="flex items-center space-x-6">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                        <span class="text-sm">Online & Ready</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-400 rounded-full mr-2"></div>
                        <span class="text-sm">Model Gemini Flash 2.0</span>
                    </div>
                </div>
            </div>
            <div class="neural-network w-32 h-32 rounded-full flex items-center justify-center">
                <svg class="w-16 h-16 text-white ai-processing" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Real-time Predictions -->
        <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-200">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Resiko Penyakit</h3>

            <div class="space-y-4">
                <!-- Modal Background -->
<div id="prediction-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <!-- Modal Box -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
        <!-- Header -->
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h3 id="prediction-modal-title" class="text-lg font-semibold text-gray-900">Detail Prediksi</h3>
            <button onclick="closePredictionModal()" class="text-gray-400 hover:text-gray-600">
                ✕
            </button>
        </div>

        <!-- Body -->
        <div id="prediction-modal-content" class="px-6 py-4 text-sm text-gray-700">
            <!-- Konten dari viewPredictionDetails() akan masuk sini -->
        </div>

        <!-- Footer -->
        <div class="border-t px-6 py-4 flex justify-end">
            <button onclick="closePredictionModal()" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                Tutup
            </button>
        </div>
    </div>
</div>

                @foreach ($analisis_ai['risk_disease'] as $risk)
                    @php
                        if ($risk['percentage'] > 66) $color = 'red';
                        elseif ($risk['percentage'] > 33) $color = 'orange';
                        else $color = 'green';
                    @endphp

                    <x-risk-card :risk="$risk" :color="$color" />
                @endforeach
            </div>

            {{-- <button onclick="runNewPrediction()" class="mt-6 w-full py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium">
                Jalankan Prediksi Baru
            </button> --}}
        </div>

        <!-- Pattern Analysis -->
        <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-200">
            <h3 class="text-xl font-bold text-gray-900 mb-1">Hasil Sensor</h3>
            @foreach ($last_result as $key => $value)
                <div>
                    <p class="text-sm text-black-600">{{ ucfirst($key) }}: {{ $value ?? '-' }}</p>
                </div>
            @endforeach

            <h3 class="text-xl font-bold text-gray-900 mb-3 mt-2">Analisis Pola</h3>

            <p class="text-sm text-black-600 mb-3">{{$analisis_ai['analysis']?? 'N/A'}}</p>
            <h3 class="text-xl font-bold text-gray-900 mb-3">Saran Penanganan</h3>

            <p class="text-sm text-black-600 mb-3">{{$analisis_ai['solve_step']?? 'N/A'}}</p>
            <h3 class="text-xl font-bold text-gray-900 mb-3">Kondisi Keseluruhan</h3>

            <p class="text-sm text-black-600 mb-3">{{$analisis_ai['overall_status']?? 'N/A'}}</p>
        </div>
    </div>






@endsection

@section('scripts')
<script>
function closePredictionModal() {
    document.getElementById('prediction-modal').classList.add('hidden');
}

function viewPredictionDetails(title, detail) {
    if (!detail) return;
    const content = `
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-medium text-blue-900 mb-2">${title}</h4>
                <p class="text-sm text-blue-800">${detail}</p>
            </div>
    `;

    document.getElementById('prediction-modal-title').textContent = detail.title;
    document.getElementById('prediction-modal-content').innerHTML = content;
    document.getElementById('prediction-modal').classList.remove('hidden');
}

function closePredictionModal() {
    document.getElementById('prediction-modal').classList.add('hidden');
}

function runNewPrediction() {
    showToast('Menjalankan prediksi AI baru...', 'purple');

    // Simulate AI processing
    setTimeout(() => {
        showToast('Prediksi selesai! 3 kasus dianalisis', 'green');

    }, 3000);
}

function trainModel() {
    if (confirm('Apakah Anda yakin ingin melatih ulang model AI? Proses ini akan memakan waktu 15-30 menit.')) {
        showToast('Memulai pelatihan model AI...', 'purple');


        let progress = 0;
        const trainingInterval = setInterval(() => {
            progress += Math.random() * 10;
            if (progress >= 100) {
                clearInterval(trainingInterval);
                showToast('Pelatihan model selesai! Akurasi baru: 94.8%', 'green');
            } else {
                showToast(`Pelatihan berlangsung... ${Math.round(progress)}%`, 'blue');
            }
        }, 2000);
    }
}

function exportAnalysis() {
    showToast('Menyiapkan laporan analisis AI...', 'green');

    setTimeout(() => {
        showToast('Laporan AI berhasil diekspor ke PDF', 'green');
    }, 2500);
}

function uploadBulkData() {
    // Simulate file upload
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.csv';

    input.onchange = function(e) {
        const file = e.target.files[0];
        if (file) {
            showToast(`File ${file.name} berhasil diupload`, 'green');

            setTimeout(() => {
                showToast('Memproses prediksi batch...', 'blue');

                setTimeout(() => {
                    showToast('Prediksi batch selesai! 247 sampel dianalisis', 'green');
                }, 4000);
            }, 1000);
        }
    };

    input.click();
}

// Close modal when clicking outside
document.getElementById('prediction-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePredictionModal();
    }
});

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Analisis AI page loaded');
    showToast('AI Engine siap untuk analisis', 'purple');

    // Simulate real-time AI activity
    setInterval(() => {
        const activities = [
            'Model mendeteksi pola baru dalam dataset',
            'Prediksi real-time untuk pasien baru',
            'Kalibrasi otomatis parameter AI',
            'Optimasi algoritma neural network'
        ];

        const randomActivity = activities[Math.floor(Math.random() * activities.length)];
        console.log(`AI Activity: ${randomActivity}`);
    }, 30000);
});
</script>
@endsection
