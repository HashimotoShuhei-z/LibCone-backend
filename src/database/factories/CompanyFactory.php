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
            'company_name' => 'Test Company',
            'company_genre_id' => CompanyGenre::factory(),
            'monthly_available_points' => 5000,
            'review_bonus_point' => 1000,
        ];
    }
}
