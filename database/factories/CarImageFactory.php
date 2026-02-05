<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarImageFactory extends Factory
{
    protected array $brands = [
        'toyota',
        'honda',
        'bmw',
        'mercedes',
        'lexus',
        'ford',
        'kia',
        'hyundai',
        'nissan',
    ];

    public function definition()
    {
        return [
            'image_path' => function (array $attributes) {
                $car = Car::with('maker')->find($attributes['car_id']);

                $maker = strtolower($car->maker->name ?? 'car');

                $brand = collect($this->brands)
                    ->first(fn ($b) => str_contains($maker, $b))
                    ?? 'car';

                return "https://loremflickr.com/1200/800/{$brand},car?lock=" . fake()->numberBetween(1, 10000);
            },

            'position' => function (array $attributes) {
                return Car::find($attributes['car_id'])
                    ->images()
                    ->count() + 1;
            },
        ];
    }
}
