<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'catagory_id',
        'title',
        'thumbnail',
        'video_url',
        'url_http',
        'description',
        'status',
    ];

    public function catagory() {
        return $this->belongsTo(VideoCatagory::class, 'catagory_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
