<?php

namespace Database\Seeders;

use App\Models\CompanyGenre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyGenreSeeder extends Seeder
{
    public function run()
    {
        CompanyGenre::create([
            'genre_name' => 'IT',
            'image_color' => '#0000FF',
        ]);

        CompanyGenre::create([
            'genre_name' => 'Finance',
            'image_color' => '#00FF00',
        ]);

        CompanyGenre::create([
            'genre_name' => 'Education',
            'image_color' => '#FF0000',
        ]);
    }
}
