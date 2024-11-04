<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookPurchaseRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'isbn',
        'book_title',
        'book_url',
        'book_price',
        'purchase_type',
        'hope_deliver_at',
        'purchase_status',
    ];

}
