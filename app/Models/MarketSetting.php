<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketSetting extends Model
{
    use HasFactory;

    protected $fillable = [
         'proj_id',
         'apk_id',
         'flag',
         'status',
    ];

    public function project() {
        return $this->belongsTo(Project::class, 'proj_id');
    }

    public function apk() {
        return $this->belongsTo(ApkManager::class, 'apk_id');
    }
}
