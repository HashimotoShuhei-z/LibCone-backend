<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'book_company_id',
        'review_title',
        'review_content',
        'review_rate'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function companyBook()
    {
        return $this->belongsTo(CompanyBook::class);
    }

    public function reactionUser()
    {
        return $this->belongsToMany(ReviewReaction::class, 'review_reactions');
    }
}
