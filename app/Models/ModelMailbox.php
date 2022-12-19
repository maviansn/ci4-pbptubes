<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelMailbox extends Model
{
    protected $table            = 'mailbox';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'id','judul','pesan'
    ];
}
