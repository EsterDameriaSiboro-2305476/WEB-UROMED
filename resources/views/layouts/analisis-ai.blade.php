@extends('layouts.app')

@section('title', 'Analisis AI - Uromed')

@section('custom-styles')
.ai-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.prediction-high { 
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); 
    border-left: 4px solid #ef4444;
}
.prediction-medium { 
    background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%); 
    border-left: 4px solid #f59e0b;
}
.prediction-low { 
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
                <h2 class="text-2xl font-bold mb-2">AI Engine Status</h2>
                <p class="text-white/80 mb-4">Model Neural Network untuk Analisis Urin</p>
                <div class="flex items-center space-x-6">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                        <span class="text-sm">Online & Ready</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-400 rounded-full mr-2"></div>
                        <span class="text-sm">Model v2.3.1</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></div>
                        <span class="text-sm">Accuracy: 94.2%</span>
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
            <h3 class="text-xl font-bold text-gray-900 mb-6">Prediksi Real-time</h3>
            
            <div class="space-y-4">
                <div class="prediction-high rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-medium text-red-800">Risiko Infeksi Saluran Kemih</h4>
                        <span class="text-sm font-bold text-red-800">Tinggi (87%)</span>
                    </div>
                    <p class="text-sm text-red-700 mb-3">Berdasarkan analisis pasien UR-2025-1243 - Budi Santoso</p>
                    <div class="flex items-center">
                        <div class="w-full bg-red-200 rounded-full h-2 mr-3">
                            <div class="bg-red-600 h-2 rounded-full" style="width: 87%"></div>
                        </div>
                        <button onclick="viewPredictionDetails('isk-high')" class="text-xs text-red-800 hover:text-red-900 font-medium">Detail</button>
                    </div>
                </div>

                <div class="prediction-medium rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-medium text-orange-800">Risiko Diabetes</h4>
                        <span class="text-sm font-bold text-orange-800">Sedang (64%)</span>
                    </div>
                    <p class="text-sm text-orange-700 mb-3">Berdasarkan analisis pasien UR-2025-1244 - Siti Nurhaliza</p>
                    <div class="flex items-center">
                        <div class="w-full bg-orange-200 rounded-full h-2 mr-3">
                            <div class="bg-orange-600 h-2 rounded-full" style="width: 64%"></div>
                        </div>
                        <button onclick="viewPredictionDetails('diabetes-med')" class="text-xs text-orange-800 hover:text-orange-900 font-medium">Detail</button>
                    </div>
                </div>

                <div class="prediction-low rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-medium text-green-800">Risiko Penyakit Ginjal</h4>
                        <span class="text-sm font-bold text-green-800">Rendah (23%)</span>
                    </div>
                    <p class="text-sm text-green-700 mb-3">Berdasarkan analisis pasien UR-2025-1245 - Ahmad Pratama</p>
                    <div class="flex items-center">
                        <div class="w-full bg-green-200 rounded-full h-2 mr-3">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 23%"></div>
                        </div>
                        <button onclick="viewPredictionDetails('kidney-low')" class="text-xs text-green-800 hover:text-green-900 font-medium">Detail</button>
                    </div>
                </div>
            </div>

            <button onclick="runNewPrediction()" class="mt-6 w-full py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium">
                Jalankan Prediksi Baru
            </button>
        </div>

        <!-- Pattern Analysis -->
        <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-200">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Analisis Pola</h3>
            
            <div class="space-y-6">
                <div>
                    <h4 class="font-medium text-gray-900 mb-3">Tren Mingguan</h4>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Tes Normal</span>
                            <div class="flex items-center">
                                <div class="w-20 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: 78%"></div>
                                </div>
                                <span class="text-sm font-medium">78%</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Perlu Perhatian</span>
                            <div class="flex items-center">
                                <div class="w-20 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-yellow-500 h-2 rounded-full" style="width: 18%"></div>
                                </div>
                                <span class="text-sm font-medium">18%</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Abnormal</span>
                            <div class="flex items-center">
                                <div class="w-20 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-red-500 h-2 rounded-full" style="width: 4%"></div>
                                </div>
                                <span class="text-sm font-medium">4%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-gray-900 mb-3">Parameter Paling Sering Abnormal</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                            <span class="text-sm font-medium text-red-800">Protein</span>
                            <span class="text-sm text-red-700">42% kasus</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                            <span class="text-sm font-medium text-yellow-800">pH Level</span>
                            <span class="text-sm text-yellow-700">31% kasus</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg">
                            <span class="text-sm font-medium text-orange-800">Kekeruhan</span>
                            <span class="text-sm text-orange-700">27% kasus</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-gray-900 mb-3">Korelasi AI</h4>
                    <p class="text-sm text-gray-600 mb-3">Model AI mendeteksi pola berikut:</p>
                    <ul class="text-sm text-gray-700 space-y-1">
                        <li>• pH tinggi + protein positif → 85% kemungkinan ISK</li>
                        <li>• Warna pekat + berat jenis tinggi → 72% dehidrasi</li>
                        <li>• Glukosa positif + keton → 91% indikasi diabetes</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

   

    


@endsection

@section('scripts')
<script>
// Prediction detail data
const predictionDetails = {
    'isk-high': {
        title: 'Prediksi Infeksi Saluran Kemih - Risiko Tinggi',
        confidence: 87,
        patient: 'Budi Santoso (UR-2025-1243)',
        factors: [
            { parameter: 'pH Level', value: '8.5', impact: 'Tinggi', normal: '5.0-7.5' },
            { parameter: 'Protein', value: 'Positif', impact: 'Tinggi', normal: 'Negatif' },
            { parameter: 'Leukosit Esterase', value: 'Positif', impact: 'Sedang', normal: 'Negatif' },
            { parameter: 'Nitrit', value: 'Positif', impact: 'Tinggi', normal: 'Negatif' }
        ],
        recommendation: 'Sangat disarankan untuk rujukan ke dokter spesialis urologi. Pertimbangkan pemberian antibiotik empiris sambil menunggu hasil kultur urin.',
        riskLevel: 'high'
    },
    'diabetes-med': {
        title: 'Prediksi Diabetes - Risiko Sedang',
        confidence: 64,
        patient: 'Siti Nurhaliza (UR-2025-1244)',
        factors: [
            { parameter: 'Glukosa', value: 'Trace', impact: 'Sedang', normal: 'Negatif' },
            { parameter: 'Berat Jenis', value: '1.035', impact: 'Sedang', normal: '1.005-1.030' },
            { parameter: 'pH Level', value: '7.8', impact: 'Rendah', normal: '5.0-7.5' }
        ],
        recommendation: 'Disarankan pemeriksaan gula darah puasa dan HbA1c. Monitor gejala diabetes seperti poliuria, polidipsia, dan penurunan berat badan.',
        riskLevel: 'medium'
    },
    'kidney-low': {
        title: 'Prediksi Penyakit Ginjal - Risiko Rendah',
        confidence: 23,
        patient: 'Ahmad Pratama (UR-2025-1245)',
        factors: [
            { parameter: 'Protein', value: 'Negatif', impact: 'Rendah', normal: 'Negatif' },
            { parameter: 'Kreatinin', value: 'Normal', impact: 'Rendah', normal: 'Normal' },
            { parameter: 'Berat Jenis', value: '1.020', impact: 'Rendah', normal: '1.005-1.030' }
        ],
        recommendation: 'Kondisi ginjal dalam batas normal. Lanjutkan pola hidup sehat dan pemeriksaan rutin tahunan.',
        riskLevel: 'low'
    }
};

function viewPredictionDetails(predictionId) {
    const detail = predictionDetails[predictionId];
    if (!detail) return;
    
    const riskColors = {
        high: { bg: 'bg-red-50', text: 'text-red-800', border: 'border-red-200' },
        medium: { bg: 'bg-yellow-50', text: 'text-yellow-800', border: 'border-yellow-200' },
        low: { bg: 'bg-green-50', text: 'text-green-800', border: 'border-green-200' }
    };
    
    const colors = riskColors[detail.riskLevel];
    
    const content = `
        <div class="space-y-6">
            <div class="${colors.bg} ${colors.border} border rounded-lg p-4">
                <h4 class="${colors.text} font-medium mb-2">Tingkat Kepercayaan AI</h4>
                <div class="flex items-center">
                    <div class="w-full bg-gray-200 rounded-full h-3 mr-3">
                        <div class="h-3 rounded-full ${detail.riskLevel === 'high' ? 'bg-red-500' : detail.riskLevel === 'medium' ? 'bg-yellow-500' : 'bg-green-500'}" style="width: ${detail.confidence}%"></div>
                    </div>
                    <span class="${colors.text} font-bold">${detail.confidence}%</span>
                </div>
            </div>
            
            <div>
                <h4 class="font-medium text-gray-900 mb-3">Pasien</h4>
                <p class="text-gray-700">${detail.patient}</p>
            </div>
            
            <div>
                <h4 class="font-medium text-gray-900 mb-3">Faktor Risiko</h4>
                <div class="space-y-3">
                    ${detail.factors.map(factor => `
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex justify-between items-start mb-2">
                                <span class="font-medium text-gray-900">${factor.parameter}</span>
                                <span class="text-sm px-2 py-1 rounded-full ${
                                    factor.impact === 'Tinggi' ? 'bg-red-100 text-red-800' :
                                    factor.impact === 'Sedang' ? 'bg-yellow-100 text-yellow-800' :
                                    'bg-green-100 text-green-800'
                                }">${factor.impact}</span>
                            </div>
                            <p class="text-sm text-gray-700">Nilai: <strong>${factor.value}</strong></p>
                            <p class="text-xs text-gray-500">Normal: ${factor.normal}</p>
                        </div>
                    `).join('')}
                </div>
            </div>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-medium text-blue-900 mb-2">Rekomendasi AI</h4>
                <p class="text-sm text-blue-800">${detail.recommendation}</p>
            </div>
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
        showToast('Prediksi selesai! 3 kasus baru dianalisis', 'green');

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