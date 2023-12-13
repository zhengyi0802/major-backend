<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warranty extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'product_id',
        'register_time',
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
