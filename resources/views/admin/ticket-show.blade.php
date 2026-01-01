@extends('layouts.app')

@section('title', 'Kelola Laporan')
@section('page-title', 'Kelola Laporan')
@section('page-description', 'Update status dan respon untuk laporan IT Support')

@section('header-actions')
<div class="flex items-center space-x-3">
    <a href="{{ route('admin.tickets') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
    <button onclick="window.print()" class="btn-secondary">
        <i class="fas fa-print mr-2"></i>Cetak
    </button>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Ticket Details & Actions -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Ticket Header -->
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
            <div class="px-6 py-5 bg-gradient-to-r from-blue-50 to-blue-100 border-b border-blue-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                            <i class="fas fa-ticket-alt text-white text-lg"></i>
                        </div>
                        <div>
                            <h2 class="font-heading text-xl font-bold text-gray-900">{{ $ticket->title }}</h2>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="text-sm text-gray-600">ID: #{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}</span>
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                <span class="text-sm text-gray-600">Dibuat: {{ $ticket->created_at->format('d M Y, H:i') }}</span>
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
                
                <!-- Reporter Info -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-6">
                    <h3 class="font-heading font-semibold text-gray-900 mb-4">Informasi Pelapor</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold">
                                {{ substr($ticket->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Nama</p>
                                <p class="font-medium text-gray-900">{{ $ticket->user->name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-gray-600 to-gray-700 flex items-center justify-center">
                                <i class="fas fa-envelope text-white"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="font-medium text-gray-900">{{ $ticket->user->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Admin Actions -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                    <i class="fas fa-cogs text-white"></i>
                </div>
                <div>
                    <h3 class="font-heading font-semibold text-gray-900">Update Status & Respon</h3>
                    <p class="text-sm text-gray-600">Kelola laporan dan berikan respon</p>
                </div>
            </div>
            
            <form action="{{ route('admin.ticket.update.status', $ticket->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-3">
                            <i class="fas fa-flag mr-2"></i>Status Laporan
                        </label>
                        <div class="grid grid-cols-3 gap-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="pending" 
                                       class="sr-only peer" 
                                       {{ $ticket->status === 'pending' ? 'checked' : '' }}>
                                <div class="p-4 border-2 border-gray-200 rounded-xl text-center peer-checked:border-yellow-500 peer-checked:bg-yellow-50 hover:bg-gray-50 transition hover-lift">
                                    <i class="fas fa-clock text-yellow-500 text-xl mb-2"></i>
                                    <p class="font-medium text-gray-900">Pending</p>
                                    <p class="text-xs text-gray-500 mt-1">Menunggu</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="progress" 
                                       class="sr-only peer" 
                                       {{ $ticket->status === 'progress' ? 'checked' : '' }}>
                                <div class="p-4 border-2 border-gray-200 rounded-xl text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition hover-lift">
                                    <i class="fas fa-cogs text-blue-500 text-xl mb-2"></i>
                                    <p class="font-medium text-gray-900">Progress</p>
                                    <p class="text-xs text-gray-500 mt-1">Diproses</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="done" 
                                       class="sr-only peer" 
                                       {{ $ticket->status === 'done' ? 'checked' : '' }}>
                                <div class="p-4 border-2 border-gray-200 rounded-xl text-center peer-checked:border-green-500 peer-checked:bg-green-50 hover:bg-gray-50 transition hover-lift">
                                    <i class="fas fa-check-circle text-green-500 text-xl mb-2"></i>
                                    <p class="font-medium text-gray-900">Selesai</p>
                                    <p class="text-xs text-gray-500 mt-1">Diselesaikan</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Technician Assignment -->
                    <div>
                        <label for="technician" class="block text-sm font-medium text-gray-900 mb-2">
                            <i class="fas fa-user-cog mr-2"></i>Penugasan Teknisi
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-users text-gray-400"></i>
                            </div>
                            <select id="technician" name="technician" 
                                    class="input-modern pl-10">
                                <option value="">Pilih Teknisi...</option>
                                @foreach($technicians as $tech)
                                <option value="{{ $tech }}" {{ $ticket->technician == $tech ? 'selected' : '' }}>
                                    {{ $tech }}
                                </option>
                                @endforeach
                                <option value="other">+ Tambah teknisi baru</option>
                            </select>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Tetapkan teknisi yang bertanggung jawab</p>
                    </div>

                    <!-- Admin Response -->
                    <div>
                        <label for="admin_response" class="block text-sm font-medium text-gray-900 mb-2">
                            <i class="fas fa-comment-dots mr-2"></i>Respon & Catatan
                        </label>
                        <div class="relative">
                            <div class="absolute top-3 left-3 pointer-events-none">
                                <i class="fas fa-edit text-gray-400"></i>
                            </div>
                            <textarea id="admin_response" 
                                      name="admin_response" 
                                      rows="8"
                                      class="input-modern pl-10 pt-3"
                                      placeholder="Berikan respon untuk pelapor. Anda dapat memberikan update progress, solusi, atau instruksi selanjutnya...">{{ old('admin_response', $ticket->admin_response) }}</textarea>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Respon ini akan dikirim ke email pelapor</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-6 border-t border-gray-200">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Right Column - Sidebar -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-bolt"></i>
                </div>
                <div>
                    <h3 class="font-heading font-semibold text-lg">Quick Actions</h3>
                    <p class="text-blue-100 text-sm">Aksi cepat untuk laporan ini</p>
                </div>
            </div>
            
            <div class="space-y-3">
                <form action="{{ route('admin.ticket.update.status', $ticket->id) }}" method="POST" class="inline-block w-full">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="progress">
                    <input type="hidden" name="technician" value="{{ Auth::user()->name }}">
                    <input type="hidden" name="admin_response" value="Laporan sedang kami proses. Tim IT Support akan segera menindaklanjuti.">
                    <button type="submit" 
                            class="w-full flex items-center justify-center px-4 py-3 bg-white/20 hover:bg-white/30 rounded-xl transition group">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center group-hover:bg-white/30">
                                <i class="fas fa-play"></i>
                            </div>
                            <div class="text-left">
                                <p class="font-medium">Ambil & Proses</p>
                                <p class="text-xs text-blue-100">Tandai sebagai diproses</p>
                            </div>
                        </div>
                    </button>
                </form>
                
                <form action="{{ route('admin.ticket.update.status', $ticket->id) }}" method="POST" class="inline-block w-full">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="done">
                    <input type="hidden" name="admin_response" value="Laporan telah diselesaikan. Terima kasih telah melaporkan. Jika ada masalah lain, silakan buat laporan baru.">
                    <button type="submit" 
                            class="w-full flex items-center justify-center px-4 py-3 bg-white/20 hover:bg-white/30 rounded-xl transition group">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center group-hover:bg-white/30">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="text-left">
                                <p class="font-medium">Tandai Selesai</p>
                                <p class="text-xs text-blue-100">Selesaikan laporan</p>
                            </div>
                        </div>
                    </button>
                </form>
                
                <a href="mailto:{{ $ticket->user->email }}" 
                   class="flex items-center justify-center px-4 py-3 bg-white/20 hover:bg-white/30 rounded-xl transition group">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center group-hover:bg-white/30">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="text-left">
                            <p class="font-medium">Email Pelapor</p>
                            <p class="text-xs text-blue-100">{{ $ticket->user->email }}</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        <!-- Timeline -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <h3 class="font-heading font-semibold text-gray-900 mb-6">Timeline</h3>
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                            <i class="fas fa-plus text-white text-xs"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Laporan dibuat</p>
                        <p class="text-xs text-gray-500">{{ $ticket->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                
                @if($ticket->status !== 'pending')
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-500 flex items-center justify-center">
                            <i class="fas fa-user-cog text-white text-xs"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Diambil oleh Admin</p>
                        <p class="text-xs text-gray-500">{{ $ticket->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                @endif
                
                @if($ticket->technician)
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-user-hard-hat text-white text-xs"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Ditugaskan ke {{ $ticket->technician }}</p>
                        <p class="text-xs text-gray-500">Teknisi penanggung jawab</p>
                    </div>
                </div>
                @endif
                
                @if($ticket->status === 'done')
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Diselesaikan</p>
                        <p class="text-xs text-gray-500">{{ $ticket->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Stats Card -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <h3 class="font-heading font-semibold text-gray-900 mb-4">Statistik Laporan</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Durasi</span>
                    <span class="font-medium text-gray-900">
                        {{ $ticket->created_at->diffForHumans(null, true) }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Priority</span>
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded">Medium</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Category</span>
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">Hardware</span>
                </div>
                <div class="pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-600 mb-2">Update Terakhir</p>
                    <p class="text-sm font-medium text-gray-900">{{ $ticket->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Technician Selection -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const technicianSelect = document.getElementById('technician');
        const newTechnicianInput = document.createElement('input');
        newTechnicianInput.type = 'text';
        newTechnicianInput.name = 'technician_new';
        newTechnicianInput.placeholder = 'Nama teknisi baru';
        newTechnicianInput.className = 'input-modern mt-2 hidden';
        
        technicianSelect.parentNode.appendChild(newTechnicianInput);
        
        technicianSelect.addEventListener('change', function() {
            if (this.value === 'other') {
                newTechnicianInput.classList.remove('hidden');
                newTechnicianInput.required = true;
            } else {
                newTechnicianInput.classList.add('hidden');
                newTechnicianInput.required = false;
            }
        });
    });
</script>
@endsection