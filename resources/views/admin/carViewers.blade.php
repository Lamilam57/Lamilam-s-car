<x-app-layout :role="$role">
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
                </div>
                <div class="car-details card">
                    <div class="flex items-center justify-between">
                        <p class="car-details-price">{{ $car->getPrice() }}</p>
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
                            @if ($role==="admin" || $role==="user")
                            <tr>
                                <th>Number of Views</th>
                                <td>{{ $totalClicks ?? 0 }}</td>
                            </tr>
                            @endif
                            @if ($role==="admin")
                            <tr>
                                <th>Number of Unique Users</th>
                                <td>{{ $uniqueUsers ?? 0 }}</td>
                            </tr>
                            @endif
                            
                        </tbody>
                    </table>
                    <hr />

                    <div class="flex gap-1 my-medium">
                        <a href="{{ route('admin.users.show', $car->owner) }}">
                            <x-user-image :user="$car->owner" class="car-details-owner-image" />
                        </a>
                        {{-- <img src="{{ asset('img/avatar.png') }}" alt="" --}}
                            {{-- class="car-details-owner-image" /> --}}
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
            <div class="container">
                <h2>List of Uniqiue Users</h2>
                <div class="car-items-listing">
                    @foreach ($viewedUsers as $user )
                        <x-users :user="$user" />
                    @endforeach
                </div>
            </div>
            
        </div>
    </main>

</x-app-layout>
