<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;

Route::middleware(['auth:sanctum', 'RoleAdmin'])->group(function () {
    Route::post('/studio', [StudioController::class, 'store'])->name('studio.post');
    Route::put('/studio/{id_studio}', [StudioController::class, 'update'])->name('studio.update');
    Route::delete('/studio/{id_studio}', [StudioController::class, 'destroy'])->name('studio.delete');

    Route::post('/movie', [MovieController::class, 'store'])->name('movie.post');
    Route::put('/movie/{id_movie}', [MovieController::class, 'update'])->name('movie.update');
    Route::delete('/movie/{id_movie}', [MovieController::class, 'destroy'])->name('movie.delete');

    Route::post('/showtime', [ShowtimeController::class, 'store'])->name('showtime.post');
    Route::put('/showtime/{id_showtime}', [ShowtimeController::class, 'update'])->name('showtime.update');
    Route::delete('/showtime/{id_showtime}', [ShowtimeController::class, 'destroy'])->name('showtime.delete');
    
    Route::get('/ticket', [TicketController::class, 'index'])->name('ticket');
    Route::post('/ticket', [TicketController::class, 'store'])->name('ticket.post');
    Route::put('/ticket/{id_ticket}', [TicketController::class, 'update'])->name('ticket.update');
    Route::delete('/ticket/{id_ticket}', [TicketController::class, 'destroy'])->name('ticket.delete');
});

Route::middleware(['auth:sanctum'])->group(function() {
    Route::get('/ticket/get', [TicketController::class, 'getown'])->name('ticket.getown');
    Route::post('/order', [OrderController::class, 'store'])->name('order.post');
    Route::get('/transaction/get', [TransactionController::class, 'getown'])->name('transaction.getown');
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'message' => 'Berhasil login',
            'data' => [
                'user' => $request->user(), 
            ],
        ]);
    });
});

Route::post('/midtrans/callback', [OrderController::class, 'callback']);
Route::get('/midtrans/status/{id}', [OrderController::class, 'getStatus']);
Route::get('/studio', [StudioController::class, 'index'])->name('studio');
Route::get('/studio/{id_studio}', [StudioController::class, 'show'])->name('studio.get');
Route::get('/movie', [MovieController::class, 'index'])->name('movie');
Route::get('/showtime/getshowtime/{id_movie}', [ShowtimeController::class, 'getfrommovie'])->name('showtime.getfrommovie');
Route::get('/movie/{id_movie}', [MovieController::class, 'show'])->name('movie.get');
Route::get('/showtime', [ShowtimeController::class, 'index'])->name('showtime');
Route::get('/showtime/getoccupy/{id_showtime}', [ShowtimeController::class, 'getoccupy'])->name('showtime.getoccupy');
Route::get('/showtime/{id_showtime}', [ShowtimeController::class, 'show'])->name('showtime.get');
Route::get('/ticket/{id_ticket}', [TicketController::class, 'show'])->name('ticket.get');


Route::post('/login', function(Request $request){
    $rules = [
        'login' => ['required', 'email'],
        'password' => ['required', 'string']
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => "Validation Error",
            'error' => $validator->errors()
        ]);
    }

    $loginInput = $request->input('login');
    $getUser = User::where('email', $loginInput)->first();
    if ($getUser) {
        if (Hash::check($request->input('password'), $getUser->password)) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil login',
                'data' => [
                    'token' => $getUser->createToken('sanctum')->plainTextToken,
                    'user' => $getUser, 
                ],
            ]);
        }
    }
    return response()->json([
        'success' => false,
        'message' => 'Email atau password salah',
    ]);   
});

Route::post('/register', function(Request $request){
    $rules = [
        'name' => ['required', 'string', 'unique:'.User::class],
        'email' => ['required', 'string'],
        'password' => ['required', 'string', 'confirmed', 'min:8', Rules\Password::defaults()]
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => "Validation Error",
            'error' => $validator->errors()
        ]);
    }

    if (User::where('email', $request->input('email'))->first()) {
        return response()->json([
            'success' => false,
            'message' => 'Email sudah terdaftar',
        ]);
    }
   
    $user = User::create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'password' => Hash::make($request->input('password')),
        'role' => 'user',
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Berhasil login',
        'data' => [
            'token' => $user->createToken('sanctum')->plainTextToken,
            'user' => $user, 
        ],
    ]);
});
