<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorit extends Model
{
    protected $table = 'favorit';
    protected $fillable = [
        'id_user',
        'id_kos',
    ];
}
