<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    /**
     * @phpstan-use HasFactory<\Database\Factories\UserFactory>
     */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_book_id',
        'review_title',
        'review_content',
        'review_rate'
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

    /**
     *
     * @return BelongsToMany<ReviewReaction, $this>
     */
    public function reactionUser(): BelongsToMany
    {
        return $this->belongsToMany(ReviewReaction::class, 'review_reactions');
    }
}
