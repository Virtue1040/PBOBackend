<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ticket;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = ['id_order', 'id_user', 'order_type', 'order_nominal', 'status'];
    protected $primaryKey = 'id_order';
    protected $keyType = 'string';

    public function getTickets() {
        return $this->hasMany(Ticket::class, "id_order", "id_order");
    }

    public function getMovie() {
        $getTicket = Ticket::where("id_order", $this->id_order)->first();
        if ($getTicket != null) {
            $getMovie = $getTicket->getShowtime->getMovie;
            return $getMovie;   
        }
        return [
            "title" => "null"
        ];
    }

    public function getTime() {
        $getTicket = Ticket::where("id_order", $this->id_order)->first();
        if ($getTicket != null) {
            $getShowtime = $getTicket->getShowtime;
            return $getShowtime->time;   
        }
    }
}
