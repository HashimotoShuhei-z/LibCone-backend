<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookPurchaseRequest extends Model
{
    /**
     * @phpstan-use HasFactory<\Database\Factories\UserFactory>
     */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'isbn',
        'book_title',
        'book_url',
        'book_price',
        'purchase_type',
        'hope_deliver_at',
        'purchase_status',
    ];

    protected $casts = [
        'book_price' => 'integer',
        'hope_deliver_at' => 'date',
    ];

    /**
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     *
     * @return BelongsTo<Book, $this>
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
