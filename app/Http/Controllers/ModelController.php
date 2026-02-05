<?php

namespace App\Http\Controllers;

use App\Models\Model;

class ModelController extends Controller
{
    public function getByMaker($makerId)
    {
        $models = Model::where('maker_id', $makerId)->get();

        return response()->json($models);
    }
}
