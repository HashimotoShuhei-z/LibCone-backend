<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftRequest extends Model
{
    /**
     * @phpstan-use HasFactory<\Database\Factories\UserFactory>
     */
    use HasFactory;

    protected $fillable = [
        'point_send_user_id',
        'point_received_user_id',
        'point',
        'is_gifted'
    ];
}
