<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username'   => 'admin',
                'email'      => 'admin@example.com',
                'password'   => password_hash('admin123', PASSWORD_BCRYPT),
                'fullname'   => 'Administrator Utama',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'budi',
                'email'      => 'budi@example.com',
                'password'   => password_hash('budi123', PASSWORD_BCRYPT),
                'fullname'   => 'Budi Setiawan',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'sari',
                'email'      => 'sari@example.com',
                'password'   => password_hash('sari123', PASSWORD_BCRYPT),
                'fullname'   => 'Sari Indah',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert ke tabel users
        $this->db->table('users')->insertBatch($data);
    }
}