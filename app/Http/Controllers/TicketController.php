<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TicketController extends Controller
{
    // Dashboard karyawan lengkap - PERBAIKAN BAGIAN URGENT
    public function dashboard(Request $request)
    {
        // CEK ROLE MANUAL
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->role !== 'karyawan') {
            abort(403, 'Akses ditolak.');
        }

        // Get filter parameters
        $status = $request->get('status');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $search = $request->get('search');

        // Base query
        $query = Ticket::where('user_id', $user->id);

        // Search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('technician', 'like', "%{$search}%")
                  ->orWhere('admin_response', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        // Filter date
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Get tickets
        $tickets = $query->orderBy('created_at', 'desc')->get();

        // Statistics
        $statistics = [
            'total' => Ticket::where('user_id', $user->id)->count(),
            'pending' => Ticket::where('user_id', $user->id)->where('status', 'pending')->count(),
            'progress' => Ticket::where('user_id', $user->id)->where('status', 'progress')->count(),
            'done' => Ticket::where('user_id', $user->id)->where('status', 'done')->count(),
        ];

        // Recent activity
        $recentActivity = Ticket::where('user_id', $user->id)
            ->where('status', '!=', 'pending')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // ============================================================
        // PERBAIKAN BESAR: LOGIKA URGENT TICKETS
        // Ticket masuk urgent jika:
        // 1. Priority = 'high' (mendesak) ATAU
        // 2. Pending > 24 jam
        // ============================================================
        $urgentTickets = Ticket::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where(function($query) {
                $query->where('priority', 'high') // Priority tinggi
                      ->orWhere('created_at', '<', now()->subDay()); // atau pending > 24 jam
            })
            ->orderByRaw("CASE WHEN priority = 'high' THEN 0 ELSE 1 END") // Priority tinggi di atas
            ->orderBy('created_at', 'asc') // Yang paling lama di atas
            ->get();

        // Performance metrics
        $performance = $this->calculateUserPerformance($user->id);

        return view('karyawan.dashboard', compact(
            'tickets',
            'statistics',
            'recentActivity',
            'urgentTickets',
            'performance',
            'status',
            'dateFrom',
            'dateTo',
            'search'
        ));
    }

    // Tampilkan form buat ticket
    public function create()
    {
        // CEK ROLE MANUAL
        $user = Auth::user();
        if ($user->role !== 'karyawan') {
            abort(403, 'Akses ditolak.');
        }

        return view('karyawan.ticket-create');
    }

    // ============================================================
    // SIMPAN TICKET BARU - CLEAN TANPA CATATAN SIMULASI
    // ============================================================
    public function store(Request $request)
    {
        // CEK ROLE MANUAL
        $user = Auth::user();
        if ($user->role !== 'karyawan') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'nullable|in:low,medium,high',
            'category' => 'nullable|string',
        ]);

        // LOGIKA SIMULASI URGENT (UNTUK TESTING)
        $simulateUrgent = $request->has('simulate_urgent');
        
        if ($simulateUrgent) {
            // Untuk testing: buat ticket dengan tanggal 3 hari lalu
            $createdAt = now()->subDays(3);
            
            $ticket = Ticket::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'description' => $request->description,
                'status' => 'pending',
                'priority' => $request->priority ?? 'medium',
                'category' => $request->category,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $message = '✅ Laporan berhasil dibuat (simulasi urgent). ';
            $message .= 'Akan muncul di bagian "Laporan Mendesak".';
        } else {
            // Normal: buat ticket dengan tanggal sekarang
            $ticket = Ticket::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'description' => $request->description,
                'status' => 'pending',
                'priority' => $request->priority ?? 'medium',
                'category' => $request->category,
            ]);

            $message = '✅ Laporan berhasil dibuat. Tim IT Support akan segera menindaklanjuti.';
            
            // Tambahkan pesan khusus untuk priority tinggi
            if ($request->priority === 'high') {
                $message .= ' 🚨 (PRIORITAS TINGGI: Akan langsung muncul di bagian "Laporan Mendesak")';
            }
        }

        return redirect()->route('karyawan.dashboard')
            ->with('success', $message);
    }

    // Tampilkan form edit ticket
    public function edit($id)
    {
        // CEK ROLE MANUAL
        $user = Auth::user();
        if ($user->role !== 'karyawan') {
            abort(403, 'Akses ditolak.');
        }

        $ticket = Ticket::where('user_id', $user->id)
            ->findOrFail($id);

        return view('karyawan.ticket-edit', compact('ticket'));
    }

    // Update ticket
    public function update(Request $request, $id)
    {
        // CEK ROLE MANUAL
        $user = Auth::user();
        if ($user->role !== 'karyawan') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $ticket = Ticket::where('user_id', $user->id)
            ->findOrFail($id);

        // Hanya bisa edit jika status masih pending
        if ($ticket->status !== 'pending') {
            return back()->withErrors([
                'message' => 'Laporan tidak bisa diedit karena sudah diproses.',
            ]);
        }

        $ticket->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('karyawan.dashboard')
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    // Detail ticket
    public function show($id)
    {
        // CEK ROLE MANUAL
        $user = Auth::user();
        if ($user->role !== 'karyawan') {
            abort(403, 'Akses ditolak.');
        }

        $ticket = Ticket::where('user_id', $user->id)
            ->findOrFail($id);

        // Get related tickets
        $relatedTickets = Ticket::where('user_id', $user->id)
            ->where('id', '!=', $id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('karyawan.ticket-show', compact('ticket', 'relatedTickets'));
    }

    // User profile
    public function profile()
    {
        // CEK ROLE MANUAL
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->role !== 'karyawan') {
            abort(403, 'Akses ditolak.');
        }

        $ticketStats = [
            'total' => Ticket::where('user_id', $user->id)->count(),
            'pending' => Ticket::where('user_id', $user->id)->where('status', 'pending')->count(),
            'progress' => Ticket::where('user_id', $user->id)->where('status', 'progress')->count(),
            'done' => Ticket::where('user_id', $user->id)->where('status', 'done')->count(),
        ];

        $recentTickets = Ticket::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('karyawan.profile', compact('user', 'ticketStats', 'recentTickets'));
    }

    // Update profile
    public function updateProfile(Request $request)
    {
        // CEK ROLE MANUAL
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->role !== 'karyawan') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
        ]);

        $user->forceFill([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
        ])->save();

        return redirect()->route('karyawan.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    // Change password
    public function changePassword(Request $request)
    {
        // CEK ROLE MANUAL
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->role !== 'karyawan') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->new_password),
        ])->save();

        return redirect()->route('karyawan.profile')
            ->with('success', 'Password berhasil diubah.');
    }

    // Export user tickets
    public function exportTickets(Request $request)
    {
        // CEK ROLE MANUAL
        $user = Auth::user();
        if ($user->role !== 'karyawan') {
            abort(403, 'Akses ditolak.');
        }

        $query = Ticket::where('user_id', $user->id);

        // Apply filters
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $tickets = $query->orderBy('created_at', 'desc')->get();

        $filename = 'my_tickets_' . date('Y-m-d_H-i-s') . '.csv';
        
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
            'Tanggal Dibuat',
            'Tanggal Diupdate',
            'Durasi (jam)'
        ]);
        
        // Data
        foreach ($tickets as $ticket) {
            $duration = $ticket->created_at->diffInHours($ticket->updated_at);
            
            fputcsv($output, [
                $ticket->id,
                $ticket->title,
                $ticket->description,
                $ticket->status,
                $ticket->technician ?? '-',
                $ticket->admin_response ?? '-',
                $ticket->created_at->format('Y-m-d H:i:s'),
                $ticket->updated_at->format('Y-m-d H:i:s'),
                $duration
            ]);
        }
        
        fclose($output);
        exit;
    }

    // Analytics for user
    public function analytics()
    {
        // CEK ROLE MANUAL
        $user = Auth::user();
        if ($user->role !== 'karyawan') {
            abort(403, 'Akses ditolak.');
        }

        $tickets = Ticket::where('user_id', $user->id)->get();

        // Monthly statistics
        $monthlyData = $this->getMonthlyStatistics($user->id);

        // Category distribution (simulated)
        $categoryData = $this->getCategoryDistribution($tickets);

        // Resolution time analysis
        $resolutionData = $this->getResolutionAnalysis($tickets);

        // Performance trends
        $performanceTrends = $this->getPerformanceTrends($user->id);

        return view('karyawan.analytics', compact(
            'tickets',
            'monthlyData',
            'categoryData',
            'resolutionData',
            'performanceTrends'
        ));
    }

    // Help & Support page
    public function help()
    {
        // CEK ROLE MANUAL
        $user = Auth::user();
        if ($user->role !== 'karyawan') {
            abort(403, 'Akses ditolak.');
        }

        $faqs = [
            [
                'question' => 'Berapa lama waktu respon IT Support?',
                'answer' => 'Tim IT Support merespon laporan dalam 2-4 jam kerja. Laporan prioritas tinggi akan diprioritaskan.'
            ],
            [
                'question' => 'Bagaimana cara melacak status laporan saya?',
                'answer' => 'Anda dapat melihat status laporan di dashboard atau halaman detail laporan. Notifikasi akan dikirim saat ada update.'
            ],
            [
                'question' => 'Kapan saya bisa mengedit laporan?',
                'answer' => 'Laporan hanya bisa diedit selama status masih "Pending". Setelah diproses, tidak dapat diedit.'
            ],
            [
                'question' => 'Bagaimana cara menghubungi IT Support darurat?',
                'answer' => 'Untuk masalah darurat, hubungi ext. 1234 atau email support@sariater.com'
            ],
        ];

        $guides = [
            ['title' => 'Cara Membuat Laporan yang Efektif', 'icon' => 'fas fa-file-alt'],
            ['title' => 'Troubleshooting Dasar Komputer', 'icon' => 'fas fa-desktop'],
            ['title' => 'Tips Keamanan Data', 'icon' => 'fas fa-shield-alt'],
            ['title' => 'Penggunaan Software Perusahaan', 'icon' => 'fas fa-cogs'],
        ];

        return view('karyawan.help', compact('faqs', 'guides'));
    }

    // Private helper methods
    private function calculateUserPerformance($userId)
    {
        $tickets = Ticket::where('user_id', $userId)->get();
        
        if ($tickets->isEmpty()) {
            return [
                'avg_response_time' => 0,
                'completion_rate' => 0,
                'satisfaction_score' => 0,
            ];
        }

        $doneTickets = $tickets->where('status', 'done');
        
        // Calculate average response time (from creation to first admin response)
        $avgResponseTime = $doneTickets->avg(function($ticket) {
            return $ticket->created_at->diffInHours($ticket->updated_at);
        });

        // Completion rate (percentage of done tickets)
        $completionRate = ($doneTickets->count() / $tickets->count()) * 100;

        // Simulated satisfaction score
        $satisfactionScore = min(5, 3 + ($completionRate / 100) * 2);

        return [
            'avg_response_time' => round($avgResponseTime, 1),
            'completion_rate' => round($completionRate, 1),
            'satisfaction_score' => round($satisfactionScore, 1),
        ];
    }

    private function getMonthlyStatistics($userId)
    {
        $data = [];
        $months = 6; // Last 6 months
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();
            
            $tickets = Ticket::where('user_id', $userId)
                ->whereBetween('created_at', [$start, $end])
                ->get();
            
            $data[] = [
                'month' => $month->format('M Y'),
                'total' => $tickets->count(),
                'pending' => $tickets->where('status', 'pending')->count(),
                'progress' => $tickets->where('status', 'progress')->count(),
                'done' => $tickets->where('status', 'done')->count(),
            ];
        }
        
        return $data;
    }

    private function getCategoryDistribution($tickets)
    {
        // Simulated categories based on ticket titles
        $categories = [
            'Hardware' => 0,
            'Software' => 0,
            'Network' => 0,
            'Account' => 0,
            'Other' => 0,
        ];
        
        foreach ($tickets as $ticket) {
            $title = strtolower($ticket->title);
            
            if (str_contains($title, 'printer') || str_contains($title, 'laptop') || 
                str_contains($title, 'monitor') || str_contains($title, 'keyboard') ||
                str_contains($title, 'mouse')) {
                $categories['Hardware']++;
            } elseif (str_contains($title, 'software') || str_contains($title, 'windows') || 
                     str_contains($title, 'office') || str_contains($title, 'antivirus')) {
                $categories['Software']++;
            } elseif (str_contains($title, 'wifi') || str_contains($title, 'internet') || 
                     str_contains($title, 'network') || str_contains($title, 'email')) {
                $categories['Network']++;
            } elseif (str_contains($title, 'password') || str_contains($title, 'login') || 
                     str_contains($title, 'account') || str_contains($title, 'akses')) {
                $categories['Account']++;
            } else {
                $categories['Other']++;
            }
        }
        
        return $categories;
    }

    private function getResolutionAnalysis($tickets)
    {
        $doneTickets = $tickets->where('status', 'done');
        
        if ($doneTickets->isEmpty()) {
            return [
                'avg_time' => 0,
                'fastest' => 0,
                'slowest' => 0,
                'distribution' => []
            ];
        }
        
        $resolutionTimes = $doneTickets->map(function($ticket) {
            return $ticket->created_at->diffInHours($ticket->updated_at);
        });
        
        return [
            'avg_time' => round($resolutionTimes->avg(), 1),
            'fastest' => $resolutionTimes->min(),
            'slowest' => $resolutionTimes->max(),
            'distribution' => [
                'under_1h' => $resolutionTimes->filter(fn($t) => $t < 1)->count(),
                '1_4h' => $resolutionTimes->filter(fn($t) => $t >= 1 && $t <= 4)->count(),
                '4_24h' => $resolutionTimes->filter(fn($t) => $t > 4 && $t <= 24)->count(),
                'over_24h' => $resolutionTimes->filter(fn($t) => $t > 24)->count(),
            ]
        ];
    }

    private function getPerformanceTrends($userId)
    {
        $trends = [];
        $weeks = 8; // Last 8 weeks
        
        for ($i = $weeks - 1; $i >= 0; $i--) {
            $week = now()->subWeeks($i);
            $start = $week->copy()->startOfWeek();
            $end = $week->copy()->endOfWeek();
            
            $tickets = Ticket::where('user_id', $userId)
                ->whereBetween('created_at', [$start, $end])
                ->get();
            
            $doneTickets = $tickets->where('status', 'done');
            $completionRate = $tickets->count() > 0 ? 
                ($doneTickets->count() / $tickets->count()) * 100 : 0;
            
            $trends[] = [
                'week' => 'W' . ($weeks - $i),
                'tickets' => $tickets->count(),
                'completion_rate' => round($completionRate, 1),
                'avg_resolution' => $doneTickets->isNotEmpty() ? 
                    round($doneTickets->avg(function($t) {
                        return $t->created_at->diffInHours($t->updated_at);
                    }), 1) : 0,
            ];
        }
        
        return $trends;
    }
}