<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Showtime extends Model
{
    /** @use HasFactory<\Database\Factories\ShowtimeFactory> */
    use HasFactory;

    protected $fillable = ['id_movie','id_studio','time'];
    protected $primaryKey = 'id';

    public function getTickets()
    {
        return $this->hasMany(Ticket::class, 'id_showtime', 'id')
        ->where(function ($query) {
                $query->where('status', 'paid');
            })
            ->orWhere(function ($query) {
                $query->where('status', 'unpaid')
                      ->where('created_at', '>=', Carbon::now()->subMinutes(5));
            });
    }

    public function getMovie() {
        return $this->belongsTo(Movie::class, 'id_movie', 'id');
    }

    public function getStudio() {
        return $this->belongsTo(Studio::class, 'id_studio', 'id');
    }
}
