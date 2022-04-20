<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FuellingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'driver_id' => $this->faker->numberBetween(1, 5),
            'car_id' => $this->faker->numberBetween(1, 5),
            'fuel_type' => $this->faker->randomElement(['gas_oil', 'gasoline']),
            'amount' => $this->faker->numberBetween(2000, 10000)
        ];
    }
}
