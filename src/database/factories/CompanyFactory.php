<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CompanyGenre;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'company_name' => $this->faker->company,
            'company_genre_id' => CompanyGenre::factory(),
            'monthly_available_points' => $this->faker->numberBetween(1000, 10000),
            'review_bonus_point' => $this->faker->numberBetween(100, 500),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
