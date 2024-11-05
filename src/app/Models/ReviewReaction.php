<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewReaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'review_id',
        'stamp_id'
    ];

    public function stamp()
    {
        return $this->belongsTo(Stamp::class);
    }

    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
