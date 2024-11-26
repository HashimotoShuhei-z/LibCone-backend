<?php

namespace App\Http\Resources\BookBorrow;

use Illuminate\Http\Resources\Json\JsonResource;

class BorrowedBookLogResource extends JsonResource
{
    /**
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $item = $this->resource;
        return [
            'userId' => $item->user->id,
            'userName' => $item->user->name,
            'bookId' => $item->company_book_id,
            'bookName' => $item->companyBook->book->book_title,
            'startDate' => $item->start_date->format('Y-m-d'),
            'endDate' => $item->end_date->format('Y-m-d'),
            'returnedAt' => $item->returned_at ? $item->returned_at->format('Y-m-d') : null,
        ];
    }
}
