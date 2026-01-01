@extends('layouts.app')

@section('title', 'Dashboard Karyawan')
@section('page-title', 'Dashboard Saya')
@section('page-description', 'Kelola semua laporan dan masalah IT Anda')

@section('header-actions')
<div class="flex items-center space-x-3">
    <a href="{{ route('karyawan.tickets.create') }}" class="btn-primary">
        <i class="fas fa-plus mr-2"></i>Buat Laporan Baru
    </a>
    <a href="{{ route('karyawan.tickets.export') }}?{{ http_build_query(request()->query()) }}" 
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
            <h3 class="font-heading font-semibold text-gray-900">Filter Laporan</h3>
            <form action="{{ route('karyawan.dashboard') }}" method="GET" class="flex flex-wrap gap-3">
                <!-- Search -->
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           placeholder="Cari laporan..." 
                           value="{{ request('search') }}"
                           class="input-modern pl-10 pr-4 py-2 w-64"
                           onkeypress="if(event.keyCode==13) this.form.submit()">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                
                <!-- Status Filter -->
                <div>
                    <select name="status" onchange="this.form.submit()" class="input-modern py-2">
                        <option value="all" {{ !request('status') || request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="progress" {{ request('status') == 'progress' ? 'selected' : '' }}>Progress</option>
                        <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Selesai</option>
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
                <a href="{{ route('karyawan.dashboard') }}" class="btn-secondary py-2">
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
                <span>Menunggu penanganan</span>
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
                <span>{{ $statistics['total'] > 0 ? round(($statistics['done']/$statistics['total'])*100) : 0 }}% completion</span>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Rata-rata Waktu</p>
                    <h3 class="text-3xl font-bold mt-1">{{ $performance['avg_response_time'] }}</h3>
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

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Tickets List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h2 class="font-heading text-xl font-bold text-gray-900">Daftar Laporan Saya</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $tickets->count() }} laporan 
                            @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                            (setelah filter)
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('karyawan.tickets.create') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                        <i class="fas fa-plus mr-2"></i> Baru
                    </a>
                </div>
                
                @if($tickets->isEmpty())
                <div class="text-center py-12">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-inbox text-blue-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                        Tidak ada laporan yang sesuai dengan filter
                        @else
                        Belum ada laporan
                        @endif
                    </h3>
                    <p class="text-gray-500 mb-4">
                        @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                        Coba ubah filter atau <a href="{{ route('karyawan.dashboard') }}" class="text-blue-600 hover:text-blue-800">reset filter</a>
                        @else
                        Mulai dengan membuat laporan pertama Anda
                        @endif
                    </p>
                    @if(!request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                    <a href="{{ route('karyawan.tickets.create') }}" class="btn-primary inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i> Buat Laporan
                    </a>
                    @endif
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($tickets as $ticket)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-mono font-medium text-gray-900">
                                        #{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-lg {{ $ticket->getStatusColor() }} flex items-center justify-center">
                                                <i class="fas fa-ticket-alt text-white text-sm"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 truncate max-w-xs">{{ $ticket->title }}</div>
                                            <div class="text-xs text-gray-500">{{ Str::limit($ticket->description, 40) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge {{ $ticket->getStatusColor() }} shadow-sm">
                                        {{ $ticket->getStatusText() }}
                                    </span>
                                    @if($ticket->technician)
                                    <div class="text-xs text-gray-500 mt-1">
                                        Teknisi: {{ $ticket->technician }}
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $ticket->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $ticket->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('karyawan.tickets.show', $ticket->id) }}" 
                                           class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        @if($ticket->status === 'pending')
                                        <a href="{{ route('karyawan.tickets.edit', $ticket->id) }}" 
                                           class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 flex items-center justify-center hover:bg-yellow-100 transition"
                                           title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Performance Card -->
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-heading font-bold text-gray-900">Performance</h3>
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Score</span>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Completion Rate</span>
                            <span class="font-medium text-gray-900">{{ $performance['completion_rate'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-full rounded-full bg-gradient-to-r from-green-400 to-emerald-500" 
                                 style="width: {{ min($performance['completion_rate'], 100) }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Satisfaction Score</span>
                            <span class="font-medium text-gray-900">{{ $performance['satisfaction_score'] }}/5</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-full rounded-full bg-gradient-to-r from-yellow-400 to-yellow-500" 
                                 style="width: {{ ($performance['satisfaction_score'] / 5) * 100 }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Avg. Response Time</span>
                            <span class="font-medium text-gray-900">{{ $performance['avg_response_time'] }} jam</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-full rounded-full bg-gradient-to-r from-blue-400 to-blue-500" 
                                 style="width: {{ min($performance['avg_response_time'] / 48 * 100, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="bg-gradient-to-br from-gray-900 to-blue-900 rounded-2xl shadow-lg p-6 text-white">
                <h3 class="font-heading font-bold text-lg mb-4">Quick Links</h3>
                <div class="space-y-3">
                    <a href="{{ route('karyawan.tickets.create') }}" 
                       class="flex items-center justify-between p-3 rounded-xl bg-white/10 hover:bg-white/20 transition">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-plus-circle"></i>
                            <span>Buat Laporan Baru</span>
                        </div>
                        <i class="fas fa-chevron-right text-sm"></i>
                    </a>
                    
                    <a href="{{ route('karyawan.profile') }}" 
                       class="flex items-center justify-between p-3 rounded-xl bg-white/10 hover:bg-white/20 transition">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-user"></i>
                            <span>Profil Saya</span>
                        </div>
                        <i class="fas fa-chevron-right text-sm"></i>
                    </a>
                    
                    <a href="{{ route('karyawan.analytics') }}" 
                       class="flex items-center justify-between p-3 rounded-xl bg-white/10 hover:bg-white/20 transition">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-chart-bar"></i>
                            <span>Analytics</span>
                        </div>
                        <i class="fas fa-chevron-right text-sm"></i>
                    </a>
                    
                    <a href="{{ route('karyawan.help') }}" 
                       class="flex items-center justify-between p-3 rounded-xl bg-white/10 hover:bg-white/20 transition">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-question-circle"></i>
                            <span>Bantuan</span>
                        </div>
                        <i class="fas fa-chevron-right text-sm"></i>
                    </a>
                </div>
            </div>
            
            <!-- Urgent Tickets -->
            @if($urgentTickets->isNotEmpty())
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold">Laporan Mendesak</h3>
                        <p class="text-red-100 text-sm">Pending > 3 hari</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    @foreach($urgentTickets->take(2) as $ticket)
                    <div class="p-3 rounded-lg bg-white/10 hover:bg-white/20 transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <p class="text-sm font-medium truncate">{{ $ticket->title }}</p>
                                <p class="text-xs text-red-200 mt-1">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $ticket->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <a href="{{ route('karyawan.tickets.show', $ticket->id) }}" 
                               class="ml-2 text-xs bg-white/20 hover:bg-white/30 px-2 py-1 rounded">
                                Lihat
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if($urgentTickets->count() > 2)
                <div class="mt-4 text-center">
                    <a href="{{ route('karyawan.dashboard') }}?status=pending" class="text-sm text-red-100 hover:text-white">
                        +{{ $urgentTickets->count() - 2 }} laporan mendesak lainnya
                    </a>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-heading text-xl font-bold text-gray-900">Aktivitas Terbaru</h2>
                <p class="text-sm text-gray-500 mt-1">Update terbaru dari IT Support</p>
            </div>
        </div>
        
        @if($recentActivity->isEmpty())
        <div class="text-center py-8">
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-history text-gray-400 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada aktivitas</h3>
            <p class="text-gray-500">Tidak ada update dari IT Support</p>
        </div>
        @else
        <div class="space-y-6">
            @foreach($recentActivity as $ticket)
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 relative">
                    <div class="w-12 h-12 rounded-full {{ $ticket->status === 'done' ? 'bg-gradient-to-br from-green-500 to-emerald-600' : 'bg-gradient-to-br from-blue-500 to-blue-600' }} flex items-center justify-center">
                        <i class="{{ $ticket->status === 'done' ? 'fas fa-check text-white' : 'fas fa-cogs text-white' }}"></i>
                    </div>
                    @if(!$loop->last)
                    <div class="absolute left-6 top-12 w-0.5 h-12 {{ $ticket->status === 'done' ? 'bg-green-200' : 'bg-blue-200' }}"></div>
                    @endif
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-medium text-gray-900">Laporan #{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $ticket->status === 'done' ? 'Selesai ditangani' : 'Sedang diproses' }}
                                @if($ticket->technician)
                                oleh {{ $ticket->technician }}
                                @endif
                            </p>
                        </div>
                        <span class="text-xs text-gray-500">{{ $ticket->updated_at->format('d M H:i') }}</span>
                    </div>
                    
                    @if($ticket->admin_response)
                    <div class="mt-3 p-4 bg-gray-50 rounded-xl">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                <i class="fas fa-headset text-white text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">IT Support Team</p>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($ticket->admin_response, 150) }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="mt-3">
                        <a href="{{ route('karyawan.tickets.show', $ticket->id) }}" 
                           class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Lihat detail <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

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