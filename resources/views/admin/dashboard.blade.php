@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-shield-alt mr-3 text-blue-600"></i>
                Dashboard Admin IT Support
            </h1>
            <p class="text-gray-600 mt-2">
                Selamat datang, <span class="font-semibold text-blue-600">{{ Auth::user()->name }}</span>. 
                Kelola semua laporan IT Support di sini.
            </p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6 hover-scale">
                <div class="flex items-center">
                    <div class="rounded-lg bg-blue-100 p-3">
                        <i class="fas fa-ticket-alt text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total Laporan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalTickets }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6 hover-scale">
                <div class="flex items-center">
                    <div class="rounded-lg bg-yellow-100 p-3">
                        <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Pending</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $pendingTickets }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6 hover-scale">
                <div class="flex items-center">
                    <div class="rounded-lg bg-blue-100 p-3">
                        <i class="fas fa-cogs text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Progress</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $progressTickets }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6 hover-scale">
                <div class="flex items-center">
                    <div class="rounded-lg bg-green-100 p-3">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Selesai</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $doneTickets }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mb-6 flex flex-wrap gap-3">
            <a href="{{ route('admin.tickets') }}" 
               class="inline-flex items-center px-6 py-3 gradient-bg text-white font-semibold rounded-lg hover:opacity-90 transition">
                <i class="fas fa-list mr-2"></i>
                Lihat Semua Laporan
            </a>
            <a href="{{ route('admin.tickets') }}?status=pending" 
               class="inline-flex items-center px-6 py-3 bg-yellow-500 text-white font-semibold rounded-lg hover:bg-yellow-600 transition">
                <i class="fas fa-exclamation-circle mr-2"></i>
                Laporan Pending ({{ $pendingTickets }})
            </a>
        </div>

        <!-- Recent Tickets -->
        <div class="bg-white rounded-xl shadow overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">
                        <i class="fas fa-history mr-2"></i>
                        Laporan Terbaru
                    </h2>
                    <span class="text-sm text-gray-500">10 terakhir</span>
                </div>
            </div>

            @if($recentTickets->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada laporan</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pelapor
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Judul
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentTickets as $ticket)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $ticket->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $ticket->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ Str::limit($ticket->title, 40) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge {{ $ticket->getStatusColor() }}">
                                        {{ $ticket->getStatusText() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.ticket.show', $ticket->id) }}" 
                                       class="text-blue-600 hover:text-blue-900 inline-flex items-center">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Priority Issues -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i>
                    Laporan Pending Tertua
                </h3>
                @php
                    $oldestPending = \App\Models\Ticket::where('status', 'pending')
                        ->orderBy('created_at', 'asc')
                        ->limit(3)
                        ->get();
                @endphp
                
                @if($oldestPending->isEmpty())
                    <p class="text-gray-500 text-center py-4">Tidak ada laporan pending</p>
                @else
                    <div class="space-y-4">
                        @foreach($oldestPending as $ticket)
                        <div class="border border-yellow-200 rounded-lg p-4 hover:bg-yellow-50 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $ticket->title }}</p>
                                    <p class="text-sm text-gray-500 mt-1">{{ $ticket->user->name }}</p>
                                </div>
                                <span class="text-xs text-yellow-600 font-medium">
                                    {{ $ticket->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('admin.ticket.show', $ticket->id) }}" 
                                   class="text-sm text-blue-600 hover:text-blue-800 inline-flex items-center">
                                    <i class="fas fa-hand-point-right mr-1"></i> Tangani Sekarang
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- System Info -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-chart-bar mr-2 text-blue-500"></i>
                    Statistik Sistem
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Laporan Bulan Ini</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ \App\Models\Ticket::whereMonth('created_at', date('m'))->count() }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Rata-rata Waktu Penyelesaian</p>
                        <p class="text-xl font-bold text-green-600">
                            @php
                                $doneTickets = \App\Models\Ticket::where('status', 'done')->get();
                                $avgHours = $doneTickets->avg(function($ticket) {
                                    return $ticket->created_at->diffInHours($ticket->updated_at);
                                });
                                echo $avgHours ? round($avgHours) . ' jam' : 'Belum ada data';
                            @endphp
                        </p>
                    </div>
                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-500">Last Login</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ Auth::user()->updated_at->format('d F Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection