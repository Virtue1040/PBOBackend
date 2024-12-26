<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    /** @use HasFactory<\Database\Factories\StudioFactory> */
    use HasFactory;

    protected $fillable = [
        'studioName', 'capacity'
    ];
    protected $primaryKey = 'id';
}
