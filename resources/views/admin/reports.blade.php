@extends('layouts.app')

@section('title', 'Reports')
@section('page-title', 'Laporan & Analytics')
@section('page-description', 'Generate dan export laporan detail')

@section('header-actions')
<div class="flex items-center space-x-3">
    <a href="{{ route('admin.dashboard') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Dashboard
    </a>
</div>
@endsection

@section('content')
<!-- Report Generator -->
<div class="mb-6">
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <h3 class="font-heading font-semibold text-gray-900 mb-4">Generate Report</h3>
        
        <form action="{{ route('admin.reports') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Report Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Laporan</label>
                    <select name="type" class="input-modern w-full" onchange="this.form.submit()">
                        <option value="daily" {{ $reportType == 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="technician" {{ $reportType == 'technician' ? 'selected' : '' }}>Per Teknisi</option>
                        <option value="status" {{ $reportType == 'status' ? 'selected' : '' }}>Per Status</option>
                    </select>
                </div>
                
                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" 
                           name="date_from" 
                           value="{{ $dateFrom }}"
                           class="input-modern w-full"
                           onchange="this.form.submit()">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" 
                           name="date_to" 
                           value="{{ $dateTo }}"
                           class="input-modern w-full"
                           onchange="this.form.submit()">
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Report Summary -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-blue-700 font-medium">Total Laporan</p>
                <p class="text-2xl font-bold text-gray-900">{{ $reportData['summary']['total_tickets'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center">
                <i class="fas fa-file-alt text-white"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl p-4 border border-yellow-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-yellow-700 font-medium">Pending</p>
                <p class="text-2xl font-bold text-gray-900">{{ $reportData['summary']['pending'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-yellow-500 flex items-center justify-center">
                <i class="fas fa-clock text-white"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-green-700 font-medium">Selesai</p>
                <p class="text-2xl font-bold text-gray-900">{{ $reportData['summary']['done'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-green-500 flex items-center justify-center">
                <i class="fas fa-check-circle text-white"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-purple-700 font-medium">Avg. Time</p>
                <p class="text-2xl font-bold text-gray-900">{{ round($reportData['summary']['avg_resolution_time'], 1) }}</p>
                <p class="text-xs text-gray-500">jam</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-purple-500 flex items-center justify-center">
                <i class="fas fa-stopwatch text-white"></i>
            </div>
        </div>
    </div>
</div>

<!-- Report Content -->
<div class="bg-white rounded-2xl shadow-soft overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-heading text-xl font-bold text-gray-900">
                    @switch($reportType)
                        @case('daily')
                            Laporan Harian
                            @break
                        @case('technician')
                            Laporan Per Teknisi
                            @break
                        @case('status')
                            Laporan Per Status
                            @break
                        @default
                            Laporan
                    @endswitch
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Periode: {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
                </p>
            </div>
            
            <div class="flex items-center space-x-3">
                <button onclick="window.print()" class="btn-secondary">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
                <a href="{{ route('admin.tickets.export') }}?date_from={{ $dateFrom }}&date_to={{ $dateTo }}" 
                   class="btn-primary">
                    <i class="fas fa-download mr-2"></i>Export CSV
                </a>
            </div>
        </div>
    </div>
    
    <div class="p-6">
        @if($reportType == 'daily')
        <!-- Daily Report -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pending</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selesai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completion Rate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($reportData['data'] as $item)
                    @php
                        $completionRate = $item->total > 0 ? round(($item->done / $item->total) * 100) : 0;
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($item->date)->format('l') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->total }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->pending }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->progress }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->done }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900 mr-2">{{ $completionRate }}%</div>
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="h-full rounded-full bg-gradient-to-r from-green-400 to-emerald-500" 
                                         style="width: {{ $completionRate }}%"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">TOTAL</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $reportData['summary']['total_tickets'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $reportData['summary']['pending'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $reportData['summary']['progress'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $reportData['summary']['done'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">
                                @php
                                    $totalRate = $reportData['summary']['total_tickets'] > 0 
                                        ? round(($reportData['summary']['done'] / $reportData['summary']['total_tickets']) * 100) 
                                        : 0;
                                @endphp
                                {{ $totalRate }}%
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        @elseif($reportType == 'technician')
        <!-- Technician Report -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teknisi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Tickets</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completion Rate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg. Resolution Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($reportData['data'] as $item)
                    @php
                        $completionRate = $item->total > 0 ? round(($item->completed / $item->total) * 100) : 0;
                        $performance = '';
                        $performanceColor = '';
                        
                        if ($completionRate >= 90) {
                            $performance = 'Excellent';
                            $performanceColor = 'bg-green-100 text-green-800';
                        } elseif ($completionRate >= 70) {
                            $performance = 'Good';
                            $performanceColor = 'bg-blue-100 text-blue-800';
                        } elseif ($completionRate >= 50) {
                            $performance = 'Average';
                            $performanceColor = 'bg-yellow-100 text-yellow-800';
                        } else {
                            $performance = 'Needs Improvement';
                            $performanceColor = 'bg-red-100 text-red-800';
                        }
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-medium text-xs mr-3">
                                    {{ substr($item->technician, 0, 1) }}
                                </div>
                                <div class="text-sm font-medium text-gray-900">{{ $item->technician }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->total }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->completed }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900 mr-2">{{ $completionRate }}%</div>
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="h-full rounded-full bg-gradient-to-r from-green-400 to-emerald-500" 
                                         style="width: {{ $completionRate }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ round($item->avg_resolution_time, 1) }} jam</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full {{ $performanceColor }}">
                                {{ $performance }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @elseif($reportType == 'status')
        <!-- Status Report -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($reportData['data'] as $item)
            <div class="bg-gradient-to-br 
                @if($item->status == 'pending') from-yellow-50 to-yellow-100 border-yellow-200
                @elseif($item->status == 'progress') from-blue-50 to-blue-100 border-blue-200
                @else from-green-50 to-green-100 border-green-200 @endif 
                rounded-xl p-6 border">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="font-heading font-semibold text-gray-900">
                            @if($item->status == 'pending') Pending
                            @elseif($item->status == 'progress') Progress
                            @else Selesai @endif
                        </h4>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $item->count }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl 
                        @if($item->status == 'pending') bg-yellow-500
                        @elseif($item->status == 'progress') bg-blue-500
                        @else bg-green-500 @endif 
                        flex items-center justify-center">
                        <i class="fas 
                            @if($item->status == 'pending') fa-clock
                            @elseif($item->status == 'progress') fa-cogs
                            @else fa-check-circle @endif 
                            text-white"></i>
                    </div>
                </div>
                
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span>Latest:</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($item->latest)->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Oldest:</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($item->oldest)->format('d M Y') }}</span>
                    </div>
                    <div class="pt-2 border-t">
                        <span>Percentage:</span>
                        <span class="font-medium float-right">
                            @php
                                $percentage = $reportData['summary']['total_tickets'] > 0 
                                    ? round(($item->count / $reportData['summary']['total_tickets']) * 100) 
                                    : 0;
                            @endphp
                            {{ $percentage }}%
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Status Distribution Chart -->
        <div class="mt-6">
            <div class="bg-white rounded-xl p-6 border border-gray-200">
                <h4 class="font-heading font-semibold text-gray-900 mb-4">Status Distribution</h4>
                <div class="h-64">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@if($reportType == 'status')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('statusChart').getContext('2d');
    const statusData = @json($reportData['data']);
    
    const labels = statusData.map(item => {
        if(item.status == 'pending') return 'Pending';
        if(item.status == 'progress') return 'Progress';
        return 'Selesai';
    });
    
    const data = statusData.map(item => item.count);
    
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
});
</script>
@endif

<!-- Print Styles -->
<style>
@media print {
    nav, footer, .header-actions, button, a {
        display: none !important;
    }
    
    body {
        background: white !important;
    }
    
    .rounded-2xl, .rounded-xl {
        border-radius: 0 !important;
        box-shadow: none !important;
        border: 1px solid #e5e7eb !important;
    }
    
    .bg-gradient-to-r, .gradient-bg {
        background: #f8fafc !important;
        color: #1e293b !important;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    th, td {
        border: 1px solid #e5e7eb;
        padding: 8px;
    }
}
</style>
@endsection