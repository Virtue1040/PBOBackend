<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory;

    protected $fillable = ['id_order', 'id_user', 'id_showtime', 'seatNumber', 'price', 'status'];
    protected $primaryKey = 'id';

    public function getShowtime() {
        return $this->belongsTo(Showtime::class, 'id_showtime', 'id');
    }
}
