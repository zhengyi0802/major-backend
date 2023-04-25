<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoCatagory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parent_id',
        'name',
        'description',
        'status',
    ];

    public function parent() {
         return $this->belongsTo(VideoCatagory::class, 'parent_id');
    }

    public function contents() {
        return $this->hasMany(Video::class, 'catagory_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

}
