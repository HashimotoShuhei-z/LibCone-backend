<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookBorrow\BorrowBookRequest;
use App\Http\Requests\BookBorrow\BorrowedBookLogRequest;
use App\Http\Requests\BookBorrow\CreateBorrowedBookLogRequest;
use App\Http\Resources\BookBorrow\BorrowedBookLogResource;
use App\Http\Resources\BookBorrow\BorrowResource;
use App\Models\BorrowedBookLog;
use App\Models\CompanyBook;
use App\Services\BorrowedBookService;
use Illuminate\Http\JsonResponse;

class BorrowBookController extends Controller
{
    protected BorrowedBookService $borrowed_book_service;

    public function __construct(BorrowedBookService $borrowed_book_service)
    {
        $this->borrowed_book_service = $borrowed_book_service;
    }

    /**
     * 社員が書籍を借りる
     *
     * @param BorrowBookRequest $request
     * @param CompanyBook $company_book
     * @return JsonResponse
     */
    public function borrowBook(BorrowBookRequest $request, CompanyBook $company_book): JsonResponse
    {
        $borrow_log = $this->borrowed_book_service->borrowBook($request, $company_book);


        return response()->json(new BorrowResource($borrow_log), 201);
    }

    /**
     * 管理者が社内書籍の貸出履歴一覧を取得
     *
     * @param BorrowedBookLogRequest $request
     * @return JsonResponse
     */
    public function borrowedBookLogList(BorrowedBookLogRequest $request): JsonResponse
    {
        $filters = $request->only(['book_name', 'user_name']);
        $borrowed_logs= $this->borrowed_book_service->getBorrowedBookLogs($filters);

        return response()->json(BorrowedBookLogResource::collection($borrowed_logs));
    }

    /**
     * 管理者が社内書籍の貸出履歴を作成
     *
     * @param CreateBorrowedBookLogRequest $request
     * @return JsonResponse
     */
    public function createBorrowedBookLog(CreateBorrowedBookLogRequest $request): JsonResponse
    {
        $borrow_log = $this->borrowed_book_service->createBorrowedBookLog($request);

        return response()->json(new BorrowedBookLogResource($borrow_log), 201);
    }

    /**
     * 管理者が社内書籍の貸出履歴を編集
     *
     * @param CreateBorrowedBookLogRequest $request
     * @param BorrowedBookLog $borrowed_book_log
     * @return JsonResponse
     */
    public function updateBorrowedBookLog(CreateBorrowedBookLogRequest $request, BorrowedBookLog $borrowed_book_log): JsonResponse
    {
        $borrow_log = $this->borrowed_book_service->updateBorrowedBookLog($request, $borrowed_book_log);

        return response()->json(new BorrowedBookLogResource($borrow_log), 200);
    }

    /**
     * 管理者が社内書籍の貸出履歴を削除
     *
     * @param BorrowedBookLog $borrowed_book_log
     * @return JsonResponse
     */
    public function deleteBorrowedBookLog(BorrowedBookLog $borrowed_book_log): JsonResponse
    {
        $this->borrowed_book_service->deleteBorrowedBookLog($borrowed_book_log);

        return response()->json(['message' => 'Borrowed-book-log deleted'], 204);
    }
}
