<?php

namespace App\Http\Resources\BookBorrow;

use Illuminate\Http\Resources\Json\JsonResource;

class BorrowResource extends JsonResource
{
    /**
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $item = $this->resource;
        return [
            'userId' => $item->user_id,
            'bookId' => $item->company_book_id,
            'startDate' => $item->start_date,
            'endDate' => $item->end_date,
        ];
    }
}
