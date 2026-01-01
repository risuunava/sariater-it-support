<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    // Dashboard karyawan
    public function dashboard()
    {
        // CEK ROLE MANUAL
        $user = Auth::user();
        if ($user->role !== 'karyawan') {
            abort(403, 'Akses ditolak.');
        }

        $tickets = Ticket::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('karyawan.dashboard', compact('tickets'));
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

    // Simpan ticket baru
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
        ]);

        Ticket::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return redirect()->route('karyawan.dashboard')
            ->with('success', 'Laporan berhasil dibuat.');
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

        return view('karyawan.ticket-show', compact('ticket'));
    }
}