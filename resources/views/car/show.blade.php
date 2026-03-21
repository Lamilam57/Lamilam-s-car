<x-app-layout :role="$role">
    <x-success-message />
    <x-error-message />
    <main>
        <div class="container">
            <h1 class="car-details-page-title">{{ $car->maker->name }} {{ $car->model->name }} - {{ $car->year }}</h1>
            <div class="car-details-region">{{ $car->city->name }} - {{ $car->published_at }}</div>

            <div class="car-details-content">
                <div class="car-images-and-description">
                    <div class="car-images-carousel">
                        <div class="car-image-wrapper">
                            <x-car-image :car="$car" class="car-active-image" id="activeImage" />
                        </div>
                        <div class="car-image-thumbnails">
                            @foreach ($car->images as $image)
                                <img src="{{ str_starts_with($image->image_path, 'https') ? $image->image_path : asset('storage/' . $image->image_path) }}"
                                    alt="" />
                            @endforeach
                        </div>
                        <button class="carousel-button prev-button" id="prevButton">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" style="width: 64px">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                        <button class="carousel-button next-button" id="nextButton">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" style="width: 64px">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>

                    <div class="card car-detailed-description">
                        <h2 class="car-details-title">Detailed Description</h2>
                        <p>
                            {!! $car->description??"No description for this car" !!}
                        </p>
                    </div>

                    <div class="card car-detailed-description">
                        <h2 class="car-details-title">Car Specifications</h2>

                        <ul class="car-specifications">
                            <x-car-specification :value="$car->features->air_conditioning">
                                Air Conditioning
                            </x-car-specification>
                            <x-car-specification :value="$car->features->power_windows">
                                Power Windows
                            </x-car-specification>
                            <x-car-specification :value="$car->features->power_door_locks">
                                Power Door Locks
                            </x-car-specification>
                            <x-car-specification :value="$car->features->abs">
                                ABS
                            </x-car-specification>
                            <x-car-specification :value="$car->features->cruise_control">
                                Cruise Control
                            </x-car-specification>
                            <x-car-specification :value="$car->features->bluetooth_connectivity">
                                Bluetooth Connectivity
                            </x-car-specification>
                            <x-car-specification :value="$car->features->remote_start">
                                Remote Start
                            </x-car-specification>
                            <x-car-specification :value="$car->features->gps_navigation">
                                GPS Navigation System
                            </x-car-specification>
                            <x-car-specification :value="$car->features->heated_seats">
                                Heated Seats
                            </x-car-specification>
                            <x-car-specification :value="$car->features->climate_control">
                                Climate Control
                            </x-car-specification>
                            <x-car-specification :value="$car->features->rear_parking_sensors">
                                Rear Parking Sensors
                            </x-car-specification>
                            <x-car-specification :value="$car->features->leather_seats">
                                Leather Seats
                            </x-car-specification>
                        </ul>
                    </div>
                    <div class="card car-detailed-description">
                        <h2 class="car-details-title">Car Review</h2>
                        <p class="page-subtitle">See what users are saying about this car</p>
                        {{-- Reviews List --}}
                        <div class="card reviews-list">

                            <h4 class="mb-4">User Reviews</h4>

                            @forelse ($reviews as $review)

                                <div class="review-card">

                                    <div class="review-header">

                                        <div class="review-user">

                                            <x-user-image :user="$review->user" class="my-cars-img-thumbnail" />

                                            <div>
                                                <strong>{{ $review->user->name }}</strong>
                                                <p class="review-date">
                                                    {{ $review->created_at->diffForHumans() }}
                                                </p>
                                            </div>

                                        </div>

                                        <div class="review-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    ⭐
                                                @else
                                                    ☆
                                                @endif
                                            @endfor
                                        </div>

                                    </div>

                                    <div class="review-body">
                                        {{ $review->review }}
                                    </div>

                                </div>

                            @empty

                                <div class="no-review">
                                    No reviews yet.
                                </div>

                            @endforelse

                        </div>

                        {{-- Add Review --}}
                        @if ($role === 'user' && $user->id !== $car->user_id)
                            <div class="card review-form-card mb-5">
                                <div class="card-body">

                                    <h4 class="card-title mb-3">Leave a Review</h4>

                                    <form method="POST" action="{{ route('reviews.store', $car) }}">
                                        @csrf

                                        <input type="hidden" name="car_id" value="{{ $car->id }}">

                                        {{-- Rating --}}
                                        <div class="mb-3">
                                            <label class="form-label">Rating</label>

                                            <select name="rating" class="form-control" required>
                                                <option value="">Select Rating</option>
                                                <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                                                <option value="4">⭐⭐⭐⭐ Good</option>
                                                <option value="3">⭐⭐⭐ Average</option>
                                                <option value="2">⭐⭐ Poor</option>
                                                <option value="1">⭐ Very Poor</option>
                                            </select>

                                            @error('rating')
                                                <p class="error-message">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Comment --}}
                                        <div class="mb-3">
                                            <label class="form-label">Comment</label>

                                            <textarea name="review" rows="4" class="form-control" placeholder="Write your experience with this car..."
                                                required></textarea>

                                            @error('comment')
                                                <p class="error-message">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <button class="btn btn-primary">
                                            Submit Review
                                        </button>

                                    </form>

                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="car-details card">
                    <div class="flex items-center justify-between">
                        <p class="car-details-price">{{ $car->getPrice() }}</p>
                        <x-favourite-heart :car="$car" />
                    </div>

                    <hr />
                    <table class="car-details-table">
                        <tbody>
                            <tr>
                                <th>Maker</th>
                                <td>{{ $car->maker->name }}</td>
                            </tr>
                            <tr>
                                <th>Model</th>
                                <td>{{ $car->model->name }}</td>
                            </tr>
                            <tr>
                                <th>Year</th>
                                <td>{{ $car->year }}</td>
                            </tr>
                            <tr>
                                <th>Vin</th>
                                <td>{{ $car->vin }}</td>
                            </tr>
                            <tr>
                                <th>Mileage</th>
                                <td>{{ $car->mileage }}</td>
                            </tr>
                            @if ($role === 'admin' || $role === 'user')
                                <tr>
                                    <th>Number of Views</th>
                                    <td>{{ $totalClicks ?? 0 }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Car Type</th>
                                <td>{{ $car->carType->name }}</td>
                            </tr>
                            <tr>
                                <th>Fuel Type</th>
                                <td>{{ $car->fuelType->name }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>
                                    <span class="short-text">
                                        {{ Str::words($car->address, 2, '...') }}
                                    </span>

                                    @if (str_word_count($car->address) > 2)
                                        <span class="full-text hidden">
                                            {{ $car->address }}
                                        </span>

                                        <a onclick="toggleAddress(this)" style="display: block">
                                            More
                                        </a>
                                    @endif
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <hr />

                    <div class="flex gap-1 my-medium">
                        <img src="{{ asset('img/avatar.png') }}" alt="avatar picturs"
                            class="car-details-owner-image" />
                        <div>
                            <h3 class="car-details-owner">{{ $car->owner->name }}</h3>
                            <div class="text-muted">{{ $car->owner->cars()->count() }} cars</div>
                        </div>
                    </div>
                    <a href="tel:{{ $car->phone }}" class="car-details-phone" data-phone="{{ $car->phone }}"
                        data-masked="{{ Str::mask($car->phone, '*', -5) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" style="width: 16px">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                        </svg>

                        <span class="phone-text">{{ Str::mask($car->phone, '*', -5) }}</span>
                        <span class="toggle-text car-details-phone-view">view full number</span>
                    </a>
                </div>

            </div>
        </div>
    </main>
    <script>
        function toggleAddress(button) {
            const td = button.closest('td');
            const shortText = td.querySelector('.short-text');
            const fullText = td.querySelector('.full-text');

            if (fullText.classList.contains('hidden')) {
                fullText.classList.remove('hidden');
                shortText.style.display = 'none';
                button.innerText = 'Less';
            } else {
                fullText.classList.add('hidden');
                shortText.style.display = 'block';
                button.innerText = 'More';
            }
        }
    </script>
</x-app-layout>
