<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPosition extends Model
{
    /**
     * @phpstan-use HasFactory<\Database\Factories\UserFactory>
     */
    use HasFactory;

    protected $table = 'users_positions';

    protected $fillable = [
        'user_id',
        'position_id',
        'experience_years'
    ];
}
