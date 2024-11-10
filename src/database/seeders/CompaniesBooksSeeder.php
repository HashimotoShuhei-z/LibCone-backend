<?php

namespace Database\Seeders;

use App\Models\CompanyBook;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompaniesBooksSeeder extends Seeder
{
    public function run()
    {
        CompanyBook::create([
            'company_id' => 1,
            'book_id' => 1,
            'in_office' => true,
        ]);

    }
}
