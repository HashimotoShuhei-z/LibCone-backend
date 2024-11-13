<?php

namespace Database\Seeders;

use App\Models\BookGenre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookGenreSeeder extends Seeder
{
    public function run()
    {
        BookGenre::create([
            'genre_name' => 'IT'
        ]);
        BookGenre::create([
            'genre_name' => '営業'
        ]);
        BookGenre::create([
            'genre_name' => '自己啓発'
        ]);
    }
}
