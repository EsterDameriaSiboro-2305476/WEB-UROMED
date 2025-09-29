@extends('layouts.app')

@section('title', 'Data Pasien - Uromed')

@section('custom-styles')
.patient-card {
    transition: all 0.3s ease;
}
.patient-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.15);
}
.status-active { background-color: #d1fae5; color: #065f46; }
.status-inactive { background-color: #fee2e2; color: #991b1b; }
.modal {
    backdrop-filter: blur(4px);
    background-color: rgba(0, 0, 0, 0.5);
}
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
            <li class="text-gray-500">/</li>
            <li class="text-gray-700">Data Pasien</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Data Pasien</h1>
            <p class="text-gray-600 mt-2">Kelola informasi dan riwayat medis pasien dengan aman</p>
        </div>
        <div class="flex space-x-4">
            <button onclick="exportPatientData()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Data
            </button>
            <button onclick="addNewPatient()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Pasien
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Pasien</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pasien Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pasien Baru (Bulan Ini)</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['newThisMonth'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Tes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['totalTests'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200 mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
            <!-- Search -->
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <input type="text" id="search-patients" placeholder="Cari berdasarkan nama, ID, atau nomor telepon..." class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex items-center space-x-4">
                <select id="status-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="all">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Tidak Aktif</option>
                </select>
                
                <select id="gender-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="all">Semua Jenis Kelamin</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
                
                <select id="age-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="all">Semua Usia</option>
                    <option value="child">Anak (0-17)</option>
                    <option value="adult">Dewasa (18-59)</option>
                    <option value="elderly">Lansia (60+)</option>
                </select>
            </div>
        </div>
    </div>


    </div></div>
                <div>
                    <p class="text-xs text-gray-500">Tes Terakhir</p>
                    <p class="font-medium text-gray-900">29/09/2025</p>
                </div>
            </div>
            
            <div class="flex space-x-2">
                <button onclick="viewPatientDetail('PAT-2025-156')" class="flex-1 bg-blue-50 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors">
                    Lihat Detail
                </button>
                <button onclick="editPatient('PAT-2025-156')" class="bg-gray-50 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </button>
                <button onclick="deletePatient('PAT-2025-156')" class="bg-red-50 text-red-700 px-3 py-2 rounded-lg text-sm font-medium hover:bg-red-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200 flex items-center justify-between">
        <div class="text-sm text-gray-500">
            Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">4</span> dari <span class="font-medium">1,247</span> pasien
        </div>
        <div class="flex space-x-2">
            <button class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50 transition-colors">
                Sebelumnya
            </button>
            <button class="px-3 py-1 bg-blue-600 text-white rounded text-sm">1</button>
            <button class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50 transition-colors">2</button>
            <button class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50 transition-colors">3</button>
            <button class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50 transition-colors">
                Selanjutnya
            </button>
        </div>
    </div>
</div>

<!-- Patient Detail Modal -->
<div id="patient-modal" class="modal fixed inset-0 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900" id="patient-modal-title">Detail Pasien</h3>
                    <button onclick="closePatientModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="p-6" id="patient-modal-content">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Patient Modal -->
<div id="add-patient-modal" class="modal fixed inset-0 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900" id="add-patient-title">Tambah Pasien Baru</h3>
                    <button onclick="closeAddPatientModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <form id="patient-form" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" id="patient-name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ID Pasien</label>
                        <input type="text" id="patient-id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-100" readonly>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir *</label>
                        <input type="date" id="patient-birthdate" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin *</label>
                        <select id="patient-gender" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="tel" id="patient-phone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="patient-email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <textarea id="patient-address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>
                
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Riwayat Medis</label>
                    <textarea id="patient-history" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Riwayat penyakit, alergi, atau informasi medis penting lainnya"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3 mt-8">
                    <button type="button" onclick="closeAddPatientModal()" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Simpan Pasien
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Get CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// Tambah pasien baru - buka modal
function addNewPatient() {
    document.getElementById('patient-form').reset();
    document.getElementById('add-patient-title').textContent = 'Tambah Pasien Baru';
    document.getElementById('patient-id').value = 'Auto-generated';
    document.getElementById('add-patient-modal').classList.remove('hidden');
}

// Tutup modal tambah/edit
function closeAddPatientModal() {
    document.getElementById('add-patient-modal').classList.add('hidden');
}

// Submit form pasien baru/edit
document.getElementById('patient-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = {
        name: document.getElementById('patient-name').value,
        birthdate: document.getElementById('patient-birthdate').value,
        gender: document.getElementById('patient-gender').value,
        phone: document.getElementById('patient-phone').value,
        email: document.getElementById('patient-email').value,
        address: document.getElementById('patient-address').value,
        medical_history: document.getElementById('patient-history').value,
    };
    
    try {
        const response = await fetch('{{ route("patients.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            alert('✅ ' + result.message);
            closeAddPatientModal();
            window.location.reload(); // Reload untuk refresh data
        } else {
            alert('❌ Gagal menambahkan pasien');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Terjadi kesalahan saat menyimpan data');
    }
});

// Lihat detail pasien
async function viewPatientDetail(patientId) {
    try {
        const response = await fetch(`/patients/${patientId}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            const patient = result.data;
            
            // Build detail HTML
            const detailHtml = `
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-3">Informasi Pribadi</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">ID Pasien:</span>
                                    <span class="font-medium">${patient.patient_id}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Nama:</span>
                                    <span class="font-medium">${patient.name}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Umur:</span>
                                    <span class="font-medium">${patient.age} tahun</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Jenis Kelamin:</span>
                                    <span class="font-medium">${patient.gender === 'L' ? 'Laki-laki' : 'Perempuan'}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Telepon:</span>
                                    <span class="font-medium">${patient.phone || '-'}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Email:</span>
                                    <span class="font-medium">${patient.email || '-'}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-2">Alamat</h4>
                            <p class="text-sm text-gray-700">${patient.address || '-'}</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-2">Riwayat Medis</h4>
                            <p class="text-sm text-gray-700">${patient.medical_history || 'Tidak ada riwayat medis'}</p>
                        </div>
                    </div>
                    
                    <div>
                        <div class="bg-blue-50 p-4 rounded-lg mb-4">
                            <h4 class="font-medium text-blue-900 mb-3">Statistik Tes</h4>
                            <div class="grid grid-cols-2 gap-4 text-center">
                                <div>
                                    <p class="text-2xl font-bold text-blue-600">${patient.total_tests}</p>
                                    <p class="text-sm text-blue-700">Total Tes</p>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-green-600">${patient.urine_tests ? patient.urine_tests.length : 0}</p>
                                    <p class="text-sm text-green-700">Riwayat Tes</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button onclick="editPatient(${patient.id}); closePatientModal();" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Edit Data
                            </button>
                            <button onclick="window.location.href='{{ route("test-baru") }}?patient=${patient.id}'" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                Tes Baru
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('patient-modal-title').textContent = `Detail Pasien - ${patient.name}`;
            document.getElementById('patient-modal-content').innerHTML = detailHtml;
            document.getElementById('patient-modal').classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Gagal memuat detail pasien');
    }
}

// Edit pasien
async function editPatient(patientId) {
    try {
        const response = await fetch(`/patients/${patientId}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            const patient = result.data;
            
            // Isi form dengan data pasien
            document.getElementById('patient-name').value = patient.name;
            document.getElementById('patient-id').value = patient.patient_id;
            document.getElementById('patient-birthdate').value = patient.birthdate;
            document.getElementById('patient-gender').value = patient.gender;
            document.getElementById('patient-phone').value = patient.phone || '';
            document.getElementById('patient-email').value = patient.email || '';
            document.getElementById('patient-address').value = patient.address || '';
            document.getElementById('patient-history').value = patient.medical_history || '';
            
            // Set form untuk update
            document.getElementById('add-patient-title').textContent = 'Edit Data Pasien';
            document.getElementById('patient-form').dataset.patientId = patient.id;
            document.getElementById('patient-form').dataset.mode = 'edit';
            
            document.getElementById('add-patient-modal').classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Gagal memuat data pasien');
    }
}

// Hapus pasien
async function deletePatient(patientId) {
    if (!confirm('Apakah Anda yakin ingin menghapus pasien ini? Tindakan ini tidak dapat dibatalkan.')) {
        return;
    }
    
    try {
        const response = await fetch(`/patients/${patientId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            alert('✅ ' + result.message);
            window.location.reload();
        } else {
            alert('❌ Gagal menghapus pasien');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Terjadi kesalahan saat menghapus data');
    }
}

// Tutup modal detail
function closePatientModal() {
    document.getElementById('patient-modal').classList.add('hidden');
}

// Export data
function exportPatientData() {
    alert('Fitur export akan segera tersedia');
}

// Close modal saat klik di luar
document.getElementById('patient-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closePatientModal();
});

document.getElementById('add-patient-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeAddPatientModal();
});

// Search functionality
document.getElementById('search-patients')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const patientCards = document.querySelectorAll('.patient-card');
    
    patientCards.forEach(card => {
        const name = card.querySelector('h3').textContent.toLowerCase();
        const id = card.querySelector('.text-sm.text-gray-500').textContent.toLowerCase();
        
        if (name.includes(searchTerm) || id.includes(searchTerm)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
});

// Filter functionality
function filterPatients() {
    const statusFilter = document.getElementById('status-filter').value;
    const genderFilter = document.getElementById('gender-filter').value;
    const ageFilter = document.getElementById('age-filter').value;
    
    const patientCards = document.querySelectorAll('.patient-card');
    
    patientCards.forEach(card => {
        let show = true;
        
        if (statusFilter !== 'all' && card.dataset.status !== statusFilter) show = false;
        if (genderFilter !== 'all' && card.dataset.gender !== genderFilter) show = false;
        
        if (ageFilter !== 'all') {
            const age = parseInt(card.dataset.age);
            if (ageFilter === 'child' && age >= 18) show = false;
            if (ageFilter === 'adult' && (age < 18 || age >= 60)) show = false;
            if (ageFilter === 'elderly' && age < 60) show = false;
        }
        
        card.style.display = show ? '' : 'none';
    });
}

document.getElementById('status-filter')?.addEventListener('change', filterPatients);
document.getElementById('gender-filter')?.addEventListener('change', filterPatients);
document.getElementById('age-filter')?.addEventListener('change', filterPatients);
</script>
@endsection