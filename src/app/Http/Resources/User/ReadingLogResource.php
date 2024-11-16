<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class ReadingLogResource extends JsonResource
{
    /**
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $item = $this->resource;

        return [
            'bookId' => $item->book->id,
            'bookName' => $item->book->book_title,
            'bookGenreName' => $item->book->bookGenres->pluck('genre_name')->toArray(),
            'bookImage' => $item->book->book_image,
            'authorId' => $item->book->author->id,
            'authorName' => $item->book->author->author_name,
            'reviewId' => $item->id,
            'reviewRate' => $item->review_rate,
            'reviewTitle' => $item->review_title,
        ];
    }
}
