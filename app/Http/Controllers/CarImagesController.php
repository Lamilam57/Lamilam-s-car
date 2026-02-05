<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        return view('car.car_images', compact('car'));
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
            $images = CarImage::where('car_id', $car->id)
                ->whereIn('id', $request->delete_images)
                ->get();

            foreach ($images as $image) {
                if (
                    $image->image_path &&
                    ! str_starts_with($image->image_path, 'https') &&
                    Storage::disk('public')->exists($image->image_path)
                ) {
                    Storage::disk('public')->delete($image->image_path);
                }

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

        if ($existingCount >= 20) {
            return back()->withErrors([
                'error' => 'You can upload a maximum of 20 images per car.',
            ]);
        }

        $position = $existingCount + 1;

        foreach ($request->file('images') as $image) {
            if ($position > 21) {
                break;
            }

            $path = $image->store('cars', 'public');

            if (! $path) {
                continue;
            }

            CarImage::create([
                'car_id' => $car->id,
                'image_path' => $path,
                'position' => $position,
            ]);

            $position++;
        }

        return back()->with('success', 'Images added successfully.');
    }
}
