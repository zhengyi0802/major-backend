<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ELearningCatagory extends Model
{
    use HasFactory;

    protected $fillable = [
        'proj_id',
        'parent_id',
        'type',
        'name',
        'description',
        'thumbnail',
        'status',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function project() {
        return $this->belongsTo(Project::class, 'proj_id');
    }

    public function parent() {
        return $this->belongsTo(ELearningCatagory::class, 'parent_id');
    }

    public function childs() {
        return $this->hasMany(ELearningCatagory::class, 'parent_id');
    }

}
