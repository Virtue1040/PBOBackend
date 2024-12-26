<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShowtimeRequest;
use App\Http\Requests\UpdateShowtimeRequest;
use App\Models\Showtime;

class ShowtimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $showtime = Showtime::all();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data showtime',
            'data' => $showtime
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
    public function store(StoreShowtimeRequest $request)
    {
        $request->validate([
            'id_movie' => ['required', 'integer', 'exists:movies,id'],
            'id_studio' => ['required', 'integer', 'exists:studios,id'],
            'time' => ['required', 'date'],
        ]);

        $showtime = Showtime::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan data showtime',
            'data' => $showtime
        ]);
    }

    public function getfrommovie($id_movie)
    {
        $showtime = Showtime::where("id_movie", $id_movie)->with("getMovie")->with("getStudio")->get();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan data showtime',
            'data' => $showtime
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Showtime $showtime, $id_showtime)
    {
        $showtime = Showtime::find($id_showtime);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data showtime',
            'data' => $showtime
        ]);
    }

    public function getoccupy(Showtime $showtime, $id_showtime)
    {
        $showtime = Showtime::find($id_showtime);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data showtime',
            'data' => $showtime->getTickets
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Showtime $showtime)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShowtimeRequest $request, Showtime $showtime, $id_showtime)
    {
        $request->validate([
            'id_studio' => ['required', 'integer', 'exists:studios,id'],
            'time' => ['required', 'date'],
        ]);

        $getShowtime = Showtime::find($id_showtime);
        $showtime = $getShowtime->update($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengupdate data showtime',
            'data' => $showtime
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Showtime $showtime, $id_showtime)
    {
        $showtime = Showtime::find($id_showtime);
        $showtime->delete();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus data showtime',
            'data' => $showtime
        ]);
    }
}
