<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    // Dashboard admin dengan semua fitur - PERBAIKAN BAGIAN URGENT
    public function dashboard(Request $request)
    {
        // CEK ROLE MANUAL
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        // Get filter parameters
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $period = $request->get('period', '7days');

        // Get statistics
        $statistics = $this->getStatistics($dateFrom, $dateTo);

        // Get chart data
        $chartData = $this->getChartData($period);

        // Get recent tickets
        $recentTickets = Ticket::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ============================================================
        // PERBAIKAN BESAR: LOGIKA URGENT TICKETS SAMA DENGAN KARYAWAN
        // Ticket masuk urgent jika:
        // 1. Priority = 'high' (mendesak) ATAU
        // 2. Pending > 24 jam
        // ============================================================
        $urgentTickets = Ticket::where('status', 'pending')
            ->where(function($query) {
                $query->where('priority', 'high') // Priority tinggi
                      ->orWhere('created_at', '<', now()->subDay()); // atau pending > 24 jam
            })
            ->with('user')
            ->orderByRaw("CASE WHEN priority = 'high' THEN 0 ELSE 1 END") // Priority tinggi di atas
            ->orderBy('created_at', 'asc') // Yang paling lama di atas
            ->limit(5)
            ->get();

        // Get top technicians
        $topTechnicians = Ticket::whereNotNull('technician')
            ->select('technician', DB::raw('count(*) as total_tickets'), DB::raw('sum(case when status = "done" then 1 else 0 end) as completed'))
            ->groupBy('technician')
            ->orderBy('total_tickets', 'desc')
            ->limit(5)
            ->get();

        // Calculate average resolution time
        $avgResolutionTime = Ticket::where('status', 'done')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours'))
            ->first()->avg_hours ?? 0;

        // Get system metrics
        $systemMetrics = [
            'total_users' => User::count(),
            'total_tickets' => $statistics['total'],
            'tickets_today' => Ticket::whereDate('created_at', today())->count(),
            'completion_rate' => $statistics['total'] > 0 ? 
                round(($statistics['done'] / $statistics['total']) * 100, 1) : 0,
        ];

        return view('admin.dashboard', compact(
            'statistics',
            'chartData',
            'recentTickets',
            'urgentTickets',
            'topTechnicians',
            'avgResolutionTime',
            'systemMetrics',
            'dateFrom',
            'dateTo',
            'period'
        ));
    }

    // Semua tickets dengan search dan filter
    public function tickets(Request $request)
    {
        // CEK ROLE MANUAL
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $query = Ticket::with('user');

        // Search
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('technician', 'like', "%{$search}%")
                  ->orWhere('admin_response', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Filter technician
        if ($technician = $request->get('technician')) {
            $query->where('technician', 'like', "%{$technician}%");
        }

        // Filter date
        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Sort
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'status':
                $query->orderBy('status', 'asc')->orderBy('created_at', 'desc');
                break;
            case 'technician':
                $query->orderBy('technician', 'asc')->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->get('per_page', 20);
        $tickets = $query->paginate($perPage);

        // Get unique technicians for filter
        $technicians = Ticket::whereNotNull('technician')
            ->distinct('technician')
            ->pluck('technician')
            ->sort();

        // Get statistics for current filter
        $filterStats = $this->getStatistics($dateFrom, $dateTo);

        return view('admin.tickets', compact(
            'tickets',
            'technicians',
            'filterStats',
            'search',
            'status',
            'technician',
            'dateFrom',
            'dateTo',
            'sort',
            'perPage'
        ));
    }

    // Detail ticket
    public function showTicket($id)
    {
        // CEK ROLE MANUAL
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $ticket = Ticket::with('user')->findOrFail($id);
        
        // Get all technicians from tickets
        $technicians = Ticket::whereNotNull('technician')
            ->distinct('technician')
            ->pluck('technician')
            ->sort()
            ->toArray();

        // Add current user if not in list
        if (!in_array($user->name, $technicians)) {
            $technicians[] = $user->name;
        }
        sort($technicians);

        // Get ticket history (if we had a history table)
        $ticketHistory = [];

        return view('admin.ticket-show', compact('ticket', 'technicians', 'ticketHistory'));
    }

    // Update status ticket
    public function updateStatus(Request $request, $id)
    {
        // CEK ROLE MANUAL
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'status' => 'required|in:pending,progress,done',
            'technician' => 'nullable|string|max:255',
            'admin_response' => 'nullable|string',
        ]);

        $ticket = Ticket::findOrFail($id);
        
        $updateData = [
            'status' => $request->status,
            'admin_response' => $request->admin_response,
        ];

        if ($request->technician) {
            $updateData['technician'] = $request->technician;
        }

        $ticket->update($updateData);

        return redirect()->route('admin.ticket.show', $id)
            ->with('success', 'Status laporan berhasil diperbarui.');
    }

    // Export tickets to CSV
    public function exportTickets(Request $request)
    {
        // CEK ROLE MANUAL
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $query = Ticket::with('user');

        // Apply filters
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('technician', 'like', "%{$search}%")
                  ->orWhere('admin_response', 'like', "%{$search}%");
            });
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($technician = $request->get('technician')) {
            $query->where('technician', 'like', "%{$technician}%");
        }
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $tickets = $query->orderBy('created_at', 'desc')->get();

        $filename = 'tickets_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header
        fputcsv($output, [
            'ID', 
            'Judul', 
            'Deskripsi', 
            'Status', 
            'Teknisi', 
            'Respon Admin', 
            'Pelapor', 
            'Email Pelapor',
            'Tanggal Dibuat',
            'Tanggal Diupdate'
        ]);
        
        // Data
        foreach ($tickets as $ticket) {
            fputcsv($output, [
                $ticket->id,
                $ticket->title,
                $ticket->description,
                $ticket->status,
                $ticket->technician ?? '-',
                $ticket->admin_response ?? '-',
                $ticket->user->name,
                $ticket->user->email,
                $ticket->created_at->format('Y-m-d H:i:s'),
                $ticket->updated_at->format('Y-m-d H:i:s')
            ]);
        }
        
        fclose($output);
        exit;
    }

    // Analytics page
    public function analytics(Request $request)
    {
        // CEK ROLE MANUAL
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $period = $request->get('period', '30days');
        
        // Get chart data
        $chartData = $this->getChartData($period);
        
        // Get daily stats for the selected period
        $dailyStats = $this->getDailyStats($period);
        
        // Get technician performance
        $technicianPerformance = $this->getTechnicianPerformance();
        
        // Get category distribution
        $categoryDistribution = $this->getCategoryDistribution();
        
        // Get resolution time trends
        $resolutionTrends = $this->getResolutionTimeTrends();

        return view('admin.analytics', compact(
            'chartData',
            'dailyStats',
            'technicianPerformance',
            'categoryDistribution',
            'resolutionTrends',
            'period'
        ));
    }

    // Reports page
    public function reports(Request $request)
    {
        // CEK ROLE MANUAL
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $reportType = $request->get('type', 'daily');
        $dateFrom = $request->get('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $reportData = $this->generateReport($reportType, $dateFrom, $dateTo);

        return view('admin.reports', compact(
            'reportData',
            'reportType',
            'dateFrom',
            'dateTo'
        ));
    }

    // Settings page
    public function settings()
    {
        // CEK ROLE MANUAL
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        return view('admin.settings');
    }

    // Users management
    public function users(Request $request)
    {
        // CEK ROLE MANUAL
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $search = $request->get('search');
        
        $users = User::when($search, function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users', compact('users', 'search'));
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    private function getStatistics($dateFrom = null, $dateTo = null)
    {
        $query = Ticket::query();
        
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        
        $total = $query->count();
        $pending = $query->where('status', 'pending')->count();
        $progress = $query->where('status', 'progress')->count();
        $done = $query->where('status', 'done')->count();
        
        return [
            'total' => $total,
            'pending' => $pending,
            'progress' => $progress,
            'done' => $done,
        ];
    }

    private function getChartData($period = '30days')
    {
        $data = [
            'labels' => [],
            'pending' => [],
            'progress' => [],
            'done' => []
        ];
        
        switch ($period) {
            case 'today':
                $days = 1;
                break;
            case '7days':
                $days = 7;
                break;
            case '30days':
                $days = 30;
                break;
            case 'month':
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                $days = $start->diffInDays($end) + 1;
                break;
            default:
                $days = 30;
        }
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data['labels'][] = $date->format('d M');
            
            $pending = Ticket::whereDate('created_at', $date->format('Y-m-d'))
                ->where('status', 'pending')
                ->count();
            $progress = Ticket::whereDate('created_at', $date->format('Y-m-d'))
                ->where('status', 'progress')
                ->count();
            $done = Ticket::whereDate('created_at', $date->format('Y-m-d'))
                ->where('status', 'done')
                ->count();
                
            $data['pending'][] = $pending;
            $data['progress'][] = $progress;
            $data['done'][] = $done;
        }
        
        return $data;
    }

    private function getDailyStats($period)
    {
        switch ($period) {
            case 'today':
                $start = now()->startOfDay();
                $end = now()->endOfDay();
                break;
            case '7days':
                $start = now()->subDays(6)->startOfDay();
                $end = now()->endOfDay();
                break;
            case '30days':
                $start = now()->subDays(29)->startOfDay();
                $end = now()->endOfDay();
                break;
            case 'month':
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                break;
            default:
                $start = now()->subDays(29)->startOfDay();
                $end = now()->endOfDay();
        }

        $stats = Ticket::whereBetween('created_at', [$start, $end])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN status = "progress" THEN 1 ELSE 0 END) as progress'),
                DB::raw('SUM(CASE WHEN status = "done" THEN 1 ELSE 0 END) as done')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $stats;
    }

    private function getTechnicianPerformance()
    {
        return Ticket::whereNotNull('technician')
            ->select(
                'technician',
                DB::raw('COUNT(*) as total_tickets'),
                DB::raw('SUM(CASE WHEN status = "done" THEN 1 ELSE 0 END) as completed'),
                DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours')
            )
            ->groupBy('technician')
            ->orderBy('total_tickets', 'desc')
            ->get();
    }

    private function getCategoryDistribution()
    {
        return [
            ['category' => 'Hardware', 'count' => Ticket::where('title', 'like', '%printer%')
                ->orWhere('title', 'like', '%laptop%')
                ->orWhere('title', 'like', '%monitor%')
                ->orWhere('title', 'like', '%keyboard%')
                ->orWhere('title', 'like', '%mouse%')->count()],
            ['category' => 'Software', 'count' => Ticket::where('title', 'like', '%software%')
                ->orWhere('title', 'like', '%windows%')
                ->orWhere('title', 'like', '%office%')
                ->orWhere('title', 'like', '%antivirus%')->count()],
            ['category' => 'Network', 'count' => Ticket::where('title', 'like', '%wifi%')
                ->orWhere('title', 'like', '%internet%')
                ->orWhere('title', 'like', '%network%')
                ->orWhere('title', 'like', '%email%')->count()],
            ['category' => 'Account', 'count' => Ticket::where('title', 'like', '%password%')
                ->orWhere('title', 'like', '%login%')
                ->orWhere('title', 'like', '%account%')
                ->orWhere('title', 'like', '%akses%')->count()],
        ];
    }

    private function getResolutionTimeTrends()
    {
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            
            $avgTime = Ticket::whereDate('updated_at', $date)
                ->where('status', 'done')
                ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours'))
                ->first()->avg_hours ?? 0;
            
            $data[] = [
                'date' => now()->subDays($i)->format('D'),
                'avg_hours' => round($avgTime, 1)
            ];
        }
        
        return $data;
    }

    private function generateReport($type, $dateFrom, $dateTo)
    {
        $query = Ticket::with('user')
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);

        switch ($type) {
            case 'daily':
                $report = $query->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending'),
                    DB::raw('SUM(CASE WHEN status = "progress" THEN 1 ELSE 0 END) as progress'),
                    DB::raw('SUM(CASE WHEN status = "done" THEN 1 ELSE 0 END) as done')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();
                break;

            case 'technician':
                $report = $query->whereNotNull('technician')
                    ->select(
                        'technician',
                        DB::raw('COUNT(*) as total'),
                        DB::raw('SUM(CASE WHEN status = "done" THEN 1 ELSE 0 END) as completed'),
                        DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_resolution_time')
                    )
                    ->groupBy('technician')
                    ->orderBy('total', 'desc')
                    ->get();
                break;

            case 'status':
                $report = $query->select(
                    'status',
                    DB::raw('COUNT(*) as count'),
                    DB::raw('MAX(created_at) as latest'),
                    DB::raw('MIN(created_at) as oldest')
                )
                ->groupBy('status')
                ->get();
                break;

            default:
                $report = collect();
        }

        // Summary statistics
        $summary = [
            'total_tickets' => $query->count(),
            'pending' => $query->where('status', 'pending')->count(),
            'progress' => $query->where('status', 'progress')->count(),
            'done' => $query->where('status', 'done')->count(),
            'avg_resolution_time' => $query->where('status', 'done')
                ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours'))
                ->first()->avg_hours ?? 0,
        ];

        return [
            'type' => $type,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'data' => $report,
            'summary' => $summary
        ];
    }
}