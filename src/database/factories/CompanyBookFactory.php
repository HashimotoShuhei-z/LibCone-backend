<?php

namespace Database\Factories;

use App\Models\CompanyBook;
use App\Models\Book;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyBookFactory extends Factory
{
    protected $model = CompanyBook::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'book_id' => Book::factory(),
            'in_office' => true,
        ];
    }
}
