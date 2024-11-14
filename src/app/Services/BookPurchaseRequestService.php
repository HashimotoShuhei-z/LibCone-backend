<?php

namespace App\Services;

use App\Http\Requests\BookPurchaseRequest\CreateBookPurchaseRequest;
use App\Models\BookPurchaseRequest;
use Illuminate\Database\Eloquent\Collection;

class BookPurchaseRequestService
{
    /**
     * 書籍購入リクエストの一覧を取得
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, BookPurchaseRequest>
     */
    public function getBookPurchaseRequests(array $filters): Collection
    {
        $query = BookPurchaseRequest::with('user');

        if (! empty($filters['book_name'])) {
            $query->where('book_title', 'like', '%' . $filters['book_name'] . '%');
        }

        if (! empty($filters['position_id'])) {
            $query->whereHas('user.positions', function ($q) use ($filters) {
                $q->where('positions.id', $filters['position_id']);
            });
        }

        return $query->get();
    }

    /**
     * 新しい書籍購入リクエストを作成
     *
     * @param CreateBookPurchaseRequest $data
     * @return BookPurchaseRequest
     */
    public function createBookPurchaseRequest(CreateBookPurchaseRequest $data): BookPurchaseRequest
    {
        // テーブルのカラムに合わせてデータを整形
        $new_book_purchase_request = [
            'user_id' => $data->userId,
            'isbn' => $data->isbn,
            'book_title' => $data->title,
            'book_url' => $data->itemUrl,
            'book_price' => $data->itemPrice,
            'purchase_type' => $data->purchaseType,
            'hope_deliver_at' => $data->hopeDeliveryAt,
        ];
        return BookPurchaseRequest::create($new_book_purchase_request);
    }

    /**
     * 一括購入確定処理
     *
     * @param array<int, int> $request_ids
     * @return int
     */
    public function confirmPurchaseRequests(array $request_ids): int
    {
        return BookPurchaseRequest::whereIn('id', $request_ids)->update(['purchase_status' => 1]);
    }
}
