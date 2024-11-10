<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'userId' => $this->user->id,
            'userName' => $this->user->name,
            'userIcon' => $this->user->user_icon,
            'reviewRate' => $this->review_rate,
            'reviewTitle' => $this->review_title,
            'reviewContent' => $this->review_content,
            'createdAt' => $this->created_at->format('Y-m-d'),
        ];
    }
}
