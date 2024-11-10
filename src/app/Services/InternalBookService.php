<?php

namespace App\Services;

use App\Models\CompanyBook;

class InternalBookService
{
    public function getInternalBookList($filters, $company_id)
    {
        $query = CompanyBook::query()
            ->with(['book', 'reviews', 'book.bookGenres', 'book.author'])
            ->where('company_id', $company_id);

        // 書籍名による部分一致検索
        if (! empty($filters['book_name'])) {
            $query->whereHas('book', function ($q) use ($filters) {
                $q->where('book_title', 'like', '%' . $filters['book_name'] . '%');
            });
        }

        // 書籍ジャンルIDによるフィルタリング
        if (! empty($filters['book_genre_id'])) {
            $query->whereHas('book.bookGenres', function ($q) use ($filters) {
                $q->where('book_genre_id', $filters['book_genre_id']);
            });
        }

        return $query->get();
    }

    public function getInternalBookItem(CompanyBook $companyBook)
    {
        return $companyBook->with(['book', 'reviews'])->get();
    }

    public function createInternalBook($data, $companyId)
    {
        // 書籍の登録ロジック（省略）
    }
}
