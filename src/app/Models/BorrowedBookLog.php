<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowedBookLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_book_id',
        'start_date',
        'end_data',
        'returned_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function companyBook()
    {
        return $this->belongsTo(CompanyBook::class);
    }
}
