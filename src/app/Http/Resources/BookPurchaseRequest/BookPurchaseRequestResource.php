<?php

namespace App\Http\Resources\BookPurchaseRequest;

use Illuminate\Http\Resources\Json\JsonResource;

class BookPurchaseRequestResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->book_title,
            'itemPrice' => $this->book_price,
            'itemUrl' => $this->book_url,
            'userId' => $this->user->id,
            'userName' => $this->user->name,
            'userIcon' => $this->user->icon_url,
            'purchaseType' => $this->purchase_type,
            'hopeDeliverAt' => $this->hope_deliver_at->format('Y-m-d'),
            'existInOffice' => $this->exist_in_office,
            'purchaseStatus' => $this->purchase_status,
        ];
    }
}
