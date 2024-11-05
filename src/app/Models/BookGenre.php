<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookGenre extends Model
{
    use HasFactory;

    protected $fillable = [
        'genre_name',
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'books_book_genres');
    }
}
