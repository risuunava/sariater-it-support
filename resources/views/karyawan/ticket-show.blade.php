@extends('layouts.app')

@section('title', 'Detail Laporan')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        <i class="fas fa-ticket-alt mr-3 text-blue-600"></i>
                        Detail Laporan
                    </h1>
                    <p class="text-gray-600 mt-2">
                        ID: #{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
                <span class="status-badge text-lg {{ $ticket->getStatusColor() }}">
                    {{ $ticket->getStatusText() }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Ticket Info -->
                <div class="bg-white rounded-xl shadow">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            Informasi Laporan
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $ticket->title }}</h3>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <p class="text-gray-700 whitespace-pre-line">{{ $ticket->description }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Dibuat oleh:</p>
                                <p class="font-medium">{{ $ticket->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Tanggal dibuat:</p>
                                <p class="font-medium">{{ $ticket->created_at->format('d F Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Terakhir diupdate:</p>
                                <p class="font-medium">{{ $ticket->updated_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Response -->
                @if($ticket->admin_response)
                <div class="bg-white rounded-xl shadow">
                    <div class="px-6 py-4 border-b border-blue-200 bg-blue-50">
                        <h2 class="text-xl font-semibold text-blue-800">
                            <i class="fas fa-comments mr-2"></i>
                            Respon Admin IT Support
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-gray-700 whitespace-pre-line">{{ $ticket->admin_response }}</p>
                            @if($ticket->technician)
                            <div class="mt-4 p-3 bg-white rounded border">
                                <p class="font-medium text-gray-700">
                                    <i class="fas fa-user-cog mr-2 text-blue-600"></i>
                                    Teknisi Penanggung Jawab
                                </p>
                                <p class="text-lg font-semibold text-blue-700 mt-1">{{ $ticket->technician }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Actions -->
                <div class="bg-white rounded-xl shadow">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-cogs mr-2"></i>
                            Aksi
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('karyawan.dashboard') }}" 
                               class="flex items-center justify-center w-full px-4 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                                <i class="fas fa-arrow-left mr-3"></i>
                                Kembali ke Dashboard
                            </a>
                            
                            @if($ticket->status === 'pending')
                            <a href="{{ route('karyawan.tickets.edit', $ticket->id) }}" 
                               class="flex items-center justify-center w-full px-4 py-3 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-600 transition">
                                <i class="fas fa-edit mr-3"></i>
                                Edit Laporan
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="bg-white rounded-xl shadow">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-history mr-2"></i>
                            Timeline Status
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-plus text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Laporan dibuat</p>
                                    <p class="text-xs text-gray-500">{{ $ticket->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            
                            @if($ticket->status !== 'pending')
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-play text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Diproses admin</p>
                                    <p class="text-xs text-gray-500">Setelah {{ $ticket->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endif
                            
                            @if($ticket->status === 'done')
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-check text-green-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Selesai</p>
                                    <p class="text-xs text-gray-500">{{ $ticket->updated_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Help -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <h3 class="font-semibold text-blue-800 mb-2">
                        <i class="fas fa-question-circle mr-2"></i>
                        Butuh Bantuan Cepat?
                    </h3>
                    <p class="text-sm text-blue-700">
                        Jika masalah sangat mendesak, hubungi IT Support langsung melalui:
                    </p>
                    <div class="mt-3 p-3 bg-white rounded border">
                        <p class="text-sm font-medium text-gray-700">
                            <i class="fas fa-phone mr-2 text-green-600"></i>
                            Ext: 1234
                        </p>
                        <p class="text-sm text-gray-600 mt-1">Senin - Jumat, 08:00 - 17:00</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection