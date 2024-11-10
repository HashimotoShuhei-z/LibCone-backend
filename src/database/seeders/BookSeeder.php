<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run()
    {
        Book::create([
            'isbn' => '9781234567897',
            'book_title' => 'Laravel入門',
            'book_publisher' => 'Tech Publisher',
            'book_image' => 'default_book.png',
            'author_id' => 1,
        ]);
    }
}
