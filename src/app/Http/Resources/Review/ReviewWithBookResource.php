<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewWithBookResource extends JsonResource
{
    /**
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $item = $this->resource;

        return [
            'book' => [
                'bookId' => $item->companyBook->book->id,
                'bookName' => $item->companyBook->book->book_title,
                // 'bookGenreName' => $item->companyBook->book->bookGenres->genre_name,
                'bookImage' => $item->companyBook->book->book_image,
                'bookPublisher' => $item->companyBook->book->book_publisher,
                'authorId' => $item->companyBook->book->author->id,
                'authorName' => $item->companyBook->book->author->author_name,
            ],
            'review' => new ReviewResource($item),
        ];
    }
}
