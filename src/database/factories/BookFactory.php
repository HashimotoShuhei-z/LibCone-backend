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
            'book_title' => 'Test Book',
            'book_publisher' => 'Test Publisher',
            'book_image' => 'https://example.com/image.jpg',
            'author_id' => Author::factory(),
        ];
    }
}
