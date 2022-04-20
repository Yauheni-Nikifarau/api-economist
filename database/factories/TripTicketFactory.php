<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TripTicketFactory extends Factory
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
        ];
    }
}
