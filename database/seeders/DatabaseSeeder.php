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
            'email_verified_at' => now(),
        ]);

        // Buat beberapa user karyawan
        $karyawan1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@sariater.com',
            'password' => Hash::make('karyawan123'),
            'role' => 'karyawan',
            'email_verified_at' => now(),
        ]);

        $karyawan2 = User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti@sariater.com',
            'password' => Hash::make('karyawan123'),
            'role' => 'karyawan',
            'email_verified_at' => now(),
        ]);

        $karyawan3 = User::create([
            'name' => 'Agus Wijaya',
            'email' => 'agus@sariater.com',
            'password' => Hash::make('karyawan123'),
            'role' => 'karyawan',
            'email_verified_at' => now(),
        ]);

        $karyawan4 = User::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi@sariater.com',
            'password' => Hash::make('karyawan123'),
            'role' => 'karyawan',
            'email_verified_at' => now(),
        ]);

        // Buat contoh tickets untuk testing
        $ticketData = [
            [
                'user_id' => $karyawan1->id,
                'title' => 'Printer tidak bisa mencetak',
                'description' => 'Printer di ruang Marketing (Epson L3210) tidak bisa mencetak. Sudah dicek kabel dan koneksi, masih tidak berfungsi. Error lampu merah berkedip.',
                'status' => 'pending',
                'technician' => null,
                'admin_response' => null,
            ],
            [
                'user_id' => $karyawan2->id,
                'title' => 'Laptop lambat dan sering hang',
                'description' => 'Laptop Dell Latitude 3420 sangat lambat saat membuka aplikasi dan sering hang. Sudah restart beberapa kali. Digunakan untuk kerja harian.',
                'status' => 'progress',
                'technician' => 'John Doe',
                'admin_response' => 'Sudah dicek, kemungkinan perlu upgrade RAM dari 4GB ke 8GB. Kami akan jadwalkan untuk upgrade.',
            ],
            [
                'user_id' => $karyawan3->id,
                'title' => 'Email tidak bisa terkirim',
                'description' => 'Email dengan attachment file PDF (ukuran 5MB) tidak bisa terkirim ke klien. Error message: "Connection timed out".',
                'status' => 'done',
                'technician' => 'Jane Smith',
                'admin_response' => 'Masalah sudah diperbaiki. Ada setting SMTP yang perlu disesuaikan. Sekarang email sudah bisa dikirim dengan attachment sampai 10MB.',
            ],
            [
                'user_id' => $karyawan1->id,
                'title' => 'WiFi di ruang meeting lemah',
                'description' => 'Sinyal WiFi di ruang meeting lantai 3 sangat lemah. Sulit untuk video conference. Sudah dicoba di beberapa spot.',
                'status' => 'pending',
                'technician' => null,
                'admin_response' => null,
            ],
            [
                'user_id' => $karyawan4->id,
                'title' => 'Software accounting error',
                'description' => 'Software Accurate error ketika ingin cetak laporan neraca. Muncul pesan "Database connection failed". Software versi 5.',
                'status' => 'progress',
                'technician' => 'Robert Johnson',
                'admin_response' => 'Kami sedang cek koneksi database. Untuk sementara, backup data harian dulu.',
            ],
            [
                'user_id' => $karyawan2->id,
                'title' => 'Monitor flicker',
                'description' => 'Monitor di meja saya (LG 24 inch) sering flicker terutama saat digunakan lebih dari 2 jam. Sudah ganti kabel HDMI, masih sama.',
                'status' => 'done',
                'technician' => 'Lisa Wang',
                'admin_response' => 'Monitor sudah diganti dengan yang baru. Monitor lama akan dikirim untuk service.',
            ],
            [
                'user_id' => $karyawan3->id,
                'title' => 'Akses shared folder terbatas',
                'description' => 'Tidak bisa akses shared folder "Project_2024" di server. Permission denied. Padahal kemarin masih bisa.',
                'status' => 'pending',
                'technician' => null,
                'admin_response' => null,
            ],
            [
                'user_id' => $karyawan4->id,
                'title' => 'Keyboard tidak responsif',
                'description' => 'Keyboard wireless Logitech tiba-tiba tidak responsif. Sudah ganti baterai, masih tidak berfungsi.',
                'status' => 'done',
                'technician' => 'John Doe',
                'admin_response' => 'Keyboard sudah diganti dengan yang baru. Receiver mungkin rusak.',
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
        $this->command->info('====================================');
        $this->command->info('Total data yang dibuat:');
        $this->command->info('- Users: 5 (1 admin, 4 karyawan)');
        $this->command->info('- Tickets: 8 (contoh data)');
        $this->command->info('====================================');
    }
}