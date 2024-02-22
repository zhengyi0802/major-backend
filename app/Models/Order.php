<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $connection = 'sales2';

    protected $fillable = [
        'id',
        'customer_id',
        'product_id',
        'project_id',
        'sales_id',
        'name',
        'phone',
        'address',
        'price',
        'installation_fee',
        'flow',
        'memo',
        'status',
        'order_date',
        'created_by',
    ];

    function product() {
        return $this->belongsTo(ProductModel::class, 'product_id');
    }
}
