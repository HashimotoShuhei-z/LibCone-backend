<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'isbn' => $this->faker->unique()->isbn13,
            'book_title' => $this->faker->sentence(3),
            'book_publisher' => $this->faker->company,
            'book_image' => $this->faker->imageUrl(200, 300, 'books', true),
            'author_id' => Author::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
