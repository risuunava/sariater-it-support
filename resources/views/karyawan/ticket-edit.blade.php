@extends('layouts.app')

@section('title', 'Edit Laporan')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-edit mr-3 text-blue-600"></i>
                Edit Laporan
            </h1>
            <p class="text-gray-600 mt-2">
                Edit laporan IT Support Anda
            </p>
            
            @if($ticket->status !== 'pending')
            <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            <strong>Perhatian:</strong> Laporan ini tidak bisa diedit karena status sudah 
                            <span class="font-bold">{{ $ticket->getStatusText() }}</span>.
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">
                        Form Edit Laporan
                    </h2>
                    <span class="status-badge {{ $ticket->getStatusColor() }}">
                        {{ $ticket->getStatusText() }}
                    </span>
                </div>
            </div>

            <form action="{{ route('karyawan.tickets.update', $ticket->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-heading mr-2"></i>Judul Laporan
                        </label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               required
                               {{ $ticket->status !== 'pending' ? 'readonly' : '' }}
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition {{ $ticket->status !== 'pending' ? 'bg-gray-100' : '' }}"
                               value="{{ $ticket->title }}">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-2"></i>Deskripsi Masalah
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="8"
                                  required
                                  {{ $ticket->status !== 'pending' ? 'readonly' : '' }}
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition {{ $ticket->status !== 'pending' ? 'bg-gray-100' : '' }}">{{ $ticket->description }}</textarea>
                    </div>

                    <!-- Admin Response (Read Only) -->
                    @if($ticket->admin_response)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-blue-800 mb-2">
                            <i class="fas fa-comment-dots mr-2"></i>Respon Admin
                        </h3>
                        <div class="bg-white p-4 rounded border">
                            <p class="text-gray-700">{{ $ticket->admin_response }}</p>
                            @if($ticket->technician)
                            <p class="mt-2 text-sm text-gray-600">
                                <i class="fas fa-user-cog mr-1"></i>
                                Teknisi: {{ $ticket->technician }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('karyawan.dashboard') }}" 
                           class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                        
                        @if($ticket->status === 'pending')
                        <button type="submit" 
                                class="px-6 py-3 gradient-bg text-white font-medium rounded-lg hover:opacity-90 transition">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection