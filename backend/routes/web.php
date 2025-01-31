<?php

use Illuminate\Support\Facades\Route;
use App\Models\Clients;
use App\Models\Cars;
use App\Models\Services;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/clients', function () {
    return Clients::all();
});

Route::get('/api/clients/{id}/get-cars', function ($id) {
    $client = Clients::findOrFail($id);
    $cars = Cars::where('client_id', $id)->get();

    $cars->each(function ($car) {
        $newestService = Services::where('car_id', $car->car_id)
            ->orderBy('log_number', 'desc')
            ->first();
        $car->newest_event = $newestService ? $newestService->event : null;
        $car->newest_event_date = $newestService ? $newestService->event_time : null;
    });

    return response()->json([
        'client' => $client,
        'cars' => $cars
    ]);
});
