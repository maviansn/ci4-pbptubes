<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelDonasi extends Model
{
    protected $table            = 'donasi';
    protected $primaryKey       = 'iddonasi';
    protected $allowedFields    = [
        'iddonasi', 'namadonasi', 'jenisdonasi', 'jumlahkumpul', 'targetdonasi', 'tglakhir'
    ];
}