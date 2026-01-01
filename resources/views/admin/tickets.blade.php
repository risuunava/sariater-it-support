@extends('layouts.app')

@section('title', 'Kelola Laporan')
@section('page-title', 'Kelola Laporan')
@section('page-description', 'Kelola semua laporan IT Support dengan filter dan pencarian')

@section('header-actions')
<div class="flex items-center space-x-3">
    <a href="{{ route('admin.tickets.export') }}?{{ http_build_query(request()->query()) }}" 
       class="btn-secondary">
        <i class="fas fa-download mr-2"></i>Export CSV
    </a>
    <a href="{{ route('admin.dashboard') }}" class="btn-secondary">
        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
    </a>
</div>
@endsection

@section('content')
<!-- Advanced Filter Section -->
<div class="mb-6">
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-heading font-semibold text-gray-900">Filter Lanjutan</h3>
            <a href="{{ route('admin.tickets') }}" class="text-sm text-blue-600 hover:text-blue-800">
                <i class="fas fa-redo mr-1"></i>Reset Filter
            </a>
        </div>
        
        <form action="{{ route('admin.tickets') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Cari judul, deskripsi, pelapor..."
                               class="input-modern pl-10 w-full">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="input-modern w-full">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="progress" {{ request('status') == 'progress' ? 'selected' : '' }}>Progress</option>
                        <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                
                <!-- Technician Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teknisi</label>
                    <select name="technician" class="input-modern w-full">
                        <option value="">Semua Teknisi</option>
                        @foreach($technicians as $tech)
                        <option value="{{ $tech }}" {{ request('technician') == $tech ? 'selected' : '' }}>
                            {{ $tech }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Sort -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Urutkan</label>
                    <select name="sort" class="input-modern w-full">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>Status</option>
                        <option value="technician" {{ request('sort') == 'technician' ? 'selected' : '' }}>Teknisi</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dari</label>
                    <input type="date" 
                           name="date_from" 
                           value="{{ request('date_from') }}"
                           class="input-modern w-full">
                </div>
                
                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Sampai</label>
                    <input type="date" 
                           name="date_to" 
                           value="{{ request('date_to') }}"
                           class="input-modern w-full">
                </div>
                
                <!-- Items Per Page -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Items per Halaman</label>
                    <select name="per_page" class="input-modern w-full">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-filter mr-2"></i>Terapkan Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Stats Summary -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-blue-700 font-medium">Total</p>
                <p class="text-2xl font-bold text-gray-900">{{ $filterStats['total'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center">
                <i class="fas fa-ticket-alt text-white"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl p-4 border border-yellow-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-yellow-700 font-medium">Pending</p>
                <p class="text-2xl font-bold text-gray-900">{{ $filterStats['pending'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-yellow-500 flex items-center justify-center">
                <i class="fas fa-clock text-white"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-blue-700 font-medium">Progress</p>
                <p class="text-2xl font-bold text-gray-900">{{ $filterStats['progress'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center">
                <i class="fas fa-cogs text-white"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-green-700 font-medium">Selesai</p>
                <p class="text-2xl font-bold text-gray-900">{{ $filterStats['done'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-green-500 flex items-center justify-center">
                <i class="fas fa-check-circle text-white"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tickets Table -->
<div class="bg-white rounded-2xl shadow-soft overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-heading text-xl font-bold text-gray-900">Daftar Laporan</h2>
            <p class="text-sm text-gray-500 mt-1">
                Menampilkan {{ $tickets->firstItem() }} - {{ $tickets->lastItem() }} dari {{ $tickets->total() }} laporan
                @if(request()->hasAny(['search', 'status', 'technician', 'date_from', 'date_to']))
                (setelah filter)
                @endif
            </p>
        </div>
        
        <div class="flex items-center space-x-3">
            <div class="text-sm text-gray-500">
                Halaman {{ $tickets->currentPage() }} dari {{ $tickets->lastPage() }}
            </div>
        </div>
    </div>
    
    @if($tickets->isEmpty())
    <div class="text-center py-12">
        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-inbox text-blue-400 text-2xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak ada laporan</h3>
        <p class="text-gray-500 mb-4">
            @if(request()->hasAny(['search', 'status', 'technician', 'date_from', 'date_to']))
            Tidak ada laporan yang sesuai dengan filter Anda
            @else
            Belum ada laporan yang dibuat
            @endif
        </p>
        @if(request()->hasAny(['search', 'status', 'technician', 'date_from', 'date_to']))
        <a href="{{ route('admin.tickets') }}" class="btn-primary inline-flex items-center">
            <i class="fas fa-redo mr-2"></i> Reset Filter
        </a>
        @endif
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="flex items-center space-x-1">
                            <span>ID</span>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => request('sort') == 'newest' ? 'oldest' : 'newest']) }}" 
                               class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-sort"></i>
                            </a>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Pelapor
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Judul Laporan
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="flex items-center space-x-1">
                            <span>Status</span>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'status']) }}" 
                               class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-sort"></i>
                            </a>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="flex items-center space-x-1">
                            <span>Teknisi</span>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'technician']) }}" 
                               class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-sort"></i>
                            </a>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($tickets as $ticket)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-mono font-medium text-gray-900">
                            #{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-medium text-xs">
                                {{ substr($ticket->user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $ticket->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $ticket->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900 truncate max-w-xs">{{ $ticket->title }}</div>
                        <div class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($ticket->description, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="status-badge {{ $ticket->getStatusColor() }} shadow-sm">
                            {{ $ticket->getStatusText() }}
                        </span>
                        @if($ticket->status == 'pending' && $ticket->created_at->diffInHours(now()) > 24)
                        <span class="ml-2 text-xs text-red-600 font-medium" title="Pending lebih dari 24 jam">
                            <i class="fas fa-exclamation-triangle"></i>
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            @if($ticket->technician)
                            <div class="flex items-center space-x-2">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white text-xs">
                                    <i class="fas fa-user-cog"></i>
                                </div>
                                <span>{{ $ticket->technician }}</span>
                            </div>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $ticket->created_at->format('d M Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $ticket->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.ticket.show', $ticket->id) }}" 
                               class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition"
                               title="Kelola">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <a href="{{ route('karyawan.tickets.show', $ticket->id) }}" 
                               target="_blank"
                               class="w-8 h-8 rounded-lg bg-gray-50 text-gray-600 flex items-center justify-center hover:bg-gray-100 transition"
                               title="Lihat sebagai user">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="text-sm text-gray-500">
                Menampilkan {{ $tickets->firstItem() }} - {{ $tickets->lastItem() }} dari {{ $tickets->total() }} laporan
            </div>
            
            <div class="flex items-center space-x-2">
                {{ $tickets->appends(request()->query())->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Custom Pagination Styles -->
<style>
.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
}

.page-item {
    margin: 0 2px;
}

.page-link {
    display: block;
    padding: 8px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    color: #374151;
    text-decoration: none;
    transition: all 0.2s;
}

.page-link:hover {
    background-color: #f3f4f6;
    border-color: #d1d5db;
}

.page-item.active .page-link {
    background-color: #3b82f6;
    border-color: #3b82f6;
    color: white;
}

.page-item.disabled .page-link {
    color: #9ca3af;
    cursor: not-allowed;
    background-color: #f9fafb;
}
</style>

<!-- Create Pagination View -->
@if(!View::exists('vendor.pagination.tailwind'))
<div style="display: none;">
    <!-- This is for reference only, Laravel will use its default -->
</div>
@endif

<script>
// Auto-submit form on select change
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('select[name="status"], select[name="technician"], select[name="sort"], select[name="per_page"]');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>
@endsection