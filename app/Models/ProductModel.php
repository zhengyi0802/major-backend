<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;

    protected $connection = 'sales2';

    protected $fillable = [
        'name',
        'model',
        'catagory_id',
        'vendor_id',
        'price',
        'briefs',
        'specifications',
        'descriptions',
        'accessories',
        'is_accessories',
        'extra',
        'status',
        'created_by',
    ];

    function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    function catagory() {
        return $this->belongsTo(Catagory::class, 'catagory_id');
    }

    function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    function accessory() {
        return $this->belongsTo(ProductModel::class, 'accessories');
    }

}
