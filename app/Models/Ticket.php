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
        'technician',
        'admin_response',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusColor()
    {
        $colors = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'progress' => 'bg-blue-100 text-blue-800',
            'done' => 'bg-green-100 text-green-800',
        ];
        return $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
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
}