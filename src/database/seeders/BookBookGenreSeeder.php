<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookBookGenreSeeder extends Seeder
{
    public function run()
    {
        DB::table('books_book_genres')->insert([
            'book_id' => 1,
            'book_genre_id' => 1,
        ]);
    }
}
