<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'priority',
        'category',
        'technician',
        'admin_response',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ============================================================
    // HELPER METHODS UNTUK STATUS
    // ============================================================
    
    public function getStatusColor()
    {
        $colors = [
            'pending' => 'pending',
            'progress' => 'progress',
            'done' => 'done',
        ];
        return $colors[$this->status] ?? 'pending';
    }

    public function getStatusText()
    {
        $texts = [
            'pending' => 'Menunggu',
            'progress' => 'Dalam Proses',
            'done' => 'Selesai',
        ];
        return $texts[$this->status] ?? $this->status;
    }

    // ============================================================
    // HELPER METHODS UNTUK PRIORITY
    // ============================================================
    
    public function getPriorityText()
    {
        $texts = [
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
        ];
        return $texts[$this->priority] ?? 'Sedang';
    }

    public function getPriorityColor()
    {
        $colors = [
            'low' => 'bg-green-100 text-green-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'high' => 'bg-red-100 text-red-800',
        ];
        return $colors[$this->priority] ?? 'bg-yellow-100 text-yellow-800';
    }

    public function getPriorityBadgeClass()
    {
        $classes = [
            'low' => 'priority-badge low',
            'medium' => 'priority-badge medium',
            'high' => 'priority-badge high',
        ];
        return $classes[$this->priority] ?? 'priority-badge medium';
    }

    // ============================================================
    // HELPER UNTUK URGENT CHECK
    // ============================================================
    
    public function isUrgent()
    {
        // Ticket dianggap urgent jika:
        // 1. Priority = 'high' ATAU
        // 2. Status pending lebih dari 24 jam
        $isHighPriority = $this->priority === 'high';
        $isOldPending = $this->status === 'pending' && 
                       $this->created_at->diffInHours(now()) > 24;
        
        return $isHighPriority || $isOldPending;
    }

    public function getUrgentBadge()
    {
        if ($this->isUrgent()) {
            return '<span class="urgent-badge">🚨 MENDESAK</span>';
        }
        return '';
    }

    public function getDurationInHours()
    {
        return $this->created_at->diffInHours($this->updated_at);
    }

    public function getFormattedCreatedAt()
    {
        return $this->created_at->format('d M Y, H:i');
    }

    public function getFormattedUpdatedAt()
    {
        return $this->updated_at->format('d M Y, H:i');
    }

    // ============================================================
    // SCOPES UNTUK QUERY
    // ============================================================
    
    public function scopeSearch($query, $search)
    {
        if (!$search) return $query;
        
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('technician', 'like', "%{$search}%")
              ->orWhere('admin_response', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%")
              ->orWhereHas('user', function($userQuery) use ($search) {
                  $userQuery->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
              });
        });
    }

    public function scopeFilterStatus($query, $status)
    {
        if ($status && $status !== 'all') {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeFilterPriority($query, $priority)
    {
        if ($priority && $priority !== 'all') {
            return $query->where('priority', $priority);
        }
        return $query;
    }

    public function scopeFilterCategory($query, $category)
    {
        if ($category && $category !== 'all') {
            return $query->where('category', $category);
        }
        return $query;
    }

    public function scopeFilterDate($query, $dateFrom, $dateTo)
    {
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        return $query;
    }

    public function scopeFilterTechnician($query, $technician)
    {
        if ($technician && $technician !== 'all') {
            return $query->where('technician', $technician);
        }
        return $query;
    }

    public function scopeUrgentOnly($query)
    {
        return $query->where('status', 'pending')
            ->where(function($q) {
                $q->where('priority', 'high')
                  ->orWhere('created_at', '<', now()->subDay());
            });
    }

    // ============================================================
    // STATIC METHODS UNTUK STATISTIK DAN CHART
    // ============================================================
    
    public static function getStatistics($dateFrom = null, $dateTo = null)
    {
        $query = self::query();
        
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

        // Hitung urgent tickets
        $urgent = $query->where('status', 'pending')
            ->where(function($q) {
                $q->where('priority', 'high')
                  ->orWhere('created_at', '<', now()->subDay());
            })->count();

        return [
            'total' => $total,
            'pending' => $pending,
            'progress' => $progress,
            'done' => $done,
            'urgent' => $urgent,
        ];
    }

    public static function getChartData($period = '7days')
    {
        $data = [
            'labels' => [],
            'pending' => [],
            'progress' => [],
            'done' => [],
            'urgent' => [],
        ];
        
        switch ($period) {
            case 'today':
                $data = self::getTodayData();
                break;
            case '7days':
                $data = self::getLast7DaysData();
                break;
            case '30days':
                $data = self::getLast30DaysData();
                break;
            case 'month':
                $data = self::getCurrentMonthData();
                break;
            default:
                $data = self::getLast7DaysData();
        }

        return $data;
    }

    private static function getTodayData()
    {
        $today = now()->format('Y-m-d');
        $tickets = self::whereDate('created_at', $today)
            ->selectRaw('HOUR(created_at) as hour, status, priority, COUNT(*) as count')
            ->groupBy('hour', 'status', 'priority')
            ->get();

        $data = [
            'labels' => array_map(function($h) { return sprintf('%02d:00', $h); }, range(0, 23)),
            'pending' => array_fill(0, 24, 0),
            'progress' => array_fill(0, 24, 0),
            'done' => array_fill(0, 24, 0),
            'urgent' => array_fill(0, 24, 0),
        ];

        foreach ($tickets as $ticket) {
            $data[$ticket->status][$ticket->hour] = $ticket->count;
            if ($ticket->priority === 'high') {
                $data['urgent'][$ticket->hour] += $ticket->count;
            }
        }

        return $data;
    }

    private static function getLast7DaysData()
    {
        $data = [
            'labels' => [],
            'pending' => [],
            'progress' => [],
            'done' => [],
            'urgent' => [],
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $day = $date->format('Y-m-d');
            $label = $date->format('D');
            
            $data['labels'][] = $label;
            
            $tickets = self::whereDate('created_at', $day)
                ->selectRaw('status, priority, COUNT(*) as count')
                ->groupBy('status', 'priority')
                ->get();

            $pending = $progress = $done = $urgent = 0;
            foreach ($tickets as $ticket) {
                switch ($ticket->status) {
                    case 'pending':
                        $pending += $ticket->count;
                        if ($ticket->priority === 'high') $urgent += $ticket->count;
                        break;
                    case 'progress':
                        $progress += $ticket->count;
                        break;
                    case 'done':
                        $done += $ticket->count;
                        break;
                }
            }

            $data['pending'][] = $pending;
            $data['progress'][] = $progress;
            $data['done'][] = $done;
            $data['urgent'][] = $urgent;
        }

        return $data;
    }

    private static function getLast30DaysData()
    {
        $data = [
            'labels' => [],
            'pending' => [],
            'progress' => [],
            'done' => [],
            'urgent' => [],
        ];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $day = $date->format('Y-m-d');
            $label = $date->format('d M');
            
            $data['labels'][] = $label;
            
            $tickets = self::whereDate('created_at', $day)
                ->selectRaw('status, priority, COUNT(*) as count')
                ->groupBy('status', 'priority')
                ->get();

            $pending = $progress = $done = $urgent = 0;
            foreach ($tickets as $ticket) {
                switch ($ticket->status) {
                    case 'pending':
                        $pending += $ticket->count;
                        if ($ticket->priority === 'high') $urgent += $ticket->count;
                        break;
                    case 'progress':
                        $progress += $ticket->count;
                        break;
                    case 'done':
                        $done += $ticket->count;
                        break;
                }
            }

            $data['pending'][] = $pending;
            $data['progress'][] = $progress;
            $data['done'][] = $done;
            $data['urgent'][] = $urgent;
        }

        return $data;
    }

    private static function getCurrentMonthData()
    {
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();
        $days = $start->diffInDays($end) + 1;

        $data = [
            'labels' => [],
            'pending' => [],
            'progress' => [],
            'done' => [],
            'urgent' => [],
        ];

        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i);
            $day = $date->format('Y-m-d');
            $label = $date->format('d');
            
            $data['labels'][] = $label;
            
            $tickets = self::whereDate('created_at', $day)
                ->selectRaw('status, priority, COUNT(*) as count')
                ->groupBy('status', 'priority')
                ->get();

            $pending = $progress = $done = $urgent = 0;
            foreach ($tickets as $ticket) {
                switch ($ticket->status) {
                    case 'pending':
                        $pending += $ticket->count;
                        if ($ticket->priority === 'high') $urgent += $ticket->count;
                        break;
                    case 'progress':
                        $progress += $ticket->count;
                        break;
                    case 'done':
                        $done += $ticket->count;
                        break;
                }
            }

            $data['pending'][] = $pending;
            $data['progress'][] = $progress;
            $data['done'][] = $done;
            $data['urgent'][] = $urgent;
        }

        return $data;
    }

    // ============================================================
    // METHOD UNTUK ADMIN DASHBOARD
    // ============================================================
    
    public static function getAdminStatistics()
    {
        $total = self::count();
        $pending = self::where('status', 'pending')->count();
        $progress = self::where('status', 'progress')->count();
        $done = self::where('status', 'done')->count();
        
        // Urgent: priority high atau pending > 24 jam
        $urgent = self::where('status', 'pending')
            ->where(function($q) {
                $q->where('priority', 'high')
                  ->orWhere('created_at', '<', now()->subDay());
            })->count();

        // Tickets hari ini
        $today = self::whereDate('created_at', today())->count();

        // Average resolution time
        $avgResolution = self::where('status', 'done')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours')
            ->first()->avg_hours ?? 0;

        return [
            'total' => $total,
            'pending' => $pending,
            'progress' => $progress,
            'done' => $done,
            'urgent' => $urgent,
            'today' => $today,
            'avg_resolution' => round($avgResolution, 1),
        ];
    }

    // ============================================================
    // METHOD UNTUK USER DASHBOARD
    // ============================================================
    
    public static function getUserStatistics($userId)
    {
        $query = self::where('user_id', $userId);
        
        $total = $query->count();
        $pending = $query->where('status', 'pending')->count();
        $progress = $query->where('status', 'progress')->count();
        $done = $query->where('status', 'done')->count();
        
        // Urgent untuk user ini
        $urgent = $query->where('status', 'pending')
            ->where(function($q) {
                $q->where('priority', 'high')
                  ->orWhere('created_at', '<', now()->subDay());
            })->count();

        // Average resolution time untuk user ini
        $avgResolution = $query->where('status', 'done')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours')
            ->first()->avg_hours ?? 0;

        // Completion rate
        $completionRate = $total > 0 ? round(($done / $total) * 100, 1) : 0;

        return [
            'total' => $total,
            'pending' => $pending,
            'progress' => $progress,
            'done' => $done,
            'urgent' => $urgent,
            'avg_resolution' => round($avgResolution, 1),
            'completion_rate' => $completionRate,
        ];
    }
}