<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homepage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'detail', 'mac_address', 'status', 'start_datetime', 'stop_datetime'
    ];
}
