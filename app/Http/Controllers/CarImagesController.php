<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Cloudinary\Cloudinary;

// use App\Models\CarImage;

class CarImagesController extends Controller
{
    public function index(Car $car)
    {
        $car->load([
            'images' => function ($query) {
                $query->orderBy('position', 'asc');
            },
            'maker',
            'model',
        ]);

        return view('car.car_images', compact('car'), ['role' => Auth::user()->role]);
    }

    /**
     * Update image positions and delete selected images
     */
    public function update(Request $request, Car $car)
    {
        $car->load('images');

        /*
        |------------------------------------------------------------
        | DELETE SELECTED IMAGES
        |------------------------------------------------------------
        */

        if ($request->filled('delete_images')) {
        
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key'    => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
                'url' => ['secure' => true]
            ]);
        
            $images = CarImage::where('car_id', $car->id)
                ->whereIn('id', $request->delete_images)
                ->get();
        
            foreach ($images as $image) {
        
                // ✅ If it's a Cloudinary image
                if ($image->image_path && str_starts_with($image->image_path, 'https')) {
        
                    try {
                        // Extract public_id from URL
                        $parts = explode('/', $image->image_path);
                        $filename = end($parts);
                        $publicId = 'cars/' . pathinfo($filename, PATHINFO_FILENAME);
        
                        // Delete from Cloudinary
                        $cloudinary->uploadApi()->destroy($publicId);
        
                    } catch (\Exception $e) {
                        // Optional: log error
                    }
                }
        
                // ✅ If it's local image (fallback)
                elseif (
                    $image->image_path &&
                    Storage::disk('public')->exists($image->image_path)
                ) {
                    Storage::disk('public')->delete($image->image_path);
                }
        
                // Delete DB record
                $image->delete();
            }
        }

        /*
        |------------------------------------------------------------
        | UPDATE IMAGE POSITIONS
        |------------------------------------------------------------
        */
        if ($request->filled('positions')) {
            foreach ($request->positions as $imageId => $position) {
                CarImage::where('id', $imageId)
                    ->where('car_id', $car->id)
                    ->update([
                        'position' => (int) $position,
                    ]);
            }
        }

        return back()->with('success', 'Images updated successfully.');
    }

    /**
     * Store new images
     */
    public function store(Request $request, Car $car)
    {
        $request->validate([
            'images' => ['required', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $existingCount = $car->images()->count();

        if ($existingCount >= 10) {
            return back()->withErrors([
                'error' => 'You can upload a maximum of 20 images per car.',
            ]);
        }
         $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key'    => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
                'url' => ['secure' => true]
            ]);
        

        $position = $existingCount + 1;

        foreach ($request->file('images') as $index => $image) {

            if ($index >= 10) {
                break;
            }
        
            $uploadResult = $cloudinary->uploadApi()->upload(
                $image->getRealPath(),
                ['folder' => 'cars']
            );
        
            $imageUrl = $uploadResult['secure_url'];
        
            CarImage::create([
                'car_id' => $createdCar->id,
                'image_path' => $imageUrl,
                'position' => $index + 1,
            ]);
        }

        return back()->with('success', 'Images added successfully.');
    }
}
