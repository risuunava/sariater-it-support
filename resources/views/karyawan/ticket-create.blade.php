@extends('layouts.app')

@section('title', 'Buat Laporan Baru')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-plus-circle mr-3 text-blue-600"></i>
                Buat Laporan IT Support Baru
            </h1>
            <p class="text-gray-600 mt-2">
                Isi form di bawah ini untuk membuat laporan IT Support
            </p>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-semibold text-gray-800">
                    Form Laporan
                </h2>
            </div>

            <form action="{{ route('karyawan.tickets.store') }}" method="POST" class="p-6">
                @csrf
                
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
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               placeholder="Contoh: Printer tidak bisa mencetak"
                               value="{{ old('title') }}">
                        <p class="mt-1 text-sm text-gray-500">Buat judul yang jelas dan deskriptif</p>
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
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                  placeholder="Jelaskan masalah secara detail, termasuk langkah-langkah yang sudah dicoba, kapan masalah terjadi, dan dampaknya terhadap pekerjaan.">{{ old('description') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">
                            Semakin detail deskripsi, semakin cepat teknisi memahami masalah
                        </p>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-lightbulb text-yellow-500"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">
                                    Tips Membuat Laporan yang Baik
                                </h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Sertakan nomor aset/perangkat (jika ada)</li>
                                        <li>Jelaskan gejala yang muncul</li>
                                        <li>Sebutkan lokasi perangkat</li>
                                        <li>Lampirkan screenshot jika perlu (informasi saja)</li>
                                        <li>Prioritaskan masalah berdasarkan urgensi</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('karyawan.dashboard') }}" 
                           class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 gradient-bg text-white font-medium rounded-lg hover:opacity-90 transition">
                            <i class="fas fa-paper-plane mr-2"></i>Kirim Laporan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection