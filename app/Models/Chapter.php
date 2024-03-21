<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chapter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['order', 'lesson_id', 'title', 'intro', 'image', 'created_by', 'updated_by'];

    public function lesson()
    {
        return $this->hasOne(Lesson::class);
    }

    public function chapter_items()
    {
        return $this->hasMany(ChapterItem::class);
    }
}
