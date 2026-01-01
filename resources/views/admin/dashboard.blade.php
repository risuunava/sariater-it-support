@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')
@section('page-description', 'Monitor dan kelola semua laporan IT Support')

@section('header-actions')
<div class="flex items-center space-x-3">
    <div class="relative">
        <form action="{{ route('admin.dashboard') }}" method="GET" id="search-form">
            <input type="text" 
                   name="search" 
                   placeholder="Cari laporan..." 
                   value="{{ request('search') }}"
                   class="input-modern pl-10 pr-4 py-2 w-64"
                   onkeypress="if(event.keyCode==13) document.getElementById('search-form').submit()">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </form>
    </div>
    <a href="{{ route('admin.tickets.export') }}?{{ http_build_query(request()->query()) }}" 
       class="btn-secondary">
        <i class="fas fa-download mr-2"></i>Export
    </a>
</div>
@endsection

@section('content')
<!-- Filter Section -->
<div class="mb-6">
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <h3 class="font-heading font-semibold text-gray-900">Filter Dashboard</h3>
            <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-wrap gap-3">
                <!-- Period Filter -->
                <div>
                    <select name="period" onchange="this.form.submit()" class="input-modern py-2">
                        <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="7days" {{ request('period') == '7days' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="30days" {{ request('period') == '30days' || !request('period') ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                    </select>
                </div>
                
                <!-- Date Range -->
                <div class="flex items-center space-x-2">
                    <input type="date" 
                           name="date_from" 
                           value="{{ request('date_from') }}"
                           class="input-modern py-2"
                           onchange="this.form.submit()">
                    <span class="text-gray-500">s/d</span>
                    <input type="date" 
                           name="date_to" 
                           value="{{ request('date_to') }}"
                           class="input-modern py-2"
                           onchange="this.form.submit()">
                </div>
                
                <!-- Reset Button -->
                <a href="{{ route('admin.dashboard') }}" class="btn-secondary py-2">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </form>
        </div>
    </div>
</div>

<div class="space-y-8">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Laporan</p>
                    <h3 class="text-3xl font-bold mt-1">{{ $statistics['total'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-ticket-alt text-2xl"></i>
                </div>
            </div>
            <div class="flex items-center text-blue-100 text-sm">
                <i class="fas fa-chart-line mr-1"></i>
                <span>Semua periode</span>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl shadow-lg p-6 text-white hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Pending</p>
                    <h3 class="text-3xl font-bold mt-1">{{ $statistics['pending'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
            <div class="text-yellow-100 text-sm">
                <span>Perlu tindakan segera</span>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-green-100 text-sm font-medium">Selesai</p>
                    <h3 class="text-3xl font-bold mt-1">{{ $statistics['done'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
            <div class="flex items-center text-green-100 text-sm">
                <i class="fas fa-chart-line mr-1"></i>
                <span>Completion: {{ $systemMetrics['completion_rate'] }}%</span>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Rata-rata Waktu</p>
                    <h3 class="text-3xl font-bold mt-1">{{ round($avgResolutionTime) }}</h3>
                </div>
                <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-stopwatch text-2xl"></i>
                </div>
            </div>
            <div class="text-purple-100 text-sm">
                <span>Jam per penyelesaian</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Main Chart -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-heading text-lg font-bold text-gray-900">Trend Laporan</h3>
                    <p class="text-sm text-gray-500 mt-1">Jumlah laporan per status</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-3 py-1 text-xs bg-blue-50 text-blue-600 rounded-lg">{{ ucfirst(request('period', '30days')) }}</button>
                </div>
            </div>
            
            <!-- Chart Container -->
            <div class="h-80">
                <canvas id="ticketsChart"></canvas>
            </div>
        </div>
        
        <!-- Performance Metrics -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-heading text-lg font-bold text-gray-900">Performance Metrics</h3>
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                    {{ $systemMetrics['completion_rate'] }}% Completion
                </span>
            </div>
            
            <div class="space-y-6">
                <!-- Resolution Time -->
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Avg. Resolution Time</span>
                        <span class="font-medium text-gray-900">{{ round($avgResolutionTime) }} jam</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-full rounded-full bg-gradient-to-r from-green-400 to-emerald-500" 
                             style="width: {{ min($avgResolutionTime / 24 * 100, 100) }}%"></div>
                    </div>
                </div>
                
                <!-- Tickets Today -->
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Laporan Hari Ini</span>
                        <span class="font-medium text-gray-900">{{ $systemMetrics['tickets_today'] }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-full rounded-full bg-gradient-to-r from-blue-400 to-blue-500" 
                             style="width: {{ min($systemMetrics['tickets_today'] / 50 * 100, 100) }}%"></div>
                    </div>
                </div>
                
                <!-- User Activity -->
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Total Pengguna</span>
                        <span class="font-medium text-gray-900">{{ $systemMetrics['total_users'] }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-full rounded-full bg-gradient-to-r from-purple-400 to-purple-500" 
                             style="width: {{ min($systemMetrics['total_users'] / 100 * 100, 100) }}%"></div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="mt-8 pt-6 border-t border-gray-100">
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['pending'] }}</p>
                        <p class="text-xs text-gray-500">Pending</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['progress'] }}</p>
                        <p class="text-xs text-gray-500">Progress</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['done'] }}</p>
                        <p class="text-xs text-gray-500">Selesai</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tickets & Top Technicians -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Tickets -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h2 class="font-heading text-xl font-bold text-gray-900">Laporan Terbaru</h2>
                        <p class="text-sm text-gray-500 mt-1">10 laporan terakhir dari semua user</p>
                    </div>
                    <a href="{{ route('admin.tickets') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                        Lihat semua <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelapor</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Teknisi</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($recentTickets as $ticket)
                            <tr class="hover:bg-gray-50/50 transition">
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
                                    <div class="text-xs text-gray-500">{{ Str::limit($ticket->description, 40) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge {{ $ticket->getStatusColor() }} shadow-sm">
                                        {{ $ticket->getStatusText() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $ticket->technician ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.ticket.show', $ticket->id) }}" 
                                       class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Top Technicians -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-heading font-bold text-gray-900">Top Technicians</h3>
                    <span class="text-sm text-gray-500">30 days</span>
                </div>
                
                <div class="space-y-4">
                    @foreach($topTechnicians as $tech)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                <i class="fas fa-user-cog text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $tech->technician }}</p>
                                <p class="text-xs text-gray-500">{{ $tech->completed }}/{{ $tech->total_tickets }} selesai</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">{{ $tech->total_tickets }}</p>
                            <p class="text-xs text-gray-500">laporan</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if($topTechnicians->isEmpty())
                <div class="text-center py-4">
                    <p class="text-gray-500">Belum ada data teknisi</p>
                </div>
                @endif
            </div>
            
            <!-- Quick Links -->
            <div class="bg-gradient-to-br from-gray-900 to-blue-900 rounded-2xl shadow-lg p-6 text-white">
                <h3 class="font-heading font-bold text-lg mb-4">Quick Links</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.tickets') }}?status=pending" 
                       class="flex items-center justify-between p-3 rounded-xl bg-white/10 hover:bg-white/20 transition">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>Pending Tickets</span>
                        </div>
                        <span class="bg-white/20 px-2 py-1 rounded text-xs">{{ $statistics['pending'] }}</span>
                    </a>
                    
                    <a href="{{ route('admin.analytics') }}" 
                       class="flex items-center justify-between p-3 rounded-xl bg-white/10 hover:bg-white/20 transition">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-chart-bar"></i>
                            <span>Analytics</span>
                        </div>
                        <i class="fas fa-chevron-right text-sm"></i>
                    </a>
                    
                    <a href="{{ route('admin.reports') }}" 
                       class="flex items-center justify-between p-3 rounded-xl bg-white/10 hover:bg-white/20 transition">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-file-alt"></i>
                            <span>Reports</span>
                        </div>
                        <i class="fas fa-chevron-right text-sm"></i>
                    </a>
                    
                    <a href="{{ route('admin.users') }}" 
                       class="flex items-center justify-between p-3 rounded-xl bg-white/10 hover:bg-white/20 transition">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-users"></i>
                            <span>Users</span>
                        </div>
                        <span class="bg-white/20 px-2 py-1 rounded text-xs">{{ $systemMetrics['total_users'] }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Urgent Tickets -->
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-white"></i>
                </div>
                <div>
                    <h2 class="font-heading text-xl font-bold text-gray-900">Laporan Mendesak</h2>
                    <p class="text-sm text-gray-500 mt-1">Pending lebih dari 24 jam</p>
                </div>
            </div>
            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                {{ $urgentTickets->count() }} urgent
            </span>
        </div>
        
        @if($urgentTickets->isEmpty())
        <div class="text-center py-12">
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-green-500 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak ada laporan mendesak</h3>
            <p class="text-gray-500">Semua laporan sedang dalam kondisi baik</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-red-50">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-red-800 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-red-800 uppercase tracking-wider">Pelapor</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-red-800 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-red-800 uppercase tracking-wider">Durasi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-red-800 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-red-100">
                    @foreach($urgentTickets as $ticket)
                    <tr class="hover:bg-red-50/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-mono font-medium text-gray-900">
                                #{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center text-white font-medium text-xs">
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
                            <div class="text-xs text-red-600">
                                <i class="far fa-clock mr-1"></i>
                                {{ $ticket->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                                {{ $ticket->created_at->diffInHours(now()) }} jam
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.ticket.show', $ticket->id) }}" 
                               class="px-3 py-1 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                                Tangani Sekarang
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('ticketsChart').getContext('2d');
    
    // Data from PHP
    const chartData = @json($chartData);
    
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [
                {
                    label: 'Pending',
                    data: chartData.pending,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Progress',
                    data: chartData.progress,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Selesai',
                    data: chartData.done,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'nearest'
            }
        }
    });
});
</script>

<!-- Custom CSS for Status Badges -->
<style>
.status-badge.pending {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
    border: 1px solid #fbbf24;
}

.status-badge.progress {
    background: linear-gradient(135deg, #dbeafe 0%, #93c5fd 100%);
    color: #1e40af;
    border: 1px solid #3b82f6;
}

.status-badge.done {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    border: 1px solid #10b981;
}
</style>
@endsection