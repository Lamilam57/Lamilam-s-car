<x-app-layout :role="$role">
    <main class="container py-4">
        <h1 class="mb-4">Pending Car Submissions</h1>

        <x-success-message />
        <x-error-message />

        @if(count($pendingCars) == 0)
            <p>No pending car submissions.</p>
        @else
            <div class="list-group">
                @foreach($pendingCars as $car)
                    <div class="list-group-item">
                        <h5>{{ $car['maker_name'] ?? 'Unknown Maker' }} {{ $car['model_name'] ?? 'Unknown Model' }}</h5>
                        <p>Price: ₦{{ number_format($car['price'] ?? 0) }}</p>
                        <p>VIN: {{ $car['vin'] ?? 'N/A' }}</p>
                        <p>Status: Not subscribed</p>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</x-app-layout>