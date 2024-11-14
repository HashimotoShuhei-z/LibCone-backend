<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookPurchaseRequest\bookPurchaseReqListRequest;
use App\Http\Requests\BookPurchaseRequest\ConfirmPurchaseRequest;
use App\Http\Requests\BookPurchaseRequest\CreateBookPurchaseRequest;
use App\Http\Resources\BookPurchaseRequest\BookPurchaseRequestResource;
use App\Models\BookPurchaseRequest;
use App\Services\BookPurchaseRequestService;
use Illuminate\Http\JsonResponse;

class BookPurchaseRequestController extends Controller
{
    protected BookPurchaseRequestService $bookPurchaseRequestService;

    public function __construct(BookPurchaseRequestService $bookPurchaseRequestService)
    {
        $this->bookPurchaseRequestService = $bookPurchaseRequestService;
    }

    /**
     * 書籍購入リクエスト一覧を取得
     *
     * @param bookPurchaseReqListRequest $request
     * @return JsonResponse
     */
    public function bookPurchaseReqList(bookPurchaseReqListRequest $request): JsonResponse
    {
        $filters = $request->only(['book_name', 'position_id']);
        $requests = $this->bookPurchaseRequestService->getBookPurchaseRequests($filters);
        return response()->json(BookPurchaseRequestResource::collection($requests));
    }

    /**
     * 書籍購入リクエストを作成
     *
     * @param CreateBookPurchaseRequest $request
     * @return JsonResponse
     */
    public function makeBookPurchaseReq(CreateBookPurchaseRequest $request): JsonResponse
    {
        $bookPurchaseRequest = $this->bookPurchaseRequestService->createBookPurchaseRequest($request);
        return response()->json(['message' => 'Made a purchase-request'], 201);
    }

    public function deleteBookPurchaseReq(BookPurchaseRequest $book_purchase_request): JsonResponse
    {
        $book_purchase_request->delete();
        return response()->json(['message' => 'Purchase-request deleted'], 204);
    }

    /**
     * 購入リクエストを一括で購入確定
     *
     * @param ConfirmPurchaseRequest $request
     * @return JsonResponse
     */
    public function confirmPurchaseRequests(ConfirmPurchaseRequest $request): JsonResponse
    {
        $updatedCount = $this->bookPurchaseRequestService->confirmPurchaseRequests($request->request_ids);

        return response()->json([
            'message' => 'Purchase requests confirmed',
            'updated_count' => $updatedCount,
        ], 200);
    }
}
