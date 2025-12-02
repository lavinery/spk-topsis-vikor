<?php

namespace Database\Factories;

use App\Models\Mountain;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Mountain>
 */
class MountainFactory extends Factory
{
    protected $model = Mountain::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
            'elevation_m' => $this->faker->numberBetween(500, 3800),
            'province' => $this->faker->randomElement(['Jawa Barat', 'Jawa Tengah', 'Jawa Timur']),
            'lat' => $this->faker->latitude(-11, 6),
            'lng' => $this->faker->longitude(95, 141),
            'status' => $this->faker->randomElement(['open', 'closed']),
        ];
    }
}


