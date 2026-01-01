<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'department',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isKaryawan()
    {
        return $this->role === 'karyawan';
    }

    // Get user statistics
    public function getTicketStatistics()
    {
        return [
            'total' => $this->tickets()->count(),
            'pending' => $this->tickets()->where('status', 'pending')->count(),
            'progress' => $this->tickets()->where('status', 'progress')->count(),
            'done' => $this->tickets()->where('status', 'done')->count(),
        ];
    }

    // Get user performance metrics
    public function getPerformanceMetrics()
    {
        $tickets = $this->tickets()->get();
        $doneTickets = $tickets->where('status', 'done');
        
        $avgResponseTime = $doneTickets->avg(function($ticket) {
            return $ticket->created_at->diffInHours($ticket->updated_at);
        });

        $completionRate = $tickets->count() > 0 
            ? ($doneTickets->count() / $tickets->count()) * 100 
            : 0;

        $satisfactionScore = min(5, 3 + ($completionRate / 100) * 2);

        return [
            'avg_response_time' => round($avgResponseTime, 1),
            'completion_rate' => round($completionRate, 1),
            'satisfaction_score' => round($satisfactionScore, 1),
        ];
    }
}