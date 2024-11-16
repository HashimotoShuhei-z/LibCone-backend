<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class MypageResource extends JsonResource
{
    /**
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $item = $this->resource;

        return [
            'userId' => $item->id,
            'userName' => $item->name,
            'userIcon' => $item->user_icon,
            'monthPoint' => $item->month_point,
            'specialPoint' => $item->special_point,
            'totalReviews' => $item->reviews->count(),
        ];
    }
}
