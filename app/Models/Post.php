<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'thumbnail_url',
        'content',
        'category',
        'status',
        'views',
    ];

    public function author()
    {
        return $this->belongsTo(User::class , 'author_id');
    }
}
