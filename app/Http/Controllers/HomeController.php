<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarType;
use App\Models\City;
use App\Models\FuelType;
use App\Models\Maker;
use App\Models\Model;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $cars = Car::where('published_at', '<', now())
        ->with(['primaryImage', 'city', 'carType', 'fuelType', 'maker', 'model'])
        ->orderBy('published_at', 'desc')
        ->limit(30)
        ->get();

        if(Auth::user()){
            return view('home.index', [
                'role' => Auth::user()->role,
                'cars' => $cars,
                'makers' => Maker::all(),
                'carTypes' => CarType::all(),
                'models' => Model::all(),
                'fuelTypes' => FuelType::all(),
                'states' => State::all(),
            ]);
        }

        return view('home.index', [
            'role' => null,
            'cars' => $cars,
            'makers' => Maker::all(),
            'carTypes' => CarType::all(),
            'models' => Model::all(),
            'fuelTypes' => FuelType::all(),
            'states' => State::all(),
        ]);
    }

    public function show()
{
    return view('home.personalInfo', [
        'user' => Auth::user(),
        'role' => Auth::user()->role,
        'states' => State::all(),
        'cities' => City::all(),
    ]);
}

    
public function update(Request $request)
{
    $user = auth()->user();

    // ✅ Validate
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        'phone' => ['nullable', 'string', 'max:20'],
        'state_id' => ['required', 'exists:states,id'],
        'city_id' => ['required', 'exists:cities,id'],
        'address' => ['nullable', 'string', 'max:255'],
        'image' => [
            'nullable',
            'image',
            'mimes:jpeg,png,jpg,webp',
            'max:2048'
        ],
    ]);

    // ✅ Handle Image Upload (if exists)
    if ($request->hasFile('image')) {

        $file = $request->file('image');

        // Delete old image
        if ($user->image && Storage::disk('public')->exists('profile/' . $user->image)) {
            Storage::disk('public')->delete('profile/' . $user->image);
        }

        // Generate unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // Store file
        $file->storeAs('profile', $filename, 'public');

        // Add to validated data
        $validated['image'] = $filename;
    }

    // ✅ Single update
    $user->update($validated);

    return back()->with('success', 'Profile updated successfully.');
}
}
