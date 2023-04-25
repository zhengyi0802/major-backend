<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
       'type_id',
       'serialno',
       'ether_mac',
       'wifi_mac',
       'proj_id',
       'expire_date',
       'status_id',
    ];

    public function macformat($mac_address) {
        $mac_array = str_split($ac_address, 2);
        $macaddress = implode(':', $mac_array);
        return $macaddress;
    }

    public function type() {
        return $this->belongsTo(ProductType::class, 'type_id');
    }

    public function project() {
        return $this->belongsTo(Project::class, 'proj_id');
    }

    public function status() {
        return $this->belongsTo(ProductStatus::class, 'status_id');
    }

    public function records() {
        return $this->hasMany(ProductRecord::class, 'product_id');
    }
}
