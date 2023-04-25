<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marquee extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'proj_id',
        'product_id',
        'prev_id',
        'name',
        'content',
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

    public function previous() {
        return $this->belongsTo(Marquee::class, 'prev_id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function next() {
        return $this->hasMany(Marquee::class, 'prev_id');
    }

}
