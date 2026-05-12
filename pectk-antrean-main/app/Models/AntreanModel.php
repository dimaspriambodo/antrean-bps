<?php

namespace App\Models;

use CodeIgniter\Model;

class AntreanModel extends Model
{
    protected $table            = 'antrean';
    protected $primaryKey       = 'id_antrean';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Field di bawah ini WAJIB ada agar database bisa diisi
    protected $allowedFields    = [
        'nama_jaminan', 
        'kode_antrean', 
        'nomor_antrean', 
        'tanggal_antrean', 
        'satpam', 
        'loket', 
        'status'
    ];

    protected $useTimestamps = false; // Karena tabel Anda menggunakan default current_timestamp
}