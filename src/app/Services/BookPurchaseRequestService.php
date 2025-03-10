<?php

namespace App\Services;

use App\Http\Requests\BookPurchaseRequest\CreateBookPurchaseRequest;
use App\Models\Book;
use App\Models\BookPurchaseRequest;
use App\Services\Shared\BookHelperService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Exception;

class BookPurchaseRequestService
{
    protected BookHelperService $book_helper_service;

    public function __construct(BookHelperService $book_helper_service)
    {
        $this->book_helper_service = $book_helper_service;
    }
    /**
     * 書籍購入リクエストの一覧を取得
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, BookPurchaseRequest>
     */
    public function getBookPurchaseRequests(array $filters): Collection
    {
        $query = BookPurchaseRequest::with(['user', 'book']);

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
     * @param CreateBookPurchaseRequest $request
     * @return BookPurchaseRequest
     */
    public function createBookPurchaseRequest(CreateBookPurchaseRequest $request): BookPurchaseRequest
    {
        // 書籍が存在するか確認
        $book = Book::where('isbn', $request->isbn)->first();

        if (! $book) {
            // 楽天Books APIで書籍情報を取得
            $book_data = $this->book_helper_service->fetchBookDataFromRakuten($request->isbn);

            if (! $book_data) {
                throw new Exception('Book not found in external API');
            }

            // 著者IDを取得または作成
            $author_id = $this->book_helper_service->getOrCreateAuthorId($book_data['author']);

            // 書籍を作成
            $book = Book::create([
                'isbn' => $book_data['isbn'],
                'book_title' => $book_data['title'],
                'book_publisher' => $book_data['publisher'],
                'book_image' => $book_data['image_url'],
                'author_id' => $author_id,
            ]);
        }

        // dd($book ->id);

        $new_book_purchase_request = [
            'user_id' => Auth::user()->id,
            'book_id' => $book->id,
            'purchase_type' => $request->purchaseType,
            'hope_deliver_at' => $request->hopeDeliveryAt,
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
