<x-app-layout :role="$role">
    <div class="admin-container">

        {{-- User Details --}}
        <div class="user-card">
            <x-user-image :user="$user" class="my-cars-img-thumbnail" />
            <h2>{{ $user->name }}</h2>
            <p>Email: {{ $user->email ?? 'N/A' }}</p>
            <p>Phone: {{ $user->phone ?? 'N/A' }}</p>
            <p>State: {{ $state?->name ?? 'N/A' }}</p>
            <p>City: {{ $city?->name ?? 'N/A' }}</p>
            <p>Address: {{ $user->address ?? 'N/A' }}</p>
            <p>Total Cars Owned: {{ $cars->count() }}</p>
            <p>Total Cars Viewed: {{ $viewedCars->count() }}</p>
        </div>

        {{-- Cars Owned --}}
        <div class="section">
            <h3>Cars Owned</h3>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Total Clicks</th>
                        <th>Unique Views</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cars as $car)
                        <tr>
                            <td>
                                <a href="{{ route('car.show', $car) }}">
                                    <x-car-image :car="$car" class="my-cars-img-thumbnail" />
                                </a>
                            </td>
                            <td>{{ $car->maker->name ?? '' }} {{ $car->model->name ?? '' }}</td>
                            <td>{{ $car->views_sum_views ?? 0 }}</td>
                            <td>{{ $car->unique_views ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No cars owned.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $cars->onEachSide(1)->links() }}
        </div>

        {{-- Cars Viewed --}}
        <div class="section">
            <h3>Cars Viewed</h3>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Car</th>
                        <th>Total Clicks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($viewedCars as $view)
                        <tr>
                            <td>
                                <a href="{{ route('car.show', $view->car) }}">
                                    <x-car-image :car="$view->car" class="my-cars-img-thumbnail" />
                                </a>
                            </td>
                            <td>{{ $view->car->maker->name ?? '' }} {{ $view->car->model->name ?? '' }}</td>
                            <td>{{ $view->total_clicks }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No cars viewed yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</x-app-layout>
