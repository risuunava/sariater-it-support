@extends('layouts.app')

@section('title', 'Settings')
@section('page-title', 'Pengaturan Sistem')
@section('page-description', 'Kelola pengaturan sistem IT Support')

@section('header-actions')
<div class="flex items-center space-x-3">
    <a href="{{ route('admin.dashboard') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Dashboard
    </a>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Main Settings -->
    <div class="lg:col-span-2 space-y-6">
        <!-- System Settings -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                    <i class="fas fa-cog text-white"></i>
                </div>
                <div>
                    <h3 class="font-heading font-semibold text-gray-900">System Settings</h3>
                    <p class="text-sm text-gray-600">Pengaturan dasar sistem</p>
                </div>
            </div>
            
            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Sistem
                        </label>
                        <input type="text" 
                               value="SARIATER IT SUPPORT"
                               class="input-modern w-full">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email Support
                        </label>
                        <input type="email" 
                               value="support@sariater.com"
                               class="input-modern w-full">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Telepon Darurat
                        </label>
                        <input type="text" 
                               value="(021) 1234-5678"
                               class="input-modern w-full">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jam Operasional
                        </label>
                        <input type="text" 
                               value="08:00 - 17:00 (Senin - Jumat)"
                               class="input-modern w-full">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Sistem
                    </label>
                    <textarea class="input-modern w-full" rows="3">
Sistem pelaporan IT Support internal perusahaan untuk menangani masalah teknis secara efisien dan terstruktur.
                    </textarea>
                </div>
                
                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Notification Settings -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                    <i class="fas fa-bell text-white"></i>
                </div>
                <div>
                    <h3 class="font-heading font-semibold text-gray-900">Notification Settings</h3>
                    <p class="text-sm text-gray-600">Pengaturan notifikasi sistem</p>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-envelope text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Email Notifications</p>
                            <p class="text-xs text-gray-500">Kirim notifikasi via email</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
                
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                            <i class="fas fa-ticket-alt text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">New Ticket Alerts</p>
                            <p class="text-xs text-gray-500">Notifikasi laporan baru</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>
                
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-yellow-100 flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Urgent Ticket Alerts</p>
                            <p class="text-xs text-gray-500">Notifikasi laporan mendesak</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-600"></div>
                    </label>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Column - Quick Settings & Info -->
    <div class="space-y-6">
        <!-- System Info -->
        <div class="bg-gradient-to-br from-gray-900 to-blue-900 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div>
                    <h3 class="font-heading font-semibold text-lg">System Information</h3>
                    <p class="text-blue-100 text-sm">Info sistem saat ini</p>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-blue-200">Versi Sistem</span>
                    <span class="font-medium">v2.0.1</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-blue-200">Laravel Version</span>
                    <span class="font-medium">12.44.0</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-blue-200">PHP Version</span>
                    <span class="font-medium">8.2.12</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-blue-200">Database</span>
                    <span class="font-medium">MySQL 8.0</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-blue-200">Server Time</span>
                    <span class="font-medium">{{ now()->format('H:i:s') }}</span>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-blue-800">
                <div class="text-center">
                    <p class="text-sm text-blue-300">Last Updated</p>
                    <p class="font-medium">{{ now()->format('d M Y') }}</p>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <h3 class="font-heading font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <button onclick="clearCache()" 
                       class="w-full flex items-center justify-between p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition group">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center group-hover:bg-red-200 transition">
                            <i class="fas fa-broom text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Clear Cache</p>
                            <p class="text-xs text-gray-500">Hapus cache sistem</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </button>
                
                <button onclick="backupDatabase()" 
                       class="w-full flex items-center justify-between p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition group">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center group-hover:bg-green-200 transition">
                            <i class="fas fa-database text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Backup Database</p>
                            <p class="text-xs text-gray-500">Buat backup data</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </button>
                
                <button onclick="showLogs()" 
                       class="w-full flex items-center justify-between p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition group">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition">
                            <i class="fas fa-scroll text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">View Logs</p>
                            <p class="text-xs text-gray-500">Lihat log sistem</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </button>
                
                <button onclick="systemHealth()" 
                       class="w-full flex items-center justify-between p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition group">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center group-hover:bg-purple-200 transition">
                            <i class="fas fa-heartbeat text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">System Health</p>
                            <p class="text-xs text-gray-500">Cek kesehatan sistem</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </button>
            </div>
        </div>
        
        <!-- Danger Zone -->
        <div class="bg-white rounded-2xl shadow-soft p-6 border border-red-200">
            <h3 class="font-heading font-semibold text-red-800 mb-4">Danger Zone</h3>
            <div class="space-y-3">
                <button onclick="resetSystem()" 
                       class="w-full flex items-center justify-between p-3 rounded-xl bg-red-50 hover:bg-red-100 transition group">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center group-hover:bg-red-200 transition">
                            <i class="fas fa-redo text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-red-900">Reset System</p>
                            <p class="text-xs text-red-600">Reset semua data (demo)</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-red-400"></i>
                </button>
                
                <button onclick="purgeOldData()" 
                       class="w-full flex items-center justify-between p-3 rounded-xl bg-red-50 hover:bg-red-100 transition group">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center group-hover:bg-red-200 transition">
                            <i class="fas fa-trash text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-red-900">Purge Old Data</p>
                            <p class="text-xs text-red-600">Hapus data lama</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-red-400"></i>
                </button>
            </div>
            
            <div class="mt-4 p-3 bg-red-50 rounded-lg">
                <p class="text-xs text-red-700">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    <strong>Warning:</strong> Actions in this section are irreversible. Use with caution.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function clearCache() {
    if (confirm('Clear system cache?')) {
        alert('Cache cleared successfully!');
    }
}

function backupDatabase() {
    alert('Backup process started. You will receive an email when completed.');
}

function showLogs() {
    alert('Opening system logs...');
}

function systemHealth() {
    alert('System health check completed. All systems are operational.');
}

function resetSystem() {
    if (confirm('WARNING: This will reset all system data. Are you absolutely sure?')) {
        alert('System reset initiated (demo mode only).');
    }
}

function purgeOldData() {
    if (confirm('Delete all tickets older than 1 year?')) {
        alert('Old data purge completed.');
    }
}
</script>
@endsection