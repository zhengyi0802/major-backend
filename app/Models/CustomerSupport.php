<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSupport extends Model
{
    use HasFactory;

    protected $fillable = [
        'proj_id',
        'qrcode_type',
        'qrcode_content',
        'message',
        'rcapp',
        'rcapp_label',
        'rcapp_url',
        'is_default',
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
