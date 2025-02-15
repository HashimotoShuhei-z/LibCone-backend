<?php

namespace Database\Factories;

use App\Models\CompanyGenre;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyGenreFactory extends Factory
{
    protected $model = CompanyGenre::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'genre_name' => 'IT',
            'image_color' => '#4169e1',
        ];
    }
}
