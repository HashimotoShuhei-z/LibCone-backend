<?php

namespace App\Http\Resources\InternalBook;

use Illuminate\Http\Resources\Json\JsonResource;

class InternalBookListResource extends JsonResource
{
    /**
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $item = $this->resource;

        return [
            'companyBookId' => $item->id,
            'bookName' => $item->book->book_title,
            'bookGenreName' => $item->book->bookGenres->pluck('genre_name'),  // ジャンル名を配列として取得
            'bookImage' => $item->book->book_image,
            'bookPublisher' => $item->book->book_publisher,
            'authorId' => $item->book->author->id,
            'authorName' => $item->book->author->author_name,
            'averageReviewRate' => $item->reviews->avg('review_rate'),  // レビュー平均
            'rentalInformation' => $item->in_office,
        ];
    }
}
