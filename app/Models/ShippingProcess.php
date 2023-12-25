<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingProcess extends Model
{
    use HasFactory;

    protected $connection = 'sales2';

    protected $fillable = [
        'order_id',
        'serialno',
        'shipping_date',
        'completion_time',
        'chargeback_time',
        'installer_id',
        'created_by',
    ];

    function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    function order() {
        return $this->belongsTo(Order::class, 'order_id');
    }

    function installer() {
        return $this->belongsTo(User::class, 'installer_id');
    }
}
