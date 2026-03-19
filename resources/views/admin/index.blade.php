<x-app-layout :role="$role">
    {{-- Messages --}}
    <x-success-message />
    <x-error-message />

    <div class="admin-dashboard container-fluid">

        <!-- Page Header -->
        <div class="dashboard-header">
            <h2>Admin Dashboard</h2>
            <span>{{ now()->format('l, d M Y') }}</span>
        </div>

        <!-- Stats Cards -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <h6>Total Cars</h6>
                <h3>{{ $totalCars ?? 0 }}</h3>
            </div>

            <a href="{{ route('car.search') }}" style="text-decoration: none">
                <div class="stat-card">
                    <h6>Available Cars</h6>
                    <h3>{{ $availableCars ?? 0 }}</h3>
                </div>
            </a>
            <a href="{{ route('car.notavailable') }}" style="text-decoration: none">
                <div class="stat-card">
                    <h6>Not Available Cars</h6>
                    <h3>{{ $notavailableCars ?? 0 }}</h3>
                </div>
            </a>

            <a href="{{ route('admin.viewedCar') }}" style="text-decoration: none">
                <div class="stat-card">
                    <h6>Total Viewed Cars</h6>
                    <h3>{{ $totalViews ?? 0 }}</h3>
                </div>
            </a>

            <a href="{{ route('admin.users') }}" style="text-decoration: none">
                <div class="stat-card">
                    <h6>Total Users</h6>
                    <h3>{{ $totalUsers ?? 0 }}</h3>
                </div>
            </a>
        </div>
        <x-search-form :models="$models" :makers="$makers" :carTypes="$carTypes" :fuelTypes="$fuelTypes" :states="$states" />

        <div class="clearfix"></div>

        <!-- Recent Cars -->
        <div class="dashboard-table-card">
            <h4>Recent Cars</h4>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Car Name</th>
                            <th>Year</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cars as $car)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $car->maker->name }} {{ $car->model->name }}</td>
                                <td>{{ $car->year }}</td>
                                <td>₦{{ number_format($car->price) }}</td>
                                <td>
                                    <span
                                        class="status-badge {{ $car->published_at ? 'status-active' : 'status-inactive' }}">
                                        {{ $car->published_at ? 'Available' : 'Unavailable' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('car.show', $car) }}" class="btn btn-secondary w-100"> View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No cars found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{ $cars->onEachSide(1)->links() }}

        <!-- Quick Actions -->
        <div class="row g-3">
            <div class="col-md-4">
                <a href="#" class="btn btn-dark w-100">➕ Add New Car</a>
            </div>
            <div class="col-md-4">
                <a href="#" class="btn btn-secondary w-100">👤 Manage Users</a>
            </div>
            <div class="col-md-4">
                <a href="#" class="btn btn-warning w-100">📅 View Bookings</a>
            </div>
        </div>
    </div>
</x-app-layout>
