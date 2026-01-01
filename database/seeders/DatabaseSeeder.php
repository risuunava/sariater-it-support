<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat user admin
        $admin = User::create([
            'name' => 'Admin IT Support',
            'email' => 'admin@sariater.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '0811-1111-1111',
            'department' => 'IT Support',
            'email_verified_at' => now(),
        ]);

        // Buat beberapa user karyawan DENGAN DATA LENGKAP
        $karyawan1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@sariater.com',
            'password' => Hash::make('karyawan123'),
            'role' => 'karyawan',
            'phone' => '0812-3456-7890',
            'department' => 'Marketing',
            'email_verified_at' => now(),
        ]);

        $karyawan2 = User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti@sariater.com',
            'password' => Hash::make('karyawan123'),
            'role' => 'karyawan',
            'phone' => '0813-4567-8901',
            'department' => 'HR',
            'email_verified_at' => now(),
        ]);

        $karyawan3 = User::create([
            'name' => 'Agus Wijaya',
            'email' => 'agus@sariater.com',
            'password' => Hash::make('karyawan123'),
            'role' => 'karyawan',
            'phone' => '0814-5678-9012',
            'department' => 'IT',
            'email_verified_at' => now(),
        ]);

        $karyawan4 = User::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi@sariater.com',
            'password' => Hash::make('karyawan123'),
            'role' => 'karyawan',
            'phone' => '0815-6789-0123',
            'department' => 'Finance',
            'email_verified_at' => now(),
        ]);

        // Buat contoh tickets untuk testing
        $ticketData = [
            [
                'user_id' => $karyawan1->id,
                'title' => 'Printer tidak bisa mencetak',
                'description' => 'Printer di ruang Marketing (Epson L3210) tidak bisa mencetak. Sudah dicek kabel dan koneksi, masih tidak berfungsi. Error lampu merah berkedip.',
                'status' => 'pending',
                'priority' => 'medium',
                'technician' => null,
                'admin_response' => null,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'user_id' => $karyawan2->id,
                'title' => 'Laptop lambat dan sering hang',
                'description' => 'Laptop Dell Latitude 3420 sangat lambat saat membuka aplikasi dan sering hang. Sudah restart beberapa kali. Digunakan untuk kerja harian.',
                'status' => 'progress',
                'priority' => 'high',
                'technician' => 'John Doe',
                'admin_response' => 'Sudah dicek, kemungkinan perlu upgrade RAM dari 4GB ke 8GB. Kami akan jadwalkan untuk upgrade.',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(2),
            ],
            [
                'user_id' => $karyawan3->id,
                'title' => 'Email tidak bisa terkirim',
                'description' => 'Email dengan attachment file PDF (ukuran 5MB) tidak bisa terkirim ke klien. Error message: "Connection timed out".',
                'status' => 'done',
                'priority' => 'medium',
                'technician' => 'Jane Smith',
                'admin_response' => 'Masalah sudah diperbaiki. Ada setting SMTP yang perlu disesuaikan. Sekarang email sudah bisa dikirim dengan attachment sampai 10MB.',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(8),
            ],
            [
                'user_id' => $karyawan1->id,
                'title' => 'WiFi di ruang meeting lemah',
                'description' => 'Sinyal WiFi di ruang meeting lantai 3 sangat lemah. Sulit untuk video conference. Sudah dicoba di beberapa spot.',
                'status' => 'pending',
                'priority' => 'high',
                'technician' => null,
                'admin_response' => null,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'user_id' => $karyawan4->id,
                'title' => 'Software accounting error',
                'description' => 'Software Accurate error ketika ingin cetak laporan neraca. Muncul pesan "Database connection failed". Software versi 5.',
                'status' => 'progress',
                'priority' => 'high',
                'technician' => 'Robert Johnson',
                'admin_response' => 'Kami sedang cek koneksi database. Untuk sementara, backup data harian dulu.',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'user_id' => $karyawan2->id,
                'title' => 'Monitor flicker',
                'description' => 'Monitor di meja saya (LG 24 inch) sering flicker terutama saat digunakan lebih dari 2 jam. Sudah ganti kabel HDMI, masih sama.',
                'status' => 'done',
                'priority' => 'low',
                'technician' => 'Lisa Wang',
                'admin_response' => 'Monitor sudah diganti dengan yang baru. Monitor lama akan dikirim untuk service.',
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(12),
            ],
            [
                'user_id' => $karyawan3->id,
                'title' => 'Akses shared folder terbatas',
                'description' => 'Tidak bisa akses shared folder "Project_2024" di server. Permission denied. Padahal kemarin masih bisa.',
                'status' => 'pending',
                'priority' => 'medium',
                'technician' => null,
                'admin_response' => null,
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'user_id' => $karyawan4->id,
                'title' => 'Keyboard tidak responsif',
                'description' => 'Keyboard wireless Logitech tiba-tiba tidak responsif. Sudah ganti baterai, masih tidak berfungsi.',
                'status' => 'done',
                'priority' => 'low',
                'technician' => 'John Doe',
                'admin_response' => 'Keyboard sudah diganti dengan yang baru. Receiver mungkin rusak.',
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(18),
            ],
        ];

        foreach ($ticketData as $data) {
            Ticket::create($data);
        }

        // Informasi
        $this->command->info('====================================');
        $this->command->info('SEEDER BERHASIL DIIMPLEMENTASI!');
        $this->command->info('====================================');
        $this->command->info('');
        $this->command->info('AKUN LOGIN UNTUK TESTING:');
        $this->command->info('------------------------------------');
        $this->command->info('ADMIN:');
        $this->command->info('Email: admin@sariater.com');
        $this->command->info('Password: admin123');
        $this->command->info('');
        $this->command->info('KARYAWAN:');
        $this->command->info('Email: budi@sariater.com');
        $this->command->info('Password: karyawan123');
        $this->command->info('');
        $this->command->info('Email: siti@sariater.com');
        $this->command->info('Password: karyawan123');
        $this->command->info('');
        $this->command->info('Email: agus@sariater.com');
        $this->command->info('Password: karyawan123');
        $this->command->info('');
        $this->command->info('Email: dewi@sariater.com');
        $this->command->info('Password: karyawan123');
        $this->command->info('');
        $this->command->info('====================================');
        $this->command->info('Total data yang dibuat:');
        $this->command->info('- Users: 5 (1 admin, 4 karyawan)');
        $this->command->info('- Tickets: 8 (contoh data)');
        $this->command->info('====================================');
    }
}