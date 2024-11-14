<?php

namespace App\Http\Resources\Gift;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GiftLogResource extends JsonResource
{
    /**
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $item = $this->resource;

        return [
            'pointSendUserId' => $item->point_send_user_id,
            'pointReceivedUserId' => $item->point_received_user_id,
            'point' => $item->point,
            'created_at' => $item->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
