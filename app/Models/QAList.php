<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QAList extends Model
{
    use HasFactory;

    protected $fillable = [
        'catagory_id',
        'question',
        'type',
        'answer',
        'answer_http',
        'status',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function catagory() {
        return $this->belongsTo(QACatagory::class, 'catagory_id');
    }
}
