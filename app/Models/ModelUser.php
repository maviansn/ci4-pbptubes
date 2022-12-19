<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelUser extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'userid';
    protected $allowedFields    = [
        'userid', 'username', 'password', 'email', 'tgllahir', 'nohp'
    ];
}