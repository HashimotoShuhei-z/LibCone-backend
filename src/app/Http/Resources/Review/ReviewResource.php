<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $item = $this->resource;

        return [
            'userId' => $item->user->id,
            'userName' => $item->user->name,
            'userIcon' => $item->user->user_icon,
            'reviewRate' => $item->review_rate,
            'reviewTitle' => $item->review_title,
            'reviewContent' => $item->review_content,
            'createdAt' => $item->created_at->format('Y-m-d'),
        ];
    }
}
