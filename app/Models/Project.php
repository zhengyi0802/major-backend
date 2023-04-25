<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_id',
        'rid',
        'name',
        'descriptions',
        'status',
        'start_time',
        'stop_time',
    ];

}
