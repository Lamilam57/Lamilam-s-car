<x-app-layout :role="$role">

    @php
        $carFormData = session('car_form_data', []);
    @endphp
    <main>
        <div class="container-small">

            <h1 class="car-details-page-title">Add New Car</h1>

            <x-error-message />
            <x-success-message />

            <form action="{{ route('car.store') }}" method="POST" enctype="multipart/form-data"
                class="card add-new-car-form">

                @csrf

                <div class="form-content">

                    <div class="form-details">

                        {{-- =========================
MAKER / MODEL / YEAR
========================= --}}
                        <div class="row">

                            <div class="col">
                                <div class="form-group">
                                    <label>Maker</label>

                                    <select name="maker_id" required>

                                        <option value="">Select Maker</option>

                                        @foreach ($makers as $maker)
                                            <option value="{{ $maker->id }}" @selected(old('maker_id', $carFormData['maker_id'] ?? '') == $maker->id)>

                                                {{ $maker->name }}

                                            </option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>


                            <div class="col">

                                <div class="form-group">

                                    <label>Model</label>

                                    <select name="model_id" id="modelSelect" required>

                                        <option value="">Select Model</option>

                                    </select>

                                </div>

                            </div>


                            <div class="col">

                                <div class="form-group">

                                    <label>Year</label>

                                    <select name="year">

                                        @for ($y = now()->year; $y >= 1990; $y--)
                                            <option value="{{ $y }}" @selected(old('year', $carFormData['year'] ?? '') == $y)>

                                                {{ $y }}

                                            </option>
                                        @endfor

                                    </select>

                                </div>

                            </div>

                        </div>


                        {{-- =========================
CAR TYPE
========================= --}}

                        <div class="form-group">

                            <label>Car Type</label>

                            <div class="row">

                                @foreach ($carTypes as $type)
                                    <div class="col">

                                        <label class="feature-checkbox">

                                            <input type="radio" name="car_type_id" value="{{ $type->id }}"
                                                @checked(old('car_type_id', $carFormData['car_type_id'] ?? '') == $type->id)>

                                            <span>{{ $type->name }}</span>

                                        </label>

                                    </div>
                                @endforeach

                            </div>

                        </div>


                        {{-- =========================
PRICE / VIN / MILEAGE
========================= --}}

                        <div class="row">

                            <div class="col">

                                <div class="form-group">

                                    <label>Price</label>

                                    <input type="number" name="price"
                                        value="{{ old('price', $carFormData['price'] ?? '') }}" placeholder="Price"
                                        required>

                                </div>

                            </div>


                            <div class="col">

                                <div class="form-group">

                                    <label>VIN Code</label>

                                    <input name="vin" maxlength="17"
                                        value="{{ old('vin', $carFormData['vin'] ?? '') }}" placeholder="VIN Code"
                                        required>

                                </div>

                            </div>


                            <div class="col">

                                <div class="form-group">

                                    <label>Mileage</label>

                                    <input type="number" name="mileage"
                                        value="{{ old('mileage', $carFormData['mileage'] ?? '') }}"
                                        placeholder="Mileage">

                                </div>

                            </div>

                        </div>


                        {{-- =========================
FUEL TYPE
========================= --}}

                        <div class="form-group">

                            <label>Fuel Type</label>

                            <div class="row">

                                @foreach ($fuelTypes as $fuel)
                                    <div class="col">

                                        <label class="feature-checkbox">

                                            <input type="radio" name="fuel_type_id" value="{{ $fuel->id }}"
                                                @checked(old('fuel_type_id', $carFormData['fuel_type_id'] ?? '') == $fuel->id)>

                                            <span>{{ $fuel->name }}</span>

                                        </label>

                                    </div>
                                @endforeach

                            </div>

                        </div>


                        {{-- =========================
STATE / CITY
========================= --}}

                        <div class="row">

                            <div class="col">

                                <div class="form-group">

                                    <label>State</label>

                                    <select name="state_id" id="stateSelect">

                                        <option value="">Select State</option>

                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}" @selected(old('state_id', $carFormData['state_id'] ?? '') == $state->id)>

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

                                    </select>

                                </div>

                            </div>

                        </div>


                        {{-- =========================
ADDRESS / PHONE
========================= --}}

                        <div class="row">

                            <div class="col">

                                <div class="form-group">

                                    <label>Address</label>

                                    <input name="address" value="{{ old('address', $carFormData['address'] ?? '') }}"
                                        placeholder="Address">

                                </div>

                            </div>


                            <div class="col">

                                <div class="form-group">

                                    <label>Phone</label>

                                    <input name="phone" value="{{ old('phone', $carFormData['phone'] ?? '') }}"
                                        placeholder="Phone">

                                </div>

                            </div>

                        </div>


                        {{-- =========================
FEATURES
========================= --}}

                        @php

                            $features = [
                                'air_conditioning' => 'Air Conditioning',
                                'power_windows' => 'Power Windows',
                                'power_door_locks' => 'Power Door Locks',
                                'abs' => 'ABS',
                                'cruise_control' => 'Cruise Control',
                                'bluetooth_connectivity' => 'Bluetooth Connectivity',
                                'remote_start' => 'Remote Start',
                                'gps_navigation' => 'GPS Navigation',
                                'heater_seats' => 'Heated Seats',
                                'climate_control' => 'Climate Control',
                                'rear_parking_sensor' => 'Rear Parking Sensors',
                                'leather_seats' => 'Leather Seats',
                            ];

                        @endphp


                        <div class="form-group">

                            <label>Car Features</label>

                            <div class="features-grid">

                                @foreach ($features as $name => $label)
                                    <label class="feature-checkbox">

                                        <input type="checkbox" name="{{ $name }}" value="1"
                                            @checked(old($name, $carFormData[$name] ?? false))>

                                        <span>{{ $label }}</span>

                                    </label>
                                @endforeach

                            </div>

                        </div>


                        {{-- =========================
DESCRIPTION
========================= --}}

                        <div class="form-group">

                            <label>Description</label>

                            <textarea name="description" rows="8">

{{ old('description', $carFormData['description'] ?? '') }}

</textarea>

                        </div>


                        {{-- =========================
PUBLISHED
========================= --}}

                        <div class="form-group">

                            <label class="feature-checkbox">

                                <input type="checkbox" name="published" value="1" @checked(old('published', $carFormData['published'] ?? false))>

                                <span>Published</span>

                            </label>

                        </div>

                    </div>


                    {{-- =========================
IMAGE UPLOAD
========================= --}}

                    <div class="form-images">

                        <div class="form-image-upload">

                            <div class="upload-placeholder">

                                Upload Images

                            </div>

                            <input id="carFormImageUpload" type="file" name="images[]" multiple accept="image/*"
                                required>

                        </div>

                        <div id="imagePreviews" class="car-form-images"></div>

                    </div>


                </div>


                <div class="p-medium" style="width:100%">

                    <div class="flex justify-end gap-1">

                        <button type="reset" class="btn btn-default">
                            Reset
                        </button>

                        <button class="btn btn-primary">
                            Submit
                        </button>

                    </div>

                </div>

            </form>

        </div>
    </main>


</x-app-layout>
