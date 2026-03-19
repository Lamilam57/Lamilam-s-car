<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    
    public function store(Request $request, Car $car)
    {
        $user = Auth::user()->id;
        // dd($car->user_id);
        
        if ($user === $car->user_id) {
            return back()->withErrors('Can not make review on your own car.');
        }
        $badWords = ['fuck','suck','nigga', 'motherfucker'];

        foreach($badWords as $word){
            if(str_contains($request->review,$word)){
                return back()->withErrors('Inappropriate language.');
            }
        }
        // dd($user, $car->id, $request->review);
        $validated = $request->validate([
            'rating' => ['required','integer','between:1,5'],
            'review' => ['required','string','max:1000']
        ]);
        // dd($validated);


        
        Review::updateOrCreate(
            [
                'car_id' => $car->id,
                'user_id' => $user,
            ],
            $validated
        );
        // dd($validated);

        return back()->with('success','Review submitted.');
    }

}
