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
use Cloudinary\Cloudinary;

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

    $cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        'api_key'    => env('CLOUDINARY_API_KEY'),
        'api_secret' => env('CLOUDINARY_API_SECRET'),
    ],
    'url' => ['secure' => true]
]);

// Delete old image from Cloudinary
if ($user->image && str_starts_with($user->image, 'https')) {
    try {
        $parts = explode('/', $user->image);
        $filename = end($parts);
        $publicId = 'profile/' . pathinfo($filename, PATHINFO_FILENAME);

        $cloudinary->uploadApi()->destroy($publicId);
    } catch (\Exception $e) {
        // optional: log error
    }
}

// Upload new image
$uploadResult = $cloudinary->uploadApi()->upload(
    $file->getRealPath(),
    ['folder' => 'profile']
);

$imageUrl = $uploadResult['secure_url'];

// Save URL instead of filename
$validated['image'] = $imageUrl;

    // ✅ Single update
    $user->update($validated);

    return back()->with('success', 'Profile updated successfully.');
}
}
