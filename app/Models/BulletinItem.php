<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulletinItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'bulletin_id',
        'mime_type',
        'url',
        'url_http',
        'status',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parent() {
        return $this->belongsTo(Bulletin::class, 'bulletin_id');
    }
}
