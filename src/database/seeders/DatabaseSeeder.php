<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CompanyGenreSeeder::class,
            CompanySeeder::class,
            PositionSeeder::class,
            AuthorSeeder::class,
            BookSeeder::class,
            UserSeeder::class,
            CompaniesBooksSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
