<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Ticket;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Showtime;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ticket = Ticket::all();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data ticket',
            'data' => $ticket
        ]);
    }

    public function getown(Request $request)
    {
        $user = $request->user();
        $ticket = Ticket::where("id_user", $user->id)
        ->where("status", "paid")
        ->whereHas('getShowtime', function ($query) {
            $query->where('time', '>', now());
        })->get();

        foreach ($ticket as $ticketq) {
            $getShowtime = $ticketq->getShowtime;
            $getMovie = Movie::find($getShowtime->id_movie);
            $ticketq->get_movie = $getMovie;
            $ticketq->get_studioName = $getShowtime->getStudio->studioName;
        }
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data ticket',
            'data' => $ticket
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        $request->validate([
            'id_movie' => ['required', 'integer', 'exists:movies,id'],
            'id_showtime' => ['required', 'integer', 'exists:showtimes,id'],
            'seatNumber' => ['required', 'string', Rule::unique('tickets')->where(function ($query) use ($request) {
                return $query->where('id_showtime', $request->id_showtime);
            })],
        ]);

        $getMovie = Movie::find($request->id_movie);
        $getShowtime = Showtime::find($request->id_showtime);

        if ($getShowtime->time < $getMovie->expire) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, tiket tidak valid',
            ]);
        }

        $request->merge([
            'price' => $getMovie->price,
            'id_user' => $request->user()->id
        ]);

        $ticket = Ticket::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan data ticket',
            'data' => $ticket
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket, $id_ticket)
    {
        $request->validate([
            'time' => ['required', 'date'],
            'seatNumber' => ['required', 'string'],
        ]);

        $getTicket = Ticket::find($id_ticket);
        $ticket = getTicket->update($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan data ticket',
            'data' => $getTicket
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket, $id_ticket)
    {
        $ticket = Ticket::find($id_ticket);
        $ticket->delete();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus data ticket',
            'data' => $ticket
        ]);
    }
}
