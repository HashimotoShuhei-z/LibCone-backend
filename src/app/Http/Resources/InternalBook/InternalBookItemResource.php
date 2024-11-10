<?php

namespace App\Http\Resources\InternalBook;

use App\Http\Resources\Review\ReviewResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InternalBookItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'book' => new InternalBookListResource($this->resource),
            'reviews' => ReviewResource::collection($this->resource->reviews),
        ];
    }
}
