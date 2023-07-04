<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaCatagory extends Model
{
    use HasFactory;

    protected $fillable = [
        'proj_id',
        'parent_id',
        'type',
        'name',
        'keywords',
        'description',
        'thumbnail',
        'password',
        'status',
        'user_id',
    ];

    public function user() {
         return $this->belongsTo(User::class, 'user_id');
    }

    public function project() {
        return $this->belongsTo(Project::class, 'proj_id');
    }

    public function parent() {
        return $this->belongsTo(MediaCatagory::class, 'parent_id');
    }
}
