<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BookGenre extends Model
{
    /**
     * @phpstan-use HasFactory<\Database\Factories\UserFactory>
     */
    use HasFactory;

    protected $fillable = [
        'genre_name',
    ];

    /**
     *
     * @return BelongsToMany<Book, $this>
     */
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'books_book_genres');
    }
}
