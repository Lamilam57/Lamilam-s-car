<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function getByState($stateId)
    {
        // Return only id and name
        $cities = City::where('state_id', $stateId)->get(['id','name']);
        return response()->json($cities);
    }
}
