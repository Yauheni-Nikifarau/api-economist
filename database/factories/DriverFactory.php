<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->words(2, true);
        $slug = str_replace(' ', '_', strtolower($name));
        return [
            'slug' => $slug,
            'name' => $name,
        ];
    }
}
