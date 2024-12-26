<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudioRequest;
use App\Http\Requests\UpdateStudioRequest;
use App\Models\Studio;

class StudioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $studio = Studio::all();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data studio',
            'data' => $studio
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
    public function store(StoreStudioRequest $request)
    {
        $request->validate([
            'studioName' => ['required', 'string'],
            'capacity' => ['required', 'integer']
        ]);

        $studio = Studio::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan data studio',
            'data' => $studio
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Studio $studio, $id_studio)
    {
        $studio = Studio::find($id_studio);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data studio',
            'data' => $studio
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Studio $studio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudioRequest $request, Studio $studio, $id_studio)
    {
        $request->validate([
            'studioName' => ['required', 'string'],
            'capacity' => ['required', 'integer']
        ]);

        $getStudio = Studio::find($id_studio);
        $studio = $getStudio->update($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengupdate data studio',
            'data' => $studio
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Studio $studio, $id_studio)
    {
        $studio = Studio::find($id_studio);
        $studio->delete();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus data studio',
            'data' => $studio
        ]);
    }
}
