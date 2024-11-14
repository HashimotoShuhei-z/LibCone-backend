<?php

namespace App\Http\Resources\BookPurchaseRequest;

use Illuminate\Http\Resources\Json\JsonResource;

class BookPurchaseRequestResource extends JsonResource
{
    /**
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $item = $this->resource;

        return [
            'title' => $item->book_title,
            'itemPrice' => $item->book_price,
            'itemUrl' => $item->book_url,
            'userId' => $item->user->id,
            'userName' => $item->user->name,
            'userIcon' => $item->user->icon_url,
            'purchaseType' => $item->purchase_type,
            'hopeDeliverAt' => $item->hope_deliver_at->format('Y-m-d'),
            'existInOffice' => $item->exist_in_office,
            'purchaseStatus' => $item->purchase_status,
        ];
    }
}
