@extends('layouts.app')

@section('title', 'Kelola Laporan')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        <i class="fas fa-cogs mr-3 text-blue-600"></i>
                        Kelola Laporan
                    </h1>
                    <p class="text-gray-600 mt-2">
                        ID: #{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
                <a href="{{ route('admin.tickets') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Ticket Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Ticket Details -->
                <div class="bg-white rounded-xl shadow">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            Detail Laporan
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $ticket->title }}</h3>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <p class="text-gray-700 whitespace-pre-line">{{ $ticket->description }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <p class="text-sm text-gray-500">Pelapor</p>
                                <p class="font-medium text-gray-900">{{ $ticket->user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $ticket->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Status Saat Ini</p>
                                <span class="status-badge text-lg {{ $ticket->getStatusColor() }}">
                                    {{ $ticket->getStatusText() }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="border-t pt-4">
                            <p class="text-sm text-gray-500 mb-2">Timeline</p>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-3 h-3 rounded-full bg-blue-500"></div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Dibuat</p>
                                        <p class="text-xs text-gray-500">{{ $ticket->created_at->format('d F Y H:i') }}</p>
                                    </div>
                                </div>
                                @if($ticket->status !== 'pending')
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-3 h-3 rounded-full bg-green-500"></div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Diambil Admin</p>
                                        <p class="text-xs text-gray-500">{{ $ticket->updated_at->format('d F Y H:i') }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Actions -->
                <div class="bg-white rounded-xl shadow">
                    <div class="px-6 py-4 border-b border-blue-200 bg-blue-50">
                        <h2 class="text-xl font-semibold text-blue-800">
                            <i class="fas fa-edit mr-2"></i>
                            Update Status & Respon
                        </h2>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.ticket.update.status', $ticket->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-6">
                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-flag mr-2"></i>Status Laporan
                                    </label>
                                    <div class="grid grid-cols-3 gap-3">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="status" value="pending" 
                                                   class="sr-only peer" 
                                                   {{ $ticket->status === 'pending' ? 'checked' : '' }}>
                                            <div class="p-4 border-2 border-gray-200 rounded-lg text-center peer-checked:border-yellow-500 peer-checked:bg-yellow-50 hover:bg-gray-50 transition">
                                                <i class="fas fa-clock text-yellow-500 text-xl mb-2"></i>
                                                <p class="font-medium">Pending</p>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="status" value="progress" 
                                                   class="sr-only peer" 
                                                   {{ $ticket->status === 'progress' ? 'checked' : '' }}>
                                            <div class="p-4 border-2 border-gray-200 rounded-lg text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition">
                                                <i class="fas fa-cogs text-blue-500 text-xl mb-2"></i>
                                                <p class="font-medium">Progress</p>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="status" value="done" 
                                                   class="sr-only peer" 
                                                   {{ $ticket->status === 'done' ? 'checked' : '' }}>
                                            <div class="p-4 border-2 border-gray-200 rounded-lg text-center peer-checked:border-green-500 peer-checked:bg-green-50 hover:bg-gray-50 transition">
                                                <i class="fas fa-check-circle text-green-500 text-xl mb-2"></i>
                                                <p class="font-medium">Selesai</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Technician -->
                                <div>
                                    <label for="technician" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-user-cog mr-2"></i>Teknisi Penanggung Jawab
                                    </label>
                                    <select id="technician" name="technician" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                        <option value="">Pilih Teknisi</option>
                                        @foreach($technicians as $tech)
                                        <option value="{{ $tech }}" {{ $ticket->technician == $tech ? 'selected' : '' }}>
                                            {{ $tech }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-sm text-gray-500">Pilih teknisi yang menangani laporan ini</p>
                                </div>

                                <!-- Admin Response -->
                                <div>
                                    <label for="admin_response" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-comment-dots mr-2"></i>Respon Admin
                                    </label>
                                    <textarea id="admin_response" 
                                              name="admin_response" 
                                              rows="6"
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                              placeholder="Berikan respon atau instruksi untuk pelapor...">{{ old('admin_response', $ticket->admin_response) }}</textarea>
                                    <p class="mt-1 text-sm text-gray-500">Respon ini akan dilihat oleh pelapor</p>
                                </div>

                                <!-- Submit -->
                                <div class="flex justify-end pt-6 border-t border-gray-200">
                                    <button type="submit" 
                                            class="px-8 py-3 gradient-bg text-white font-medium rounded-lg hover:opacity-90 transition">
                                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="space-y-6">
                <!-- Current Info -->
                <div class="bg-white rounded-xl shadow">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-clipboard-check mr-2"></i>
                            Informasi Saat Ini
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Status</p>
                                <span class="status-badge {{ $ticket->getStatusColor() }}">
                                    {{ $ticket->getStatusText() }}
                                </span>
                            </div>
                            
                            @if($ticket->technician)
                            <div>
                                <p class="text-sm text-gray-500">Teknisi</p>
                                <p class="font-medium text-gray-900">{{ $ticket->technician }}</p>
                            </div>
                            @endif
                            
                            <div>
                                <p class="text-sm text-gray-500">Durasi</p>
                                <p class="font-medium text-gray-900">
                                    {{ $ticket->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Previous Response -->
                @if($ticket->admin_response)
                <div class="bg-white rounded-xl shadow">
                    <div class="px-6 py-4 border-b border-green-200 bg-green-50">
                        <h2 class="text-xl font-semibold text-green-800">
                            <i class="fas fa-history mr-2"></i>
                            Respon Sebelumnya
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                            <p class="text-gray-700 whitespace-pre-line">{{ $ticket->admin_response }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-bolt mr-2"></i>
                            Aksi Cepat
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <form action="{{ route('admin.ticket.update.status', $ticket->id) }}" method="POST" class="inline-block w-full">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="progress">
                                <input type="hidden" name="technician" value="{{ Auth::user()->name }}">
                                <button type="submit" 
                                        class="w-full flex items-center justify-center px-4 py-3 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 transition">
                                    <i class="fas fa-play mr-3"></i>
                                    Ambil & Proses
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.ticket.update.status', $ticket->id) }}" method="POST" class="inline-block w-full">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="done">
                                <input type="hidden" name="admin_response" value="Laporan telah diselesaikan.">
                                <button type="submit" 
                                        class="w-full flex items-center justify-center px-4 py-3 bg-green-500 text-white font-medium rounded-lg hover:bg-green-600 transition">
                                    <i class="fas fa-check mr-3"></i>
                                    Tandai Selesai
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <h3 class="font-semibold text-blue-800 mb-3">
                        <i class="fas fa-address-card mr-2"></i>
                        Kontak Pelapor
                    </h3>
                    <div class="space-y-2">
                        <p class="text-sm">
                            <span class="text-gray-600">Nama:</span>
                            <span class="font-medium ml-2">{{ $ticket->user->name }}</span>
                        </p>
                        <p class="text-sm">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium ml-2">{{ $ticket->user->email }}</span>
                        </p>
                        <div class="mt-4 pt-4 border-t border-blue-200">
                            <a href="mailto:{{ $ticket->user->email }}" 
                               class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                <i class="fas fa-envelope mr-2"></i>
                                Kirim Email
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection