@extends('layouts.app')

@section('title', 'Detail Laporan')
@section('page-title', 'Detail Laporan')
@section('page-description', 'Lihat detail dan status laporan IT Support')

@section('header-actions')
<div class="flex items-center space-x-3">
    <a href="{{ route('karyawan.dashboard') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
    @if($ticket->status === 'pending')
    <a href="{{ route('karyawan.tickets.edit', $ticket->id) }}" class="btn-primary">
        <i class="fas fa-edit mr-2"></i>Edit Laporan
    </a>
    @endif
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Ticket Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Ticket Header -->
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl {{ $ticket->getStatusColor() }} flex items-center justify-center">
                            <i class="fas fa-ticket-alt text-white text-lg"></i>
                        </div>
                        <div>
                            <h2 class="font-heading text-xl font-bold text-gray-900">{{ $ticket->title }}</h2>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="text-sm text-gray-500">ID: #{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}</span>
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                <span class="text-sm text-gray-500">Dibuat: {{ $ticket->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <span class="status-badge text-lg {{ $ticket->getStatusColor() }} shadow-md">
                        {{ $ticket->getStatusText() }}
                    </span>
                </div>
            </div>
            
            <div class="p-6">
                <!-- Description -->
                <div class="mb-8">
                    <h3 class="font-heading font-semibold text-gray-900 mb-3">Deskripsi Masalah</h3>
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <p class="text-gray-700 whitespace-pre-line">{{ $ticket->description }}</p>
                    </div>
                </div>
                
                <!-- Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 rounded-xl p-6">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Informasi Pelapor</h4>
                                <p class="text-sm text-gray-600">Data yang melaporkan</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500">Nama</p>
                                <p class="font-medium text-gray-900">{{ $ticket->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Email</p>
                                <p class="font-medium text-gray-900">{{ $ticket->user->email }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-gray-600 to-gray-700 flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Timeline</h4>
                                <p class="text-sm text-gray-600">Rekam waktu</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500">Dibuat</p>
                                <p class="font-medium text-gray-900">{{ $ticket->created_at->format('d F Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Terakhir Update</p>
                                <p class="font-medium text-gray-900">{{ $ticket->updated_at->format('d F Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Admin Response -->
        @if($ticket->admin_response)
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl shadow-soft overflow-hidden">
            <div class="px-6 py-5 bg-gradient-to-r from-blue-500 to-blue-600">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fas fa-comment-alt text-white"></i>
                    </div>
                    <div>
                        <h3 class="font-heading text-lg font-bold text-white">Respon Admin IT Support</h3>
                        <p class="text-blue-100 text-sm">Update dari tim teknis</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                <i class="fas fa-headset text-white"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h4 class="font-semibold text-gray-900">IT Support Team</h4>
                                    <p class="text-sm text-gray-500">{{ $ticket->updated_at->format('d F Y, H:i') }}</p>
                                </div>
                                @if($ticket->technician)
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    Teknisi: {{ $ticket->technician }}
                                </span>
                                @endif
                            </div>
                            <div class="prose prose-blue max-w-none">
                                <p class="text-gray-700 whitespace-pre-line">{{ $ticket->admin_response }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Right Column - Status & Actions -->
    <div class="space-y-6">
        <!-- Status Timeline -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <h3 class="font-heading font-bold text-gray-900 mb-6">Status Timeline</h3>
            <div class="relative">
                <!-- Timeline Line -->
                <div class="absolute left-5 top-0 bottom-0 w-0.5 bg-gradient-to-b from-blue-200 to-gray-200"></div>
                
                <!-- Timeline Items -->
                <div class="space-y-8">
                    <!-- Created -->
                    <div class="relative flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                <i class="fas fa-plus text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="font-semibold text-gray-900">Laporan Dibuat</h4>
                            <p class="text-sm text-gray-500 mt-1">{{ $ticket->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    
                    <!-- In Progress -->
                    @if($ticket->status !== 'pending')
                    <div class="relative flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-500 flex items-center justify-center">
                                <i class="fas fa-play text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="font-semibold text-gray-900">Diproses Admin</h4>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $ticket->updated_at->format('d M Y, H:i') }}
                                @if($ticket->technician)
                                <br>Oleh: {{ $ticket->technician }}
                                @endif
                            </p>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Completed -->
                    @if($ticket->status === 'done')
                    <div class="relative flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="font-semibold text-gray-900">Selesai</h4>
                            <p class="text-sm text-gray-500 mt-1">{{ $ticket->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <h3 class="font-heading font-bold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('karyawan.dashboard') }}" 
                   class="flex items-center justify-between p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition group">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-gray-600 to-gray-700 flex items-center justify-center">
                            <i class="fas fa-tachometer-alt text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Kembali ke Dashboard</p>
                            <p class="text-sm text-gray-600">Lihat semua laporan</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-900"></i>
                </a>
                
                @if($ticket->status === 'pending')
                <a href="{{ route('karyawan.tickets.edit', $ticket->id) }}" 
                   class="flex items-center justify-between p-4 rounded-xl bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 hover:shadow-md transition group">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Edit Laporan</p>
                            <p class="text-sm text-gray-600">Ubah deskripsi masalah</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-yellow-600"></i>
                </a>
                @endif
                
                <button onclick="window.print()" 
                        class="w-full flex items-center justify-between p-4 rounded-xl bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 hover:shadow-md transition group">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                            <i class="fas fa-print text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Cetak Laporan</p>
                            <p class="text-sm text-gray-600">Export ke PDF/Print</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-600"></i>
                </button>
            </div>
        </div>
        
        <!-- Help Card -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-headphones-alt text-xl"></i>
                </div>
                <div>
                    <h3 class="font-heading font-bold text-lg">Butuh Bantuan?</h3>
                    <p class="text-purple-100 text-sm">Kami siap membantu</p>
                </div>
            </div>
            
            <div class="space-y-4">
                <a href="tel:+622112345678" 
                   class="flex items-center justify-between p-3 rounded-xl bg-white/10 hover:bg-white/20 transition">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-phone"></i>
                        <span>Emergency Call</span>
                    </div>
                    <span class="text-sm font-medium">(021) 1234-5678</span>
                </a>
                
                <a href="mailto:support@sariater.com" 
                   class="flex items-center justify-between p-3 rounded-xl bg-white/10 hover:bg-white/20 transition">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-envelope"></i>
                        <span>Email Support</span>
                    </div>
                    <i class="fas fa-external-link-alt text-sm"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
    @media print {
        nav, footer, .header-actions, .btn-secondary, .btn-primary {
            display: none !important;
        }
        
        body {
            background: white !important;
        }
        
        .rounded-2xl, .rounded-xl, .rounded-lg {
            border-radius: 4px !important;
            box-shadow: none !important;
        }
        
        .bg-gradient-to-r, .gradient-bg {
            background: #f8fafc !important;
            color: #1e293b !important;
        }
        
        .text-white, .text-blue-100, .text-purple-100 {
            color: #1e293b !important;
        }
    }
</style>
@endsection