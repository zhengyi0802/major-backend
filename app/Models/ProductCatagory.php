<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCatagory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'descriptions',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function types() {
        return $this->hasMany(ProductType::class, 'catagory_id');
    }
}
