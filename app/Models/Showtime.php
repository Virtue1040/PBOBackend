<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    /** @use HasFactory<\Database\Factories\ShowtimeFactory> */
    use HasFactory;

    protected $fillable = ['id_movie','id_studio','time'];
    protected $primaryKey = 'id';

    public function getTickets()
    {
        return $this->hasMany(Ticket::class, 'id_showtime', 'id');
    }

    public function getMovie() {
        return $this->belongsTo(Movie::class, 'id_movie', 'id');
    }

    public function getStudio() {
        return $this->belongsTo(Studio::class, 'id_studio', 'id');
    }
}
