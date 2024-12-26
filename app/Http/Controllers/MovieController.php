<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Movie;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movie = Movie::all();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data movie',
            'data' => $movie
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
    public function store(StoreMovieRequest $request)
    {

        $request->validate([
            'title' => ['required', 'string'],
            'genre' => ['required', 'string', 'max:100'],
            'sinopsis' => ['required', 'string', 'max:255'],
            'duration' => ['required', 'date_format:H:i:s'],
            'expire' => ['required', 'date'],
            'cover' => ['string'],
            'price' => ['required', 'numeric'],
        ]);

        $base64Cover = $request->input('cover');

        $movie = Movie::create([
            'title' => $request->title,
            'genre' => $request->genre,
            'sinopsis' => $request->sinopsis,
            'duration' => $request->duration,
            'expire' => $request->expire,
            'price' => $request->price
        ]);      

        if ($base64Cover !== "null") {
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Cover, $matches)) {
                $extension = $matches[1]; 
                $base64Cover = substr($base64Cover, strpos($base64Cover, ',') + 1);
            } else {
                $extension = 'png';
            }
            $decodedCover = base64_decode($base64Cover);
            if ($decodedCover) {
                $fileName = uniqid('cover_') . '.' . $extension;

                $filePath = public_path('uploads/' . $fileName);
                file_put_contents($filePath, $decodedCover);
                
                $movie->cover = 'uploads/' . $fileName;
                $movie->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan data movie',
            'data' => $movie
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie, $id_movie)
    {
        $movie = Movie::find($id_movie);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data movie',
            'data' => $movie
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMovieRequest $request, Movie $movie, $id_movie)
    {
        $request->validate([
            'title' => ['required', 'string'],
            'genre' => ['required', 'string', 'max:100'],
            'sinopsis' => ['required', 'string', 'max:255'],
            'duration' => ['required', 'date_format:H:i:s'],
            'expire' => ['required', 'date'],
            'cover' => ['string'],
            'price' => ['required', 'numeric'],
        ]);

        $base64Cover = $request->input('cover');

        $getMovie = Movie::find($id_movie);
        $movie = $getMovie->update([
            'title' => $request->title,
            'genre' => $request->genre,
            'sinopsis' => $request->sinopsis,
            'duration' => $request->duration,
            'expire' => $request->expire,
            'price' => $request->price
        ]);

        if ($base64Cover !== "null") {
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Cover, $matches)) {
                $extension = $matches[1]; 
                $base64Cover = substr($base64Cover, strpos($base64Cover, ',') + 1);
            } else {
                $extension = 'png';
            }
            $decodedCover = base64_decode($base64Cover);
            if ($decodedCover) {
                $fileName = uniqid('cover_') . '.' . $extension;
    
                $filePath = public_path('uploads/' . $fileName);
                file_put_contents($filePath, $decodedCover);
                
                $getMovie->cover = 'uploads/' . $fileName;
                $getMovie->save();
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengupdate data movie',
            'data' => $getMovie
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie, $id_movie)
    {
        $movie = Movie::find($id_movie);
        $movie->delete();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus data movie',
            'data' => $movie
        ]);
    }
}
