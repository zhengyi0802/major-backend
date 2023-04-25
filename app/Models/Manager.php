<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'job_title',
        'proj_id',
        'description',
        'status',
    ];
/*
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
*/
    public function project() {
        return $this->belongsTo(Project::class, 'proj_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
