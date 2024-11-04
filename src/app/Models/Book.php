<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'book_title',
        'book_publisher',
        'book_image',
        'author_id'
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'companies_books');
    }

    public function bookGenres()
    {
        return $this->belongsToMany(BookGenre::class, 'books_book_genres');
    }
}
