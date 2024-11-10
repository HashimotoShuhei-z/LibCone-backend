<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run()
    {
        Author::create([
            'author_name' => 'John Doe',
        ]);

        Author::create([
            'author_name' => 'Jane Smith',
        ]);
    }
}
