<?php

namespace App\Http\Controllers;

use App\Http\Requests\InternalBook\CreateInternalBookRequest;
use App\Http\Requests\InternalBook\InternalBookListRequest;
use App\Http\Resources\InternalBook\InternalBookItemResource;
use App\Http\Resources\InternalBook\InternalBookListResource;
use App\Models\CompanyBook;
use App\Services\InternalBookService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class InternalBookController extends Controller
{
    protected InternalBookService $internal_book_service;

    public function __construct(InternalBookService $internal_book_service)
    {
        $this->internal_book_service = $internal_book_service;
    }

    public function internalBookList(InternalBookListRequest $request): AnonymousResourceCollection
    {
        $company_id = Auth::user()->company_id;
        $filters = $request->only(['book_name', 'book_genre_id']);
        $books = $this->internal_book_service->getInternalBookList($filters, $company_id);
        return InternalBookListResource::collection($books);
    }

    public function internalBookItem(CompanyBook $company_book): AnonymousResourceCollection
    {
        $book = $this->internal_book_service->getInternalBookItem($company_book);
        return InternalBookItemResource::collection($book);
    }

    // public function createInternalBook(CreateInternalBookRequest $request)
    // {
    //     $company_id = Auth::user()->company_id;
    //     $this->internal_book_service->createInternalBook($request, $company_id);
    //     return response()->json(['message' => 'Book created'], 201);
    // }

    // public function deleteIntenalBook(CompanyBook $company_book)
    // {
    //     $company_book->delete();
    //     return response()->json(['message' => 'Book deleted'], 204);
    // }
}
