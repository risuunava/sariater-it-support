@extends('layouts.app')

@section('title', 'Analytics')
@section('page-title', 'Analytics Saya')
@section('page-description', 'Analisis data dan statistik laporan pribadi')

@section('header-actions')
<div class="flex items-center space-x-3">
    <a href="{{ route('karyawan.dashboard') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Dashboard
    </a>
    <a href="{{ route('karyawan.tickets.export') }}" class="btn-primary">
        <i class="fas fa-download mr-2"></i>Export Data
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
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $tickets->count() }}</p>
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
                            $completed = $tickets->where('status', 'done')->count();
                            $rate = $tickets->count() > 0 ? round(($completed / $tickets->count()) * 100) : 0;
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
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $resolutionData['avg_time'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">jam</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-100 to-purple-50 flex items-center justify-center">
                    <i class="fas fa-stopwatch text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Fastest</span>
                    <span class="font-medium text-gray-900">{{ $resolutionData['fastest'] }} jam</span>
                </div>
                <div class="flex justify-between text-sm mt-1">
                    <span class="text-gray-600">Slowest</span>
                    <span class="font-medium text-gray-900">{{ $resolutionData['slowest'] }} jam</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Active Tickets</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $tickets->whereIn('status', ['pending', 'progress'])->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-100 to-yellow-50 flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm text-gray-600">Current Status</div>
                <div class="flex space-x-2 mt-2">
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                        Pending: {{ $tickets->where('status', 'pending')->count() }}
                    </span>
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                        Progress: {{ $tickets->where('status', 'progress')->count() }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Satisfaction Score</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        @php
                            $completed = $tickets->where('status', 'done')->count();
                            $score = $completed > 0 ? min(5, 3 + ($completed / $tickets->count()) * 2) : 0;
                            echo round($score, 1);
                        @endphp
                    </p>
                    <p class="text-xs text-gray-500 mt-1">/5.0</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center">
                    <i class="fas fa-star text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Rating</span>
                    <span class="font-medium text-gray-900">
                        @php
                            $stars = round($score);
                            echo str_repeat('★', $stars) . str_repeat('☆', 5 - $stars);
                        @endphp
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Trends -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-heading text-lg font-bold text-gray-900">Monthly Trends</h3>
                    <p class="text-sm text-gray-500 mt-1">6 bulan terakhir</p>
                </div>
            </div>
            
            <div class="h-80">
                <canvas id="monthlyChart"></canvas>
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
                @foreach($categoryData as $category => $count)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-900">{{ $category }}</span>
                    <span class="text-sm text-gray-500">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Resolution Time Analysis -->
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-heading text-lg font-bold text-gray-900">Resolution Time Analysis</h3>
                <p class="text-sm text-gray-500 mt-1">Distribusi waktu penyelesaian laporan</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Distribution Stats -->
            <div>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Under 1 hour</span>
                            <span class="font-medium text-gray-900">{{ $resolutionData['distribution']['under_1h'] ?? 0 }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="h-full rounded-full bg-gradient-to-r from-green-400 to-emerald-500" 
                                 style="width: {{ $tickets->count() > 0 ? (($resolutionData['distribution']['under_1h'] ?? 0) / $tickets->count()) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">1-4 hours</span>
                            <span class="font-medium text-gray-900">{{ $resolutionData['distribution']['1_4h'] ?? 0 }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="h-full rounded-full bg-gradient-to-r from-blue-400 to-blue-500" 
                                 style="width: {{ $tickets->count() > 0 ? (($resolutionData['distribution']['1_4h'] ?? 0) / $tickets->count()) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">4-24 hours</span>
                            <span class="font-medium text-gray-900">{{ $resolutionData['distribution']['4_24h'] ?? 0 }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="h-full rounded-full bg-gradient-to-r from-yellow-400 to-yellow-500" 
                                 style="width: {{ $tickets->count() > 0 ? (($resolutionData['distribution']['4_24h'] ?? 0) / $tickets->count()) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Over 24 hours</span>
                            <span class="font-medium text-gray-900">{{ $resolutionData['distribution']['over_24h'] ?? 0 }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="h-full rounded-full bg-gradient-to-r from-red-400 to-red-500" 
                                 style="width: {{ $tickets->count() > 0 ? (($resolutionData['distribution']['over_24h'] ?? 0) / $tickets->count()) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Summary Stats -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6">
                <h4 class="font-heading font-semibold text-gray-900 mb-4">Summary</h4>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Average Time</span>
                        <span class="font-medium text-gray-900">{{ $resolutionData['avg_time'] }} jam</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Fastest Resolution</span>
                        <span class="font-medium text-gray-900">{{ $resolutionData['fastest'] }} jam</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Slowest Resolution</span>
                        <span class="font-medium text-gray-900">{{ $resolutionData['slowest'] }} jam</span>
                    </div>
                    <div class="pt-3 border-t border-blue-200">
                        <p class="text-sm text-gray-600 mb-2">Efficiency Level</p>
                        @php
                            $efficiency = '';
                            if ($resolutionData['avg_time'] < 2) {
                                $efficiency = 'Excellent';
                                $color = 'text-green-600 bg-green-100';
                            } elseif ($resolutionData['avg_time'] < 6) {
                                $efficiency = 'Good';
                                $color = 'text-blue-600 bg-blue-100';
                            } elseif ($resolutionData['avg_time'] < 12) {
                                $efficiency = 'Average';
                                $color = 'text-yellow-600 bg-yellow-100';
                            } else {
                                $efficiency = 'Needs Improvement';
                                $color = 'text-red-600 bg-red-100';
                            }
                        @endphp
                        <span class="px-3 py-1 text-sm rounded-full {{ $color }} font-medium">
                            {{ $efficiency }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Trends -->
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-heading text-lg font-bold text-gray-900">Performance Trends</h3>
                <p class="text-sm text-gray-500 mt-1">8 minggu terakhir</p>
            </div>
        </div>
        
        <div class="h-80">
            <canvas id="performanceChart"></canvas>
        </div>
        
        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Minggu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Tickets</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completion Rate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg. Resolution</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($performanceTrends as $trend)
                    @php
                        $performance = '';
                        if ($trend['completion_rate'] >= 80) {
                            $performance = 'Excellent';
                            $color = 'bg-green-100 text-green-800';
                        } elseif ($trend['completion_rate'] >= 60) {
                            $performance = 'Good';
                            $color = 'bg-blue-100 text-blue-800';
                        } elseif ($trend['completion_rate'] >= 40) {
                            $performance = 'Average';
                            $color = 'bg-yellow-100 text-yellow-800';
                        } else {
                            $performance = 'Needs Improvement';
                            $color = 'bg-red-100 text-red-800';
                        }
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $trend['week'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $trend['tickets'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $trend['completion_rate'] }}%</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $trend['avg_resolution'] }} jam</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full {{ $color }}">
                                {{ $performance }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyData = @json($monthlyData);
    
    if (monthlyCtx) {
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: monthlyData.map(item => item.month),
                datasets: [
                    {
                        label: 'Total',
                        data: monthlyData.map(item => item.total),
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Done',
                        data: monthlyData.map(item => item.done),
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 1
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
    }

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryData = @json($categoryData);
    
    if (categoryCtx) {
        const categoryLabels = Object.keys(categoryData);
        const categoryValues = Object.values(categoryData);
        
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryValues,
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(107, 114, 128, 0.8)'
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
    }

    // Performance Chart
    const performanceCtx = document.getElementById('performanceChart').getContext('2d');
    const performanceData = @json($performanceTrends);
    
    if (performanceCtx) {
        new Chart(performanceCtx, {
            type: 'line',
            data: {
                labels: performanceData.map(item => item.week),
                datasets: [
                    {
                        label: 'Completion Rate (%)',
                        data: performanceData.map(item => item.completion_rate),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Avg. Resolution (hours)',
                        data: performanceData.map(item => item.avg_resolution),
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Completion Rate (%)'
                        },
                        min: 0,
                        max: 100
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Resolution Time (hours)'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                        min: 0
                    },
                }
            }
        });
    }
});
</script>
@endsection