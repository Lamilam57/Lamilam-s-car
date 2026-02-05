<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\FavouriteCar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    public function toggle(Car $car)
    {
        try {
            // Prevent favouriting your own car
            if ((string)$car->user_id === (string)Auth::id()) {
                return response()->json([
                    'error' => 'You cannot favourite your own car.',
                ], 403);
            }

            // Check if this user has already favourited the car
            $favourite = FavouriteCar::where('car_id', $car->id)
                ->where('user_id', Auth::id())
                ->first();

            if ($favourite) {
                $favourite->delete();

                return response()->json([
                    'favourited' => false,
                    'message' => 'Car removed from favourites.'
                ]);
            }

            // Create a new favourite
            FavouriteCar::create([
                'car_id' => $car->id,
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'favourited' => true,
                'message' => 'Car added to favourites.'
            ]);

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Favourite toggle failed', [
                'user_id' => Auth::id(),
                'car_id' => $car->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Something went wrong while toggling favourite. Please try again.'
            ], 500);
        }
    }
}
