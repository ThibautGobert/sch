<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChapterItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['content', 'created_by', 'updated_by'];
}
