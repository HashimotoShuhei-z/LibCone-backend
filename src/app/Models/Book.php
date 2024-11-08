<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    /**
     * @phpstan-use HasFactory<\Database\Factories\UserFactory>
     */
    use HasFactory;

    protected $fillable = [
        'isbn',
        'book_title',
        'book_publisher',
        'book_image',
        'author_id'
    ];

    /**
     *
     * @return BelongsTo<Author, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    /**
     *
     * @return BelongsToMany<Company, $this>
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'companies_books');
    }

    /**
     *
     * @return BelongsToMany<BookGenre, $this>
     */
    public function bookGenres(): BelongsToMany
    {
        return $this->belongsToMany(BookGenre::class, 'books_book_genres');
    }
}
