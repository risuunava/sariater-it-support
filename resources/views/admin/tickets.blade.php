@extends('layouts.app')

@section('title', 'Semua Laporan')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-list-alt mr-3 text-blue-600"></i>
                Semua Laporan IT Support
            </h1>
            <p class="text-gray-600 mt-2">
                Kelola semua laporan dari seluruh karyawan
            </p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700 font-medium">Filter:</span>
                    <a href="{{ route('admin.tickets') }}" 
                       class="px-4 py-2 rounded-lg {{ !request('status') ? 'gradient-bg text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Semua
                    </a>
                    <a href="{{ route('admin.tickets') }}?status=pending" 
                       class="px-4 py-2 rounded-lg {{ request('status') == 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Pending
                    </a>
                    <a href="{{ route('admin.tickets') }}?status=progress" 
                       class="px-4 py-2 rounded-lg {{ request('status') == 'progress' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Progress
                    </a>
                    <a href="{{ route('admin.tickets') }}?status=done" 
                       class="px-4 py-2 rounded-lg {{ request('status') == 'done' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Selesai
                    </a>
                </div>
                
                <div class="text-sm text-gray-500">
                    Total: <span class="font-bold">{{ $tickets->count() }}</span> laporan
                </div>
            </div>
        </div>

        <!-- Tickets Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">
                        Daftar Laporan
                    </h2>
                    <div class="text-sm text-gray-500">
                        Urutkan berdasarkan: 
                        <select class="ml-2 border rounded px-2 py-1" onchange="window.location.href = '{{ route('admin.tickets') }}?sort=' + this.value">
                            <option value="newest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                        </select>
                    </div>
                </div>
            </div>

            @if($tickets->isEmpty())
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
                                    Teknisi
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tickets as $ticket)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $ticket->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $ticket->user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ Str::limit($ticket->title, 50) }}</div>
                                    <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($ticket->description, 60) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge {{ $ticket->getStatusColor() }}">
                                        {{ $ticket->getStatusText() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $ticket->technician ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $ticket->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.ticket.show', $ticket->id) }}" 
                                       class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                                        <i class="fas fa-edit mr-2"></i>
                                        Kelola
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{-- Jika menggunakan pagination --}}
                    {{-- {{ $tickets->links() }} --}}
                    <p class="text-sm text-gray-500">
                        Menampilkan {{ $tickets->count() }} laporan
                    </p>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h3 class="font-semibold text-blue-800 mb-3">
                    <i class="fas fa-bolt mr-2"></i>
                    Prioritas Tinggi
                </h3>
                <p class="text-sm text-blue-700 mb-4">
                    Laporan pending lebih dari 3 hari harus segera ditangani
                </p>
                @php
                    $urgentTickets = \App\Models\Ticket::where('status', 'pending')
                        ->where('created_at', '<', now()->subDays(3))
                        ->count();
                @endphp
                <div class="text-center">
                    <span class="text-3xl font-bold text-red-600">{{ $urgentTickets }}</span>
                    <p class="text-sm text-gray-600">laporan mendesak</p>
                </div>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                <h3 class="font-semibold text-green-800 mb-3">
                    <i class="fas fa-chart-line mr-2"></i>
                    Performa
                </h3>
                <p class="text-sm text-green-700 mb-4">
                    Rata-rata waktu penyelesaian laporan
                </p>
                <div class="text-center">
                    <span class="text-3xl font-bold text-green-600">
                        @php
                            $doneTickets = \App\Models\Ticket::where('status', 'done')->get();
                            $avgHours = $doneTickets->avg(function($ticket) {
                                return $ticket->created_at->diffInHours($ticket->updated_at);
                            });
                            echo $avgHours ? round($avgHours) . ' jam' : '-';
                        @endphp
                    </span>
                    <p class="text-sm text-gray-600">waktu rata-rata</p>
                </div>
            </div>

            <div class="bg-purple-50 border border-purple-200 rounded-xl p-6">
                <h3 class="font-semibold text-purple-800 mb-3">
                    <i class="fas fa-user-friends mr-2"></i>
                    Teknisi
                </h3>
                <p class="text-sm text-purple-700 mb-4">
                    Distribusi pekerjaan teknisi
                </p>
                <div class="space-y-2">
                    @php
                        $techStats = \App\Models\Ticket::whereNotNull('technician')
                            ->select('technician', \DB::raw('count(*) as count'))
                            ->groupBy('technician')
                            ->get();
                    @endphp
                    @foreach($techStats->take(3) as $stat)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-700">{{ $stat->technician }}</span>
                        <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs">
                            {{ $stat->count }} laporan
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection