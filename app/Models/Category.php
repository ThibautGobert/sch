<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['order', 'active', 'in_menu', 'name'];

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'category_id', 'id');
    }
}
