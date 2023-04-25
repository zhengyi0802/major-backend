<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QACatagory extends Model
{
    use HasFactory;

    protected $fillable = [
        'position',
        'name',
        'descriptions',
        'status',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lists() {
        return $this->hasMany(QAList::class, 'catagory_id');
    }
}
