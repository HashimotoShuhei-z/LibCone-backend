<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyBook extends Model
{
    /**
     * @phpstan-use HasFactory<\Database\Factories\UserFactory>
     */
    use HasFactory, SoftDeletes;

    protected $table = 'companies_books';

    protected $fillable = [
        'book_id',
        'company_id',
        'in_office'
    ];

    /**
     *
     * @return BelongsTo<Book, $this>
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     *
     * @return HasMany<Review, $this>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     *
     * @return BelongsToMany<User, $this>
     */
    public function borrowUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'borrowed_book_logs');
    }

    /**
     *
     * @return BelongsToMany<User, $this>
     */
    public function reviewUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'borrowed_book_logs');
    }

}
