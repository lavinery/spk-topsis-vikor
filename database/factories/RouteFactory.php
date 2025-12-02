<?php

namespace Database\Factories;

use App\Models\Route as RouteModel;
use App\Models\Mountain;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Route>
 */
class RouteFactory extends Factory
{
    protected $model = RouteModel::class;

    public function definition(): array
    {
        return [
            'mountain_id' => Mountain::factory(),
            'name' => $this->faker->unique()->words(2, true),
            'distance_km' => $this->faker->randomFloat(1, 3, 30),
            'elevation_gain_m' => $this->faker->numberBetween(200, 2500),
            'slope_deg' => $this->faker->randomFloat(1, 5, 45),
            'slope_class' => $this->faker->numberBetween(1, 3),
            'land_cover_key' => $this->faker->randomElement(['hutan', 'savanna', 'campuran']),
            'water_sources_score' => $this->faker->numberBetween(1, 5),
            'support_facility_score' => $this->faker->numberBetween(1, 5),
            'permit_required' => $this->faker->boolean(30),
        ];
    }
}


