@extends('layouts.app')

@section('title', 'Analytics')
@section('page-title', 'Analytics')
@section('page-description', 'Analisis data dan statistik laporan IT Support')

@section('header-actions')
<div class="flex items-center space-x-3">
    <div class="relative">
        <select onchange="window.location.href = '{{ route('admin.analytics') }}?period=' + this.value" 
                class="input-modern py-2">
            <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hari Ini</option>
            <option value="7days" {{ $period == '7days' ? 'selected' : '' }}>7 Hari Terakhir</option>
            <option value="30days" {{ $period == '30days' ? 'selected' : '' }}>30 Hari Terakhir</option>
            <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Bulan Ini</option>
        </select>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Dashboard
    </a>
</div>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Tickets</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ array_sum($chartData['pending']) + array_sum($chartData['progress']) + array_sum($chartData['done']) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center">
                    <i class="fas fa-ticket-alt text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Completion Rate</span>
                    <span class="font-medium text-gray-900">
                        @php
                            $total = array_sum($chartData['done']) + array_sum($chartData['progress']) + array_sum($chartData['pending']);
                            $completed = array_sum($chartData['done']);
                            $rate = $total > 0 ? round(($completed / $total) * 100) : 0;
                        @endphp
                        {{ $rate }}%
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="h-full rounded-full bg-gradient-to-r from-green-400 to-emerald-500" style="width: {{ $rate }}%"></div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Avg. Resolution Time</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        @php
                            $avgTime = 0;
                            if(count($resolutionTrends) > 0) {
                                $avgTime = array_sum(array_column($resolutionTrends, 'avg_hours')) / count($resolutionTrends);
                            }
                            echo round($avgTime, 1);
                        @endphp
                    </p>
                    <p class="text-xs text-gray-500 mt-1">jam</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-100 to-purple-50 flex items-center justify-center">
                    <i class="fas fa-stopwatch text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Trend</span>
                    <span class="font-medium text-green-600">
                        @if(count($resolutionTrends) >= 2)
                            @php
                                $first = $resolutionTrends[0]['avg_hours'];
                                $last = $resolutionTrends[count($resolutionTrends)-1]['avg_hours'];
                                $change = $first > 0 ? (($last - $first) / $first) * 100 : 0;
                            @endphp
                            {{ $change >= 0 ? '+' : '' }}{{ round($change, 1) }}%
                        @else
                            -
                        @endif
                    </span>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Active Technicians</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ count($technicianPerformance) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center">
                    <i class="fas fa-user-cog text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm text-gray-600">Top Performer</div>
                <div class="text-sm font-medium text-gray-900 mt-1">
                    @if(count($technicianPerformance) > 0)
                        {{ $technicianPerformance[0]->technician }}
                    @else
                        -
                    @endif
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Peak Hours</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        @php
                            $hourlyData = [];
                            for($i = 0; $i < 24; $i++) {
                                $hourlyData[$i] = 0;
                            }
                            // This would come from actual data in production
                            echo '09:00 - 11:00';
                        @endphp
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-100 to-yellow-50 flex items-center justify-center">
                    <i class="fas fa-chart-line text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm text-gray-600">Most Active Time</div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Main Trend Chart -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-heading text-lg font-bold text-gray-900">Ticket Trends</h3>
                    <p class="text-sm text-gray-500 mt-1">Jumlah laporan per status ({{ $period }})</p>
                </div>
            </div>
            
            <div class="h-80">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
        
        <!-- Category Distribution -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-heading text-lg font-bold text-gray-900">Category Distribution</h3>
            </div>
            
            <div class="h-80">
                <canvas id="categoryChart"></canvas>
            </div>
            
            <div class="mt-6 grid grid-cols-2 gap-4">
                @foreach($categoryDistribution as $category)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-900">{{ $category['category'] }}</span>
                    <span class="text-sm text-gray-500">{{ $category['count'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Technician Performance -->
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-heading text-lg font-bold text-gray-900">Technician Performance</h3>
                <p class="text-sm text-gray-500 mt-1">Efisiensi dan produktivitas teknisi</p>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teknisi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Tickets</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completion Rate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg. Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($technicianPerformance as $tech)
                    @php
                        $completionRate = $tech->total_tickets > 0 ? round(($tech->completed / $tech->total_tickets) * 100) : 0;
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
                                    {{ substr($tech->technician, 0, 1) }}
                                </div>
                                <div class="text-sm font-medium text-gray-900">{{ $tech->technician }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $tech->total_tickets }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $tech->completed }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $completionRate }}%</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ round($tech->avg_hours, 1) }} jam</div>
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
        
        @if($technicianPerformance->isEmpty())
        <div class="text-center py-12">
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-cog text-gray-400 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada data teknisi</h3>
            <p class="text-gray-500">Tidak ada data performa teknisi untuk ditampilkan</p>
        </div>
        @endif
    </div>

    <!-- Resolution Time Trends -->
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-heading text-lg font-bold text-gray-900">Resolution Time Trends</h3>
                <p class="text-sm text-gray-500 mt-1">Rata-rata waktu penyelesaian laporan</p>
            </div>
        </div>
        
        <div class="h-64">
            <canvas id="resolutionChart"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Trend Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    const trendData = @json($chartData);
    
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: trendData.labels,
            datasets: [
                {
                    label: 'Pending',
                    data: trendData.pending,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Progress',
                    data: trendData.progress,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Selesai',
                    data: trendData.done,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryData = {
        labels: @json(array_column($categoryDistribution, 'category')),
        datasets: [{
            data: @json(array_column($categoryDistribution, 'count')),
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(139, 92, 246, 0.8)'
            ],
            borderWidth: 1
        }]
    };
    
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: categoryData,
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

    // Resolution Time Chart
    const resolutionCtx = document.getElementById('resolutionChart').getContext('2d');
    const resolutionData = @json($resolutionTrends);
    
    new Chart(resolutionCtx, {
        type: 'line',
        data: {
            labels: resolutionData.map(item => item.date),
            datasets: [{
                label: 'Average Resolution Time (hours)',
                data: resolutionData.map(item => item.avg_hours),
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Hours'
                    }
                }
            }
        }
    });
});
</script>
@endsection