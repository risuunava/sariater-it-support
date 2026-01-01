@extends('layouts.app')

@section('title', 'Kelola User')
@section('page-title', 'Kelola User')
@section('page-description', 'Kelola semua user dan akses sistem')

@section('header-actions')
<div class="flex items-center space-x-3">
    <form action="{{ route('admin.users') }}" method="GET" class="relative">
        <input type="text" 
               name="search" 
               value="{{ request('search') }}"
               placeholder="Cari user..." 
               class="input-modern pl-10 pr-4 py-2 w-64"
               onkeypress="if(event.keyCode==13) this.form.submit()">
        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
    </form>
    <a href="{{ route('admin.dashboard') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Dashboard
    </a>
</div>
@endsection

@section('content')
<div class="bg-white rounded-2xl shadow-soft overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-heading text-xl font-bold text-gray-900">Daftar User</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} user
                </p>
            </div>
            
            <div class="text-sm text-gray-500">
                Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }}
            </div>
        </div>
    </div>
    
    @if($users->isEmpty())
    <div class="text-center py-12">
        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-users text-blue-400 text-2xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak ada user</h3>
        <p class="text-gray-500 mb-4">Belum ada user yang terdaftar</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Laporan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($users as $user)
                @php
                    $ticketCount = \App\Models\Ticket::where('user_id', $user->id)->count();
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br 
                                @if($user->role == 'admin') from-purple-500 to-purple-600
                                @else from-blue-500 to-blue-600 @endif 
                                flex items-center justify-center text-white font-medium text-lg mr-3">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $user->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $user->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($user->role == 'admin') bg-purple-100 text-purple-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ $user->role == 'admin' ? 'Admin IT' : 'Karyawan' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $user->created_at->format('d M Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $user->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $ticketCount }}</div>
                        <div class="text-xs text-gray-500">laporan</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user->email_verified_at)
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Verified
                        </span>
                        @else
                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>Pending
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            @if($user->id != Auth::id())
                            <button class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition"
                                    title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                            <button class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition"
                                    title="Hapus"
                                    onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                            @else
                            <span class="text-xs text-gray-400">Current User</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="text-sm text-gray-500">
                Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} user
            </div>
            
            <div class="flex items-center space-x-2">
                {{ $users->appends(request()->query())->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- User Stats -->
<div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-blue-700 font-medium">Total Users</p>
                <p class="text-2xl font-bold text-gray-900">{{ $users->total() }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center">
                <i class="fas fa-users text-white"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-purple-700 font-medium">Admin</p>
                <p class="text-2xl font-bold text-gray-900">
                    {{ \App\Models\User::where('role', 'admin')->count() }}
                </p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-purple-500 flex items-center justify-center">
                <i class="fas fa-user-shield text-white"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-green-700 font-medium">Karyawan</p>
                <p class="text-2xl font-bold text-gray-900">
                    {{ \App\Models\User::where('role', 'karyawan')->count() }}
                </p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-green-500 flex items-center justify-center">
                <i class="fas fa-user-tie text-white"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl p-4 border border-yellow-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-yellow-700 font-medium">Verified</p>
                <p class="text-2xl font-bold text-gray-900">
                    {{ \App\Models\User::whereNotNull('email_verified_at')->count() }}
                </p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-yellow-500 flex items-center justify-center">
                <i class="fas fa-check-circle text-white"></i>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(userId, userName) {
    if (confirm(`Apakah Anda yakin ingin menghapus user "${userName}"?`)) {
        // In production, you would make an AJAX request here
        alert('Fitur hapus user akan diimplementasikan kemudian.');
    }
}
</script>

<style>
.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
}

.page-item {
    margin: 0 2px;
}

.page-link {
    display: block;
    padding: 8px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    color: #374151;
    text-decoration: none;
    transition: all 0.2s;
}

.page-link:hover {
    background-color: #f3f4f6;
    border-color: #d1d5db;
}

.page-item.active .page-link {
    background-color: #3b82f6;
    border-color: #3b82f6;
    color: white;
}

.page-item.disabled .page-link {
    color: #9ca3af;
    cursor: not-allowed;
    background-color: #f9fafb;
}
</style>
@endsection