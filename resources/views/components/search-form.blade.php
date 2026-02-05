@props(['models', 'makers', 'carTypes', 'fuelTypes', 'states'])
@php
    $currentYear = date('Y'); // e.g., 2026
    $startYear = 1950; // earliest year for cars
    $fromYears = range($currentYear - 1, $startYear); 
    $toYears = range($currentYear, $startYear); 

@endphp

<!-- Find a car form -->
<section class="find-a-car">
    <div class="container">
        <form action="{{ route('car.search') }}" method="GET" class="find-a-car-form card flex p-medium"
            id="searchCarForm">
            <div class="find-a-car-inputs">
                <div>
                    <select id="makerSelect" name="maker_id">
                        <option value="">Select Maker</option>
                        @foreach ($makers as $maker)
                            <option value="{{ $maker->id }}" @selected(old('maker_id') == $maker->id)>
                                {{ $maker->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('maker_id')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <select name="model_id" id="modelSelect">
                        <option value="" style="display: block">Model</option>
                    </select>
                    @error('model_id')
                        <p class="error-message">{{ $message }}</p>
                    @enderror

                </div>
                <div>
                    <select name="state_id" id="stateSelect">
                        <option value="">Select State</option>
                        @foreach ($states as $state)
                            <option value="{{ $state->id }}" @selected(old('state_id') == $state->id)>
                                {{ $state->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="city_id" id="citySelect">
                        <option value="">City</option>
                    </select>
                    @error('state_id')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <select name="car_type_id">
                        <option value="">Select Type</option>
                        @foreach ($carTypes as $type)
                            <option value="{{ $type->id }}">
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="year_from">
                        <option value="">From</option>
                        @foreach ($fromYears as $year)
                            <option value="{{ $year }}" @selected(old('year_from') == $year)>{{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="year_to">
                        <option value="">To</option>
                        @foreach ($toYears as $year)
                            <option value="{{ $year }}" @selected(old('year_to') == $year)>{{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <input type="number" placeholder="Price From" name="price_from" />
                </div>
                <div>
                    <input type="number" placeholder="Price To" name="price_to" />
                </div>
                <div>
                    <select name="fuel_type_id">
                        <option value="">Select Fuel Type</option>
                        @foreach ($fuelTypes as $fuel)
                            <option value="{{ $fuel->id }}">
                                {{ $fuel->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <button type="button" class="btn btn-find-a-car-reset" id="resetBtn">
                    Reset
                </button>
                <button class="btn btn-primary btn-find-a-car-submit">
                    Search
                </button>
            </div>
        </form>
    </div>
</section>
<!--/ Find a car form -->
