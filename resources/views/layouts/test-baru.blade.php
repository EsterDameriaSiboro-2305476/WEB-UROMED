@extends('layouts.app')

@section('title', 'Tes Baru - Uromed')

@section('custom-styles')
.step-active {
    background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
    color: white;
}
.step-completed {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}
.step-inactive {
    background: #f3f4f6;
    color: #6b7280;
}
.sensor-status {
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
    100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
}
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
            <li class="text-gray-500">/</li>
            <li class="text-gray-700">Tes Baru</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tes Analisis Urin Baru</h1>
                <p class="text-gray-600 mt-2">Mulai proses analisis urin dengan teknologi IoT dan AI</p>
            </div>
            <div class="flex space-x-4">
                <button onclick="resetTest()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    Reset
                </button>
                <button onclick="saveAsDraft()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    Simpan Draft
                </button>
            </div>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div id="step1" class="step-active w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium">1</div>
                <div class="w-16 h-1 bg-gray-300 mx-2"></div>
                <div id="step2" class="step-inactive w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium">2</div>
                <div class="w-16 h-1 bg-gray-300 mx-2"></div>
                <div id="step3" class="step-inactive w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium">3</div>
                <div class="w-16 h-1 bg-gray-300 mx-2"></div>
                <div id="step4" class="step-inactive w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium">4</div>
            </div>
            <div class="text-sm text-gray-600">
                <span id="step-text">Langkah 1: Input Data Pasien</span>
            </div>
        </div>
        <div class="flex justify-between mt-2 text-xs text-gray-500">
            <span>Data Pasien</span>
            <span>Persiapan Sensor</span>
            <span>Analisis</span>
            <span>Hasil</span>
        </div>
    </div>

    <!-- Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <!-- Step 1: Patient Data -->
            <div id="patient-data" class="bg-white rounded-2xl p-8 shadow-lg border border-gray-200">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Data Pasien</h3>
                
                <form id="patient-form" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ID Pasien *</label>
                            <input type="text" id="patient-id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Masukkan ID Pasien" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                            <input type="text" id="patient-name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Nama Pasien" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Umur</label>
                            <input type="number" id="patient-age" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Umur" min="1" max="120">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                            <select id="patient-gender" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keluhan/Gejala</label>
                        <textarea id="patient-symptoms" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Deskripsikan keluhan atau gejala yang dialami pasien..."></textarea>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="button" onclick="nextStep()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Lanjut ke Persiapan Sensor
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 2: Sensor Preparation (Hidden initially) -->
            <div id="sensor-prep" class="bg-white rounded-2xl p-8 shadow-lg border border-gray-200 hidden">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Persiapan Sensor IoT</h3>
                
                <div class="space-y-6">
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h4 class="font-medium text-blue-900 mb-3">Status Sensor</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center">
                                <div class="sensor-status w-12 h-12 bg-green-500 rounded-full mx-auto mb-2 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-green-700">pH Sensor</p>
                                <p class="text-xs text-gray-600">Ready</p>
                            </div>
                            <div class="text-center">
                                <div class="sensor-status w-12 h-12 bg-green-500 rounded-full mx-auto mb-2 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-green-700">Color Sensor</p>
                                <p class="text-xs text-gray-600">Ready</p>
                            </div>
                            <div class="text-center">
                                <div class="sensor-status w-12 h-12 bg-green-500 rounded-full mx-auto mb-2 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-green-700">Turbidity</p>
                                <p class="text-xs text-gray-600">Ready</p>
                            </div>
                            <div class="text-center">
                                <div class="sensor-status w-12 h-12 bg-yellow-500 rounded-full mx-auto mb-2 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-yellow-700">Temperature</p>
                                <p class="text-xs text-gray-600">Calibrating</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Petunjuk Penggunaan</h4>
                        <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700">
                            <li>Pastikan container sampel urin bersih dan steril</li>
                            <li>Masukkan sensor ke dalam sampel dengan hati-hati</li>
                            <li>Tunggu hingga sensor menunjukkan status "Ready"</li>
                            <li>Klik tombol "Mulai Analisis" ketika semua sensor siap</li>
                        </ol>
                    </div>
                    
                    <div class="flex justify-between">
                        <button type="button" onclick="prevStep()" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                            Kembali
                        </button>
                        <button type="button" onclick="startAnalysis()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Mulai Analisis
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 3: Analysis (Hidden initially) -->
            <div id="analysis-step" class="bg-white rounded-2xl p-8 shadow-lg border border-gray-200 hidden">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Proses Analisis</h3>
                
                <div class="space-y-6">
                    <div class="text-center py-8">
                        <div class="w-24 h-24 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                        <p class="text-lg font-medium text-gray-900">Menganalisis Sampel...</p>
                        <p class="text-sm text-gray-600">Proses ini memakan waktu sekitar 2-3 menit</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div id="progress-ph" class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                            <span class="text-sm font-medium text-blue-900">Mengukur pH</span>
                            <div class="w-6 h-6 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                        </div>
                        <div id="progress-color" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">Analisis Warna</span>
                            <div class="w-6 h-6 border-2 border-gray-300 rounded-full"></div>
                        </div>
                        <div id="progress-turbidity" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">Mengukur Kekeruhan</span>
                            <div class="w-6 h-6 border-2 border-gray-300 rounded-full"></div>
                        </div>
                        <div id="progress-ai" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">Analisis AI</span>
                            <div class="w-6 h-6 border-2 border-gray-300 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4: Results (Hidden initially) -->
            <div id="results-step" class="bg-white rounded-2xl p-8 shadow-lg border border-gray-200 hidden">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Hasil Analisis</h3>
                
                <div class="space-y-6">
                    <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-medium text-green-900">Analisis Selesai</h4>
                                <p class="text-sm text-green-700">Hasil menunjukkan kondisi normal</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg text-center">
                            <p class="text-sm text-gray-600">pH Level</p>
                            <p class="text-2xl font-bold text-blue-600">6.2</p>
                            <p class="text-xs text-green-600">Normal</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg text-center">
                            <p class="text-sm text-gray-600">Warna</p>
                            <p class="text-2xl font-bold text-yellow-600">Kuning</p>
                            <p class="text-xs text-green-600">Normal</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg text-center">
                            <p class="text-sm text-gray-600">Kekeruhan</p>
                            <p class="text-2xl font-bold text-purple-600">Jernih</p>
                            <p class="text-xs text-green-600">Normal</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg text-center">
                            <p class="text-sm text-gray-600">Protein</p>
                            <p class="text-2xl font-bold text-green-600">Negatif</p>
                            <p class="text-xs text-green-600">Normal</p>
                        </div>
                    </div>
                    
                    <div class="flex justify-between">
                        <button type="button" onclick="startNewTest()" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                            Tes Baru
                        </button>
                        <div class="space-x-3">
                            <button type="button" onclick="saveResults()" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                Simpan Hasil
                            </button>
                            <button type="button" onclick="printResults()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Cetak Laporan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Test Info -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Informasi Tes</h4>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Test ID:</span>
                        <span class="font-medium" id="test-id">UR-2025-{{ rand(1000, 9999) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal:</span>
                        <span class="font-medium">{{ now()->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Waktu:</span>
                        <span class="font-medium" id="test-time">{{ now()->format('H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Operator:</span>
                        <span class="font-medium">Admin</span>
                    </div>
                </div>
            </div>

            <!-- Help -->
            <div class="bg-blue-50 rounded-2xl p-6 border border-blue-200">
                <h4 class="text-lg font-medium text-blue-900 mb-4">Bantuan</h4>
                <ul class="space-y-2 text-sm text-blue-800">
                    <li>• Pastikan sampel urin fresh (max 2 jam)</li>
                    <li>• Volume minimum 50ml untuk analisis</li>
                    <li>• Hindari kontaminasi pada sampel</li>
                    <li>• Sensor akan auto-kalibrasi setiap tes</li>
                </ul>
                <button class="mt-4 text-sm text-blue-600 hover:text-blue-800 underline">
                    Panduan Lengkap →
                </button>
            </div>

            <!-- Recent Tests -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Tes Terakhir</h4>
                <div class="space-y-3">
                    <div class="flex items-center p-3 bg-green-50 rounded-lg">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">UR-2025-1234</p>
                            <p class="text-xs text-gray-500">Normal - 10:30</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-3 bg-yellow-50 rounded-lg">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.168 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">UR-2025-1233</p>
                            <p class="text-xs text-gray-500">Perhatian - 09:15</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentStep = 1;

function nextStep() {
    if (currentStep === 1) {
        // Validate patient form
        const patientId = document.getElementById('patient-id').value;
        const patientName = document.getElementById('patient-name').value;
        
        if (!patientId || !patientName) {
            showToast('Mohon lengkapi data pasien yang diperlukan', 'red');
            return;
        }
        
        // Hide step 1, show step 2
        document.getElementById('patient-data').classList.add('hidden');
        document.getElementById('sensor-prep').classList.remove('hidden');
        
        // Update progress
        updateStepProgress(2);
        currentStep = 2;
        
        showToast('Data pasien berhasil disimpan', 'green');
    }
}

function prevStep() {
    if (currentStep === 2) {
        // Show step 1, hide step 2
        document.getElementById('patient-data').classList.remove('hidden');
        document.getElementById('sensor-prep').classList.add('hidden');
        
        // Update progress
        updateStepProgress(1);
        currentStep = 1;
    }
}

function startAnalysis() {
    // Hide step 2, show step 3
    document.getElementById('sensor-prep').classList.add('hidden');
    document.getElementById('analysis-step').classList.remove('hidden');
    
    // Update progress
    updateStepProgress(3);
    currentStep = 3;
    
    // Start analysis simulation
    simulateAnalysis();
}

function updateStepProgress(step) {
    // Reset all steps
    for (let i = 1; i <= 4; i++) {
        const stepEl = document.getElementById(`step${i}`);
        if (i < step) {
            stepEl.className = 'step-completed w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium';
        } else if (i === step) {
            stepEl.className = 'step-active w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium';
        } else {
            stepEl.className = 'step-inactive w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium';
        }
    }
    
    // Update step text
    const stepTexts = {
        1: 'Langkah 1: Input Data Pasien',
        2: 'Langkah 2: Persiapan Sensor',
        3: 'Langkah 3: Proses Analisis',
        4: 'Langkah 4: Hasil Analisis'
    };
    
    document.getElementById('step-text').textContent = stepTexts[step];
}

function simulateAnalysis() {
    const steps = ['ph', 'color', 'turbidity', 'ai'];
    let currentAnalysisStep = 0;
    
    const progressSteps = () => {
        if (currentAnalysisStep > 0) {
            // Complete previous step
            const prevStep = steps[currentAnalysisStep - 1];
            const prevEl = document.getElementById(`progress-${prevStep}`);
            prevEl.className = 'flex items-center justify-between p-4 bg-green-50 rounded-lg';
            prevEl.innerHTML = prevEl.innerHTML.replace(/animate-spin/, '').replace(/border-blue-500/, 'border-green-500').replace(/border-gray-300/, 'border-green-500');
            prevEl.querySelector('.w-6').innerHTML = '<svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            prevEl.querySelector('span').className = 'text-sm font-medium text-green-700';
        }
        
        if (currentAnalysisStep < steps.length) {
            // Start current step
            const currentStepName = steps[currentAnalysisStep];
            const currentEl = document.getElementById(`progress-${currentStepName}`);
            currentEl.className = 'flex items-center justify-between p-4 bg-blue-50 rounded-lg';
            currentEl.querySelector('span').className = 'text-sm font-medium text-blue-900';
            currentEl.querySelector('.w-6').innerHTML = '<div class="w-6 h-6 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>';
            
            currentAnalysisStep++;
            setTimeout(progressSteps, 2000); // 2 seconds per step
        } else {
            // Analysis complete, show results
            setTimeout(() => {
                document.getElementById('analysis-step').classList.add('hidden');
                document.getElementById('results-step').classList.remove('hidden');
                updateStepProgress(4);
                currentStep = 4;
                showToast('Analisis selesai! Hasil tersedia.', 'green');
            }, 1000);
        }
    };
    
    progressSteps();
}

function resetTest() {
    if (confirm('Apakah Anda yakin ingin mereset tes ini? Semua data akan hilang.')) {
        // Reset form
        document.getElementById('patient-form').reset();
        
        // Show only step 1
        document.getElementById('patient-data').classList.remove('hidden');
        document.getElementById('sensor-prep').classList.add('hidden');
        document.getElementById('analysis-step').classList.add('hidden');
        document.getElementById('results-step').classList.add('hidden');
        
        // Reset progress
        updateStepProgress(1);
        currentStep = 1;
        
        showToast('Tes direset. Mulai dari awal.', 'gray');
    }
}

function saveAsDraft() {
    const patientId = document.getElementById('patient-id').value;
    const patientName = document.getElementById('patient-name').value;
    
    if (!patientId && !patientName) {
        showToast('Tidak ada data untuk disimpan sebagai draft', 'red');
        return;
    }
    
    // Simulate saving draft
    showToast('Draft berhasil disimpan', 'blue');
}

function startNewTest() {
    if (confirm('Mulai tes baru? Data tes saat ini akan disimpan otomatis.')) {
        resetTest();
        showToast('Siap untuk tes baru', 'blue');
    }
}

function saveResults() {
    // Simulate saving results
    showToast('Hasil tes berhasil disimpan ke database', 'green');
    
    setTimeout(() => {
        window.location.href = '{{ route("hasil-tes") }}';
    }, 1500);
}

function printResults() {
    // Simulate printing
    showToast('Menyiapkan laporan untuk dicetak...', 'blue');
    
    setTimeout(() => {
        showToast('Laporan siap dicetak', 'green');
        // In real implementation, this would open print dialog or generate PDF
    }, 2000);
}

// Auto-update test time every minute
setInterval(() => {
    const now = new Date();
    document.getElementById('test-time').textContent = now.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    });
}, 60000);

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Test Baru page loaded');
    showToast('Sistem siap untuk tes baru', 'blue');
});
</script>
@endsection