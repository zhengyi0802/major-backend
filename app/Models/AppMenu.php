<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'proj_id',
        'position',
        'label',
        'name',
        'thumbnail',
        'url',
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
