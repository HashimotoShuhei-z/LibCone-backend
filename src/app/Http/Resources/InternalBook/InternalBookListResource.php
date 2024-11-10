<?php

namespace App\Http\Resources\InternalBook;

use Illuminate\Http\Resources\Json\JsonResource;

class InternalBookListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'bookId' => $this->book->id,
            'bookName' => $this->book->book_title,
            'bookGenreName' => $this->book->bookGenres->pluck('genre_name'),  // ジャンル名を配列として取得
            'bookImage' => $this->book->book_image,
            'bookPublisher' => $this->book->book_publisher,
            'authorId' => $this->book->author->id,
            'authorName' => $this->book->author->author_name,
            'averageReviewRate' => $this->reviews->avg('review_rate'),  // レビュー平均
            'rentalInformation' => $this->in_office, 
        ];
    }
}
