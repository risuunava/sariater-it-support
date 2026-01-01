<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;

// Halaman utama redirect ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// AUTH ROUTES
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ROUTES UNTUK KARYAWAN
Route::middleware(['web'])->group(function () {
    // Dashboard karyawan
    Route::get('/karyawan/dashboard', [TicketController::class, 'dashboard'])
        ->name('karyawan.dashboard');
    
    // Tickets management
    Route::get('/karyawan/tickets/create', [TicketController::class, 'create'])
        ->name('karyawan.tickets.create');
    Route::post('/karyawan/tickets', [TicketController::class, 'store'])
        ->name('karyawan.tickets.store');
    Route::get('/karyawan/tickets/{id}', [TicketController::class, 'show'])
        ->name('karyawan.tickets.show');
    Route::get('/karyawan/tickets/{id}/edit', [TicketController::class, 'edit'])
        ->name('karyawan.tickets.edit');
    Route::put('/karyawan/tickets/{id}', [TicketController::class, 'update'])
        ->name('karyawan.tickets.update');
    Route::get('/karyawan/tickets/export', [TicketController::class, 'exportTickets'])
        ->name('karyawan.tickets.export');
    
    // User profile
    Route::get('/karyawan/profile', [TicketController::class, 'profile'])
        ->name('karyawan.profile');
    Route::post('/karyawan/profile', [TicketController::class, 'updateProfile'])
        ->name('karyawan.profile.update');
    Route::post('/karyawan/profile/password', [TicketController::class, 'changePassword'])
        ->name('karyawan.profile.password');
    
    // Analytics
    Route::get('/karyawan/analytics', [TicketController::class, 'analytics'])
        ->name('karyawan.analytics');
    
    // Help & Support
    Route::get('/karyawan/help', [TicketController::class, 'help'])
        ->name('karyawan.help');
});

// ROUTES UNTUK ADMIN
Route::middleware(['web'])->group(function () {
    // Dashboard admin
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');
    
    // Tickets management
    Route::get('/admin/tickets', [AdminController::class, 'tickets'])
        ->name('admin.tickets');
    Route::get('/admin/tickets/{id}', [AdminController::class, 'showTicket'])
        ->name('admin.ticket.show');
    Route::put('/admin/tickets/{id}/status', [AdminController::class, 'updateStatus'])
        ->name('admin.ticket.update.status');
    Route::get('/admin/tickets/export', [AdminController::class, 'exportTickets'])
        ->name('admin.tickets.export');
    
    // Analytics
    Route::get('/admin/analytics', [AdminController::class, 'analytics'])
        ->name('admin.analytics');
    
    // Reports
    Route::get('/admin/reports', [AdminController::class, 'reports'])
        ->name('admin.reports');
    
    // Users management
    Route::get('/admin/users', [AdminController::class, 'users'])
        ->name('admin.users');
    
    // Settings
    Route::get('/admin/settings', [AdminController::class, 'settings'])
        ->name('admin.settings');
});