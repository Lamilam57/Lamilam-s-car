@props(['car' => null, 'class' => '', 'id' => ''])

@if ($car->primaryImage)
    <img
        src="{{ str_starts_with($car->primaryImage->image_path, 'https')
            ? $car->primaryImage->image_path
            : asset('storage/' . $car->primaryImage->image_path) }}"
        alt=""
        class="{{ $class }}"
        id="{{ $id }}"
    >
@else
    <img
        src="{{ asset('img/car-png-39071.png') }}"
        alt=""
        class="{{ $class }}"
        id="{{ $id }}"
    >
@endif
