<?php

namespace App\Http\Controllers;

use App\Http\Requests\Book\ScanSearchRequest;
use App\Http\Requests\InternalBook\CreateInternalBookRequest;
use App\Http\Requests\InternalBook\InternalBookListRequest;
use App\Http\Resources\InternalBook\InternalBookItemResource;
use App\Http\Resources\InternalBook\InternalBookListResource;
use App\Models\CompanyBook;
use App\Services\InternalBookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class InternalBookController extends Controller
{
    protected InternalBookService $internal_book_service;

    public function __construct(InternalBookService $internal_book_service)
    {
        $this->internal_book_service = $internal_book_service;
    }

    /**
     * 社内書籍の一覧取得
     *
     * @param InternalBookListRequest $request
     * @return AnonymousResourceCollection
     */
    public function internalBookList(InternalBookListRequest $request): AnonymousResourceCollection
    {
        $company_id = Auth::user()->company_id;
        $filters = $request->only(['book_name', 'book_genre_id']);
        $books = $this->internal_book_service->getInternalBookList($filters, $company_id);
        return InternalBookListResource::collection($books);
    }

    /**
     * 社内書籍の詳細取得
     *
     * @param CompanyBook $company_book
     * @return AnonymousResourceCollection
     */
    public function internalBookItem(CompanyBook $company_book): AnonymousResourceCollection
    {
        $book = $this->internal_book_service->getInternalBookItem($company_book);
        return InternalBookItemResource::collection($book);
    }

    /**
     * 社内書籍を作成
     *
     * @param CreateInternalBookRequest $request
     * @return JsonResponse
     */
    public function createInternalBook(CreateInternalBookRequest $request): JsonResponse
    {
        $company_id = Auth::user()->company_id;
        $this->internal_book_service->createBook($request, $company_id);
        return response()->json(['message' => 'Book created'], 201);
    }

    /**
     * 社内書籍を削除
     *
     * @param CompanyBook $company_book
     * @return JsonResponse
     */
    public function deleteIntenalBook(CompanyBook $company_book): JsonResponse
    {
        $company_book->delete();
        return response()->json(['message' => 'Book deleted'], 204);
    }

    /**
     * 書籍のバーコードで社内書籍を検索
     *
     * @param ScanSearchRequest $request
     * @return JsonResponse
     */
    public function scanSearch(ScanSearchRequest $request): JsonResponse
    {
        $company_book = $this->internal_book_service->findBookByIsbn($request->isbn);

        if (! $company_book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        return response()->json(new InternalBookItemResource($company_book));
    }
}
