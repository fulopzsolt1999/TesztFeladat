<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getData()
    {
        // Example data
        $data = [
            'message' => 'Hello from Laravel API',
            'status' => 'success'
        ];

        return response()->json($data);
    }
}
