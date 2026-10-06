<?php

namespace Database\Factories;

use App\Models\Courier;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourierFactory extends Factory
{
    protected $model = Courier::class;

    public function definition(): array
    {
        return [
            'name'          => $this->faker->name(),
            'phone'         => $this->faker->phoneNumber(),
            'email'         => $this->faker->unique()->safeEmail(),
            'level'         => $this->faker->numberBetween(1, 5),
            'vehicle_type'  => $this->faker->randomElement(['motorcycle', 'car', 'truck']),
            'vehicle_plate' => strtoupper($this->faker->bothify('?? #### ??')),
            'address'       => $this->faker->address(),
            'status'        => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
