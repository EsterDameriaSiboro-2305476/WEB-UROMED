@extends('layouts.app')

@section('title', 'Hasil Tes - Uromed')

@section('custom-styles')
.filter-active {
    background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
    color: white;
}
.status-normal { color: #059669; background-color: #d1fae5; }
.status-perhatian { color: #d97706; background-color: #fef3c7; }
.status-abnormal { color: #dc2626; background-color: #fee2e2; }
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
            <li class="text-gray-700">Hasil Tes</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Hasil Tes Analisis Urin</h1>
            <p class="text-gray-600 mt-2">Kelola dan analisis semua hasil tes yang telah dilakukan</p>
        </div>
        <div class="flex space-x-4">
            <button onclick="exportAllData()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Excel
            </button>
            <a href="{{ route('test-baru') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tes Baru
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Tes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
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
                    <p class="text-sm text-gray-600">Normal</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['normal'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.168 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Perhatian</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['perhatian'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Abnormal</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['abnormal'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200 mb-8">
        <form method="GET" action="{{ route('hasil-tes') }}" id="filter-form">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                <!-- Search -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Cari berdasarkan ID atau nama pasien..." class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex items-center space-x-4">
                    <select name="status" id="status-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="document.getElementById('filter-form').submit()">
                        <option value="">Semua Status</option>
                        <option value="Normal" {{ request('status') == 'Normal' ? 'selected' : '' }}>Normal</option>
                        <option value="Perhatian" {{ request('status') == 'Perhatian' ? 'selected' : '' }}>Perhatian</option>
                        <option value="Abnormal" {{ request('status') == 'Abnormal' ? 'selected' : '' }}>Abnormal</option>
                    </select>
                    
                    <select name="date_filter" id="date-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="document.getElementById('filter-form').submit()">
                        <option value="all" {{ request('date_filter') == 'all' ? 'selected' : '' }}>Semua Waktu</option>
                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                    </select>
                    
                    @if(request('search') || request('status') || request('date_filter'))
                    <a href="{{ route('hasil-tes') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Reset
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Results Table -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Daftar Hasil Tes</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full" id="results-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Test ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">pH</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Protein</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="results-tbody">
                    @forelse($urineTests as $test)
                    <tr class="hover:bg-gray-50 transition-colors test-row" data-status="{{ strtolower($test->result_status) }}" data-test-id="{{ $test->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $test->test_id }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $test->patient->name }}</div>
                                <div class="text-sm text-gray-500">ID: {{ $test->patient->patient_id }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $test->test_date->format('d/m/Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $test->test_date->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $test->ph_level ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $test->protein ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="status-{{ strtolower($test->result_status) }} px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                {{ $test->result_status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button onclick="viewDetails({{ $test->id }})" class="text-blue-600 hover:text-blue-900">Lihat</button>
                            <button onclick="printReport({{ $test->id }})" class="text-green-600 hover:text-green-900">Cetak</button>
                            <button onclick="deleteTest({{ $test->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Belum ada data hasil tes</p>
                            <a href="{{ route('test-baru') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Buat Tes Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($urineTests->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Menampilkan <span class="font-medium">{{ $urineTests->firstItem() ?? 0 }}</span> 
                    sampai <span class="font-medium">{{ $urineTests->lastItem() ?? 0 }}</span> 
                    dari <span class="font-medium">{{ $urineTests->total() }}</span> hasil
                </div>
                <div>
                    {{ $urineTests->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Detail Modal -->
<div id="detail-modal" class="modal fixed inset-0 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900" id="modal-title">Detail Hasil Tes</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="p-6" id="modal-content">
                <div class="flex justify-center items-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

async function viewDetails(testId) {
    document.getElementById('detail-modal').classList.remove('hidden');
    document.getElementById('modal-content').innerHTML = '<div class="flex justify-center items-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div></div>';
    
    try {
        const response = await fetch(`/api/urine-tests/${testId}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            const test = result.data;
            
            const modalContent = `
                <div class="space-y-6">
                    <!-- Patient Info -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Informasi Pasien</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Nama Pasien</p>
                                <p class="font-medium">${test.patient.name}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">ID Pasien</p>
                                <p class="font-medium">${test.patient.patient_id}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Tes</p>
                                <p class="font-medium">${new Date(test.test_date).toLocaleDateString('id-ID')}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Waktu Tes</p>
                                <p class="font-medium">${new Date(test.test_date).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Test Results -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Hasil Analisis</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            ${generateResultCard('pH Level', test.ph_level, test.ph_status, '5.0-7.5')}
                            ${generateResultCard('Protein', test.protein, test.protein_status, 'Negatif')}
                            ${generateResultCard('Glukosa', test.glucose, test.glucose_status, 'Negatif')}
                            ${generateResultCard('Keton', test.ketones, test.ketones_status, 'Negatif')}
                            ${generateResultCard('Darah', test.blood, test.blood_status, 'Negatif')}
                            ${generateResultCard('Warna', test.color, test.color_status, 'Kuning')}
                            ${generateResultCard('Kejernihan', test.clarity, test.clarity_status, 'Jernih')}
                            ${generateResultCard('Berat Jenis', test.specific_gravity, test.gravity_status, '1.005-1.030')}
                        </div>
                    </div>
                    
                    <!-- Overall Status -->
                    <div class="bg-${getStatusColor(test.result_status)}-50 p-6 rounded-lg border border-${getStatusColor(test.result_status)}-200">
                        <h4 class="text-lg font-medium text-${getStatusColor(test.result_status)}-900 mb-2">Status Keseluruhan</h4>
                        <p class="text-${getStatusColor(test.result_status)}-800">${test.result_status}</p>
                        ${test.notes ? `<p class="mt-2 text-sm text-${getStatusColor(test.result_status)}-700">${test.notes}</p>` : ''}
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex justify-end space-x-3">
                        <button onclick="printReport(${testId})" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Cetak Laporan
                        </button>
                        <button onclick="exportToPDF(${testId})" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Export PDF
                        </button>
                    </div>
                </div>
            `;
            
            document.getElementById('modal-title').textContent = `Detail Hasil Tes - ${test.test_id}`;
            document.getElementById('modal-content').innerHTML = modalContent;
        } else {
            throw new Error('Data tidak ditemukan');
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('modal-content').innerHTML = `
            <div class="text-center py-8">
                <p class="text-red-600">Gagal memuat detail tes</p>
                <button onclick="closeModal()" class="mt-4 px-4 py-2 bg-gray-500 text-white rounded-lg">Tutup</button>
            </div>
        `;
    }
}

function generateResultCard(label, value, status, normalRange) {
    const statusClass = getStatusClass(status);
    return `
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
                <h5 class="font-medium text-gray-900">${label}</h5>
                <span class="status-${statusClass} px-2 py-1 text-xs font-semibold rounded-full">
                    ${status || 'Normal'}
                </span>
            </div>
            <p class="text-lg font-bold text-gray-900">${value || '-'}</p>
            <p class="text-sm text-gray-500">Range Normal: ${normalRange}</p>
        </div>
    `;
}

function getStatusClass(status) {
    if (!status) return 'normal';
    const statusLower = status.toLowerCase();
    if (statusLower.includes('normal')) return 'normal';
    if (statusLower.includes('perhatian') || statusLower.includes('tinggi') || statusLower.includes('rendah')) return 'perhatian';
    if (statusLower.includes('abnormal') || statusLower.includes('positif')) return 'abnormal';
    return 'normal';
}

function getStatusColor(status) {
    if (!status) return 'green';
    const statusLower = status.toLowerCase();
    if (statusLower.includes('normal')) return 'green';
    if (statusLower.includes('perhatian')) return 'yellow';
    if (statusLower.includes('abnormal')) return 'red';
    return 'green';
}

function closeModal() {
    document.getElementById('detail-modal').classList.add('hidden');
}

function printReport(testId) {
    showToast('Menyiapkan laporan untuk dicetak...', 'blue');
    window.open(`/urine-tests/${testId}/print`, '_blank');
}

function exportToPDF(testId) {
    showToast('Mengekspor ke PDF...', 'blue');
    window.location.href = `/urine-tests/${testId}/export-pdf`;
}

async function deleteTest(testId) {
    if (!confirm('Apakah Anda yakin ingin menghapus tes ini? Tindakan ini tidak dapat dibatalkan.')) {
        return;
    }
    
    try {
        const response = await fetch(`/api/urine-tests/${testId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            const row = document.querySelector(`tr[data-test-id="${testId}"]`);
            if (row) {
                row.style.opacity = '0.5';
                setTimeout(() => {
                    row.remove();
                    showToast('Tes berhasil dihapus', 'green');
                    setTimeout(() => window.location.reload(), 1000);
                }, 500);
            }
        } else {
            throw new Error(result.message || 'Gagal menghapus tes');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Gagal menghapus tes', 'red');
    }
}

function exportAllData() {
    showToast('Mengekspor semua data ke Excel...', 'green');
    window.location.href = '/urine-tests/export-excel';
}

// Search with debounce
let searchTimeout;
document.getElementById('search-input')?.addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('filter-form').submit();
    }, 500);
});

// Close modal when clicking outside
document.getElementById('detail-modal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Hasil Tes page loaded');
    const totalTests = {{ $stats['total'] ?? 0 }};
    if (totalTests > 0) {
        showToast(`${totalTests} hasil tes dimuat`, 'green', 2000);
    }
});
</script>
@endsection