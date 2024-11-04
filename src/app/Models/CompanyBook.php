<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyBook extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'companies_books';

    protected $fillable = [
        'book_id',
        'company_id',
        'in_office'
    ];

    public function borrowUsers()
    {
        return $this->belongsToMany(User::class, 'borrowed_book_logs');
    }

    public function reviewUsers()
    {
        return $this->belongsToMany(User::class, 'borrowed_book_logs');
    }

}
