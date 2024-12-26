<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    /** @use HasFactory<\Database\Factories\MovieFactory> */
    use HasFactory;

    protected $fillable = ['title', 'duration', 'genre', 'sinopsis', 'expire', 'price', 'cover'];
    protected $primaryKey = 'id';
}
