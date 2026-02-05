<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\User;
use App\Models\FavouriteCar;
use Illuminate\Database\Eloquent\Factories\Factory;

class FavouriteCarFactory extends Factory
{
    protected $model = FavouriteCar::class;

    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'car_id' => Car::inRandomOrder()->first()?->id ?? Car::factory(),
        ];
    }
}
