<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run()
    {
        Company::create([
            'company_name' => 'Test Company',
            'company_genre_id' => 1,
        ]);
    }
}
