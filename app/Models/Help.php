<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Help extends Model
{
    use HasFactory;

    protected $table = 'helps';

    protected $fillable = [
       'proj_id',
       'name',
       'url',
       'detail',
       'status',
       'start_datetime',
       'stop_datetime',
    ];

}
