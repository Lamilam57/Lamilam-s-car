<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarFeatures;
use App\Models\CarImage;
use App\Models\CarType;
use App\Models\City;
use App\Models\FuelType;
use App\Models\Maker;
use App\Models\Model;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cars = auth()->user()
        ->cars()
        ->with(['primaryImage', 'maker', 'model'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return view('car.index', ['cars' => $cars]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('car.create', [
            'makers' => Maker::orderBy('name')->get(),
            'carTypes' => CarType::all(),
            'fuelTypes' => FuelType::all(),
            'states' => State::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'maker_id' => 'required|exists:makers,id',
            'model_id' => 'required|exists:models,id',
            'year' => 'required|integer|min:1920|max:'.now()->year,
            'car_type_id' => 'required|exists:car_types,id',
            'price' => 'required|numeric',
            'phone' => 'required|string|max:11',
            'vin' => 'required|string|max:17',
            'mileage' => 'nullable|numeric',
            'city_id' => 'required|exists:cities,id',
            'fuel_type_id' => 'required|exists:fuel_types,id',
            'description' => 'nullable|string',
            'images' => 'required|array|min:1|max:20',
            'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $createdCar = null;
        $createdFeatures = null;
        $storedImages = [];
        // DB::beginTransaction();

            // 2. Create Car
            // dd('before create');
            $createdCar = Car::create([
                'maker_id' => $request->maker_id,
                'model_id' => $request->model_id,
                'year' => $request->year,
                'price' => $request->price,
                'vin' => $request->vin,
                'mileage' => $request->mileage,
                'car_type_id' => $request->car_type_id,
                'fuel_type_id' => $request->fuel_type_id,
                'user_id' => Auth::id(),
                'city_id' => $request->city_id,
                'address' => $request->address,
                'phone' => $request->phone,
                'description' => $request->description,
                'published_at' => $request->boolean('published') ? now() : null,
            ]);
// dd('after create');

            // 3. Create Features
            $featureColumns = [
                'air_conditioning',
                'power_windows',
                'power_door_locks',
                'abs',
                'cruise_control',
                'bluetooth_connectivity',
                'remote_start',
                'gps_navigation',
                'heater_seats',
                'climate_control',
                'rear_parking_sensor',
                'leather_seats',
            ];

            $featuresData = ['car_id' => $createdCar->id];
            foreach ($featureColumns as $column) {
                $featuresData[$column] = $request->boolean($column);
            }

            $createdFeatures = CarFeatures::create($featuresData);
// dd('features created');
            // 4. Store Images
            foreach ($request->file('images') as $index => $image) {
                if ($index >= 20) {
                    break;
                }

                $path = $image->store('cars', 'public');

                if (! $path) {
                    throw new \Exception('Failed to store image: '.$image->getClientOriginalName());
                }

                $storedImages[] = $path;

                CarImage::create([
                    'car_id' => $createdCar->id,
                    'image_path' => $path,
                    'position' => $index + 1,
                ]);
            }
// dd('images inserted');
            
            return redirect()
            ->route('car.create')
            ->with('success', 'Car listing created successfully.');
            
        
        
    }

//     public function store(Request $request)
// {
//     // dd('hit store');

//     $request->validate([
//         'maker_id' => 'required',
//     ]);

//     // dd('after validation');

//     $car = Car::create([
//         'maker_id' => $request->maker_id,
//         'model_id' => $request->model_id,
//         'year' => $request->year,
//         'price' => $request->price,
//         'vin' => $request->vin,
//         'address' => $request->address,
//         'phone' => $request->phone,
//         'mileage' => $request->mileage,
//         'car_type_id' => $request->car_type_id,
//         'fuel_type_id' => $request->fuel_type_id,
//         'user_id' => auth()->id(),
//         'city_id' => $request->city_id,
//     ]);

//     dd('after created');
// }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Car $car)
    {
        return view('car.show', ['car' => $car]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Car $car)
    {
    // Load images, features, and city with its state
        $car->load(['features', 'images', 'city.state']); // city -> state

    // Get state_id from the car's city
        $state_id = $car->city ? $car->city->state->id : null; // safe check

        // Get models for the car's maker
        $models = $car->maker_id ? Model::where('maker_id', $car->maker_id)->get() : collect();

        // Get all cities for dropdown (you can filter by state in JS if needed)
        $cities = City::all();

        return view('car.edit', [
            'car' => $car,
            'makers' => Maker::all(),
            'models' => $models,
            'carTypes' => CarType::all(),
            'fuelTypes' => FuelType::all(),
            'states' => State::all(),
            'cities' => $cities,
            'state_id' => $state_id, // pass for pre-selecting state
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Car $car)
    {
        // 1. Validate request
        $validated = $request->validate([
            'maker_id' => 'required|exists:makers,id',
            'model_id' => 'required|exists:models,id',
            'year' => 'required|integer|min:1900|max:'.now()->year,
            'car_type_id' => 'required|exists:car_types,id',
            'price' => 'required|numeric|min:0',
            'vin' => 'required|string|max:17',
            'mileage' => 'nullable|numeric|min:0',
            'fuel_type_id' => 'required|exists:fuel_types,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
        ]);

        // 2. Update CAR table
        $car->update([
            'maker_id' => $validated['maker_id'],
            'model_id' => $validated['model_id'],
            'year' => $validated['year'],
            'car_type_id' => $validated['car_type_id'],
            'price' => $validated['price'],
            'vin' => $validated['vin'],
            'mileage' => $validated['mileage'] ?? null,
            'fuel_type_id' => $validated['fuel_type_id'],
            'city_id' => $validated['city_id'],
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'description' => $validated['description'] ?? null,
            'published_at' => $request->boolean('published')
                ? ($car->published_at ?? now())
                : null,
        ]);

        // 3. Update FEATURES
        $featureColumns = [
            'air_conditioning',
            'power_windows',
            'power_door_locks',
            'abs',
            'cruise_control',
            'bluetooth_connectivity',
            'remote_start',
            'gps_navigation',
            'heater_seats',
            'climate_control',
            'rear_parking_sensor',
            'leather_seats',
        ];

        $featuresData = [];
        foreach ($featureColumns as $column) {
            $featuresData[$column] = $request->boolean($column);
        }

        $car->features()->updateOrCreate(
            ['car_id' => $car->id],
            $featuresData
        );

        // 5. Redirect
        return redirect()
            ->route('car.index')
            ->with('success', 'Car updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Car $car)
    {
        $car->delete(); // soft delete

        return redirect()
            ->route('car.index')
            ->with('success', 'Car deleted. It will be permanently removed after 10 days.');
    }

    public function search(Request $request)
    {
        $query = Car::with(['primaryImage', 'city', 'carType', 'fuelType', 'maker', 'model'])
            ->where('published_at', '<', now());

        if ($request->maker_id) {
            $query->where('maker_id', $request->maker_id);
        }
        if ($request->model_id) {
            $query->where('model_id', $request->model_id);
        }
        if ($request->car_type_id) {
            $query->where('car_type_id', $request->car_type_id);
        }
        if ($request->year_from) {
            $query->where('year', '>=', $request->year_from);
        }
        if ($request->year_to) {
            $query->where('year', '<=', $request->year_to);
        }
        if ($request->price_from) {
            $query->where('price', '>=', $request->price_from);
        }
        if ($request->price_to) {
            $query->where('price', '<=', $request->price_to);
        }
        if ($request->mileage) {
            $query->where('mileage', '<=', $request->mileage);
        }
        if ($request->state_id) {
            $query->where('state_id', $request->state_id);
        }
        if ($request->city_id) {
            $query->where('city_id', $request->city_id);
        }
        if ($request->fuel_type_id) {
            $query->where('fuel_type_id', $request->fuel_type_id);
        }

        // Sorting
        if ($request->sort) {
            if ($request->sort === 'price') {
                $query->orderBy('price', 'asc');
            }
            if ($request->sort === '-price') {
                $query->orderBy('price', 'desc');
            }
        } else {
            $query->orderBy('published_at', 'desc');
        }

        $cars = $query->paginate(15)->withQueryString();

        if ($request->ajax()) {
            return view('car.partials.car-list', compact('cars'))->render();
        }

        return view('car.search', [
            'cars' => $cars,
            'makers' => Maker::all(),
            'models' => Model::all(),
            'carTypes' => CarType::all(),
            'fuelTypes' => FuelType::all(),
            'states' => State::all(),
        ]);
    }

    public function watchList()
    {
        $cars = auth()->user()
            ->favouriteCars() // FavouriteCar models
            ->with(['car.primaryImage', 'car.city', 'car.carType', 'car.fuelType', 'car.maker', 'car.model'])
            ->paginate(15);

        return view('car.watchList', compact('cars'));
    }
}
