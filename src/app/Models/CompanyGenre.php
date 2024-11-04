<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyGenre extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'genre_name',
        'image_color'
    ];

    public function companies()
    {
        return $this->hasMany(Company::class);
    }
}
