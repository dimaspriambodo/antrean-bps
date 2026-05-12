<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class User extends Seeder
{
    public function run()
    {
        $data = [
            [
                'fullname'     => 'Administrator',
                'username'     => 'admin',
                'password'     => password_hash('admin123', PASSWORD_DEFAULT),
                'profilephoto' => NULL,
                'role'         => 'Admisi',
                'kode_antrian' => NULL,
                'active'       => '1',
                'registered'   => date('Y-m-d H:i:s'),
            ],
            [
                'fullname'     => 'Petugas Ambil Antrean',
                'username'     => 'ambil_antrean',
                'password'     => password_hash('antrean123', PASSWORD_DEFAULT),
                'profilephoto' => NULL,
                'role'         => 'Satpam',
                'kode_antrian' => NULL,
                'active'       => '1',
                'registered'   => date('Y-m-d H:i:s'),
            ]
        ];

        // GANTI insert MENJADI insertBatch
        $this->db->table('user')->insertBatch($data);
    }
}