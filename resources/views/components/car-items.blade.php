@props(['car', 'isInWatchList' => false])

<div class="car-item card">
    <a href="{{ route('car.show', $car) }}">
        <x-car-image :car="$car" class="car-item-img rounded-t" />
    </a>
    <div class="p-medium">
        <div class="flex items-center justify-between">
            <small class="m-0 text-muted">{{ $car->city->name }}</small>
            <x-favourite-heart :car="$car"/>
        </div>
        <h2 class="car-item-title">{{ $car->year }} - {{ $car->maker->name }} {{ $car->model->name }}</h2>
        <p class="car-item-price">{{ $car->getPrice() }}</p>
        <hr />
        <p class="m-0">
            <span class="car-item-badge">{{ $car->carType->name }}</span>
            <span class="car-item-badge">{{ $car->fuelType->name }}</span>
        </p>
    </div>
</div>
