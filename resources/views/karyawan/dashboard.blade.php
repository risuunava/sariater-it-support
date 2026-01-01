@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-tachometer-alt mr-3 text-blue-600"></i>
                Dashboard Karyawan
            </h1>
            <p class="text-gray-600 mt-2">
                Selamat datang, <span class="font-semibold text-blue-600">{{ Auth::user()->name }}</span>. 
                Kelola laporan IT Support Anda di sini.
            </p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6 hover-scale">
                <div class="flex items-center">
                    <div class="rounded-lg bg-blue-100 p-3">
                        <i class="fas fa-ticket-alt text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total Laporan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $tickets->count() }}</p>
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
                        <p class="text-2xl font-bold text-gray-900">{{ $tickets->where('status', 'pending')->count() }}</p>
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
                        <p class="text-2xl font-bold text-gray-900">{{ $tickets->where('status', 'done')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Button -->
        <div class="mb-6">
            <a href="{{ route('karyawan.tickets.create') }}" 
               class="inline-flex items-center px-6 py-3 gradient-bg text-white font-semibold rounded-lg hover:opacity-90 transition">
                <i class="fas fa-plus mr-2"></i>
                Buat Laporan Baru
            </a>
        </div>

        <!-- Tickets List -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">
                    <i class="fas fa-list mr-2"></i>
                    Daftar Laporan Anda
                </h2>
            </div>

            @if($tickets->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada laporan</p>
                    <p class="text-gray-400 mt-2">Buat laporan pertama Anda sekarang!</p>
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
                                    Judul
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dibuat
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
                                    <div class="text-sm font-medium text-gray-900">{{ $ticket->title }}</div>
                                    <div class="text-sm text-gray-500 truncate max-w-xs">{{ $ticket->description }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge {{ $ticket->getStatusColor() }}">
                                        {{ $ticket->getStatusText() }}
                                    </span>
                                    @if($ticket->technician)
                                        <div class="text-xs text-gray-500 mt-1">
                                            Teknisi: {{ $ticket->technician }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $ticket->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('karyawan.tickets.show', $ticket->id) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($ticket->status === 'pending')
                                        <a href="{{ route('karyawan.tickets.edit', $ticket->id) }}" 
                                           class="text-yellow-600 hover:text-yellow-900">
                                            <i class="fas fa-edit"></i>
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

        <!-- Help Section -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-3">
                <i class="fas fa-question-circle mr-2"></i>Butuh Bantuan?
            </h3>
            <ul class="text-blue-700 space-y-2">
                <li class="flex items-start">
                    <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                    <span>Pastikan deskripsi laporan jelas dan lengkap</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                    <span>Anda dapat mengedit laporan selama status masih "Menunggu"</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                    <span>Respon admin akan muncul di detail laporan</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection