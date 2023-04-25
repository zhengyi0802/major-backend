<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppManager extends Model
{
    use HasFactory;

    protected $fillable = [
         'proj_id',
         'apk_id',
         'market_id',
         'installer_flag',
         'delaytime',
         'status',
         'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function project() {
        return $this->belongsTo(Project::class, 'proj_id');
    }

    public function apk() {
        return $this->belongsTo(ApkManager::class, 'apk_id');
    }
}
