<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowedBookLog extends Model
{
    /**
     * @phpstan-use HasFactory<\Database\Factories\UserFactory>
     */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_book_id',
        'start_date',
        'end_data',
        'returned_at'
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
     * @return BelongsTo<CompanyBook, $this>
     */
    public function companyBook(): BelongsTo
    {
        return $this->belongsTo(CompanyBook::class);
    }
}
