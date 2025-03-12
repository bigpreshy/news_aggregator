<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_id',
        'title',
        'content',
        'category',
        'author',
        'source_name',
        'published_at',
        'url',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
