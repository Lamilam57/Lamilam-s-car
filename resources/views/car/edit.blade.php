<x-app-layout>
    <main>
        <div class="container-small">
            <h1 class="car-details-page-title">Edit Car</h1>

            <form action="{{ route('car.update', $car) }}" method="POST" enctype="multipart/form-data"
                class="card add-new-car-form">
                @csrf
                @method('PUT')

                {{-- {{ dd($state_id) }} --}}
                <div class="form-content">
                    <div class="form-details">

                        {{-- MAKER / MODEL / YEAR --}}
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Maker</label>
                                    <select name="maker_id" required>
                                        <option value="">Select Maker</option>
                                        @foreach ($makers as $maker)
                                            <option value="{{ $maker->id }}" @selected(old('maker_id', $car->maker_id) == $maker->id)>
                                                {{ $maker->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('maker_id')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label>Model</label>
                                    <select name="model_id" id="modelSelect" required>
                                        <option value="">Select Model</option>
                                        @foreach ($models as $model)
                                            <option value="{{ $model->id }}" @selected(old('model_id', $car->model_id) == $model->id)>
                                                {{ $model->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('model_id')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label>Year</label>
                                    <select name="year" required>
                                        @for ($y = now()->year; $y >= 1990; $y--)
                                            <option value="{{ $y }}" @selected(old('year', $car->year) == $y)>
                                                {{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- CAR TYPE --}}
                        <div class="form-group">
                            <label>Car Type</label>
                            <div class="row">
                                @foreach ($carTypes as $type)
                                    <div class="col">
                                        <label class="inline-radio">
                                            <input type="radio" name="car_type_id" value="{{ $type->id }}"
                                                @checked(old('car_type_id', $car->car_type_id) == $type->id)>
                                            {{ $type->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- PRICE / VIN / MILEAGE --}}
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="number" name="price" value="{{ old('price', $car->price) }}"
                                        required>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label>VIN Code</label>
                                    <input name="vin" maxlength="17" value="{{ old('vin', $car->vin) }}" required>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label>Mileage</label>
                                    <input type="number" name="mileage" value="{{ old('mileage', $car->mileage) }}">
                                </div>
                            </div>
                        </div>

                        {{-- FUEL TYPE --}}
                        <div class="form-group">
                            <label>Fuel Type</label>
                            <div class="row">
                                @foreach ($fuelTypes as $fuel)
                                    <div class="col">
                                        <label class="inline-radio">
                                            <input type="radio" name="fuel_type_id" value="{{ $fuel->id }}"
                                                @checked(old('fuel_type_id', $car->fuel_type_id) == $fuel->id)>
                                            {{ $fuel->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- STATE / CITY --}}
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>State</label>
                                    <select name="state_id" id="stateSelect">
                                        <option value="">Select State</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}" @selected(old('state_id', $state_id) == $state->id)>
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col">
                                <div class="form-group">
                                    <label>City</label>
                                    <select name="city_id" id="citySelect">
                                        <option value="">Select City</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}" @selected(old('city_id', $car->city_id) == $city->id)>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- ADDRESS / PHONE --}}
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input name="address" value="{{ old('address', $car->address) }}">
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input name="phone" value="{{ old('phone', $car->phone) }}">
                                </div>
                            </div>
                        </div>

                        {{-- FEATURES --}}
                        <div class="form-group" id="car_features">
                            <div class="row">
                                <div class="col">
                                    @foreach ([
        'air_conditioning' => 'Air Conditioning',
        'power_windows' => 'Power Windows',
        'power_door_locks' => 'Power Door Locks',
        'abs' => 'ABS',
        'cruise_control' => 'Cruise Control',
        'bluetooth_connectivity' => 'Bluetooth Connectivity',
    ] as $name => $label)
                                        <label class="checkbox">
                                            <input type="checkbox" name="{{ $name }}" value="1"
                                                @checked(old($name, optional($car->features)->$name))>
                                            {{ $label }}
                                        </label>
                                    @endforeach
                                </div>

                                <div class="col">
                                    @foreach ([
        'remote_start' => 'Remote Start',
        'gps_navigation' => 'GPS Navigation System',
        'heater_seats' => 'Heated Seats',
        'climate_control' => 'Climate Control',
        'rear_parking_sensor' => 'Rear Parking Sensors',
        'leather_seats' => 'Leather Seats',
    ] as $name => $label)
                                        <label class="checkbox">
                                            <input type="checkbox" name="{{ $name }}" value="1"
                                                @checked(old($name, optional($car->features)->$name))>
                                            {{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- DESCRIPTION --}}
                        <div class="form-group">
                            <label>Detailed Description</label>
                            <textarea name="description" rows="8">{{ old('description', $car->description) }}</textarea>
                        </div>

                        {{-- PUBLISHED --}}
                        <div class="form-group">
                            <label class="checkbox">
                                <input type="checkbox" name="published" value="1" @checked(old('published', $car->published_at))>
                                Published
                            </label>
                        </div>
                    </div>

                    {{-- IMAGES --}}
                    <div class="form-images">
                        <div class="form-image-upload">


                            <p class="my-large">
                                Manage your images
                                <a href="{{ route('car.images.index', $car) }}">From here</a>
                            </p>
                        </div>

                        <div id="imagePreviews" class="car-form-images">
                            @foreach ($car->images as $image)
                                <div
                                    style="
                                    width: 100%;
                                    aspect-ratio: 4 / 3;
                                    overflow: hidden;
                                    border-radius: 8px;
                                    border: 1px solid #e5e7eb;
                                    background: #f9fafb;">
                                    <img src="{{ str_starts_with($image->image_path, 'https') ? $image->image_path : asset('storage/' . $image->image_path) }}"
                                        alt="Car image" class="preview-img"
                                        style="
                                        width: 100%;
                                        height: 100%;
                                        object-fit: cover;
                                        display: block;
                                    ">
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="p-medium">
                    <div class="flex justify-end gap-1">
                        <button type="reset" class="btn btn-default">Reset</button>
                        <button class="btn btn-primary">Update</button>
                    </div>
                </div>

            </form>
        </div>
    </main>
</x-app-layout>
