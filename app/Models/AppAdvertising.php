<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppAdvertising extends Model
{
    use HasFactory;

    protected $fillable = [
        'proj_id',
        'interval',
        'name',
        'thumbnail',
        'link_url',
        'link_image',
        'status',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function project() {
        return $this->belongsTo(Project::class, 'proj_id');
    }
}
