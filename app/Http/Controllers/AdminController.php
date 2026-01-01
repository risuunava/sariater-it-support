<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Dashboard admin
    public function dashboard()
    {
        // CEK ROLE MANUAL
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $totalTickets = Ticket::count();
        $pendingTickets = Ticket::where('status', 'pending')->count();
        $progressTickets = Ticket::where('status', 'progress')->count();
        $doneTickets = Ticket::where('status', 'done')->count();

        $recentTickets = Ticket::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalTickets',
            'pendingTickets',
            'progressTickets',
            'doneTickets',
            'recentTickets'
        ));
    }

    // Semua tickets
    public function tickets()
    {
        // CEK ROLE MANUAL
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $tickets = Ticket::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.tickets', compact('tickets'));
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
        $technicians = ['John Doe', 'Jane Smith', 'Robert Johnson', 'Lisa Wang'];

        return view('admin.ticket-show', compact('ticket', 'technicians'));
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
}