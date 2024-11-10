<?php

namespace App\Services;

use App\Models\CompanyBook;
use Illuminate\Database\Eloquent\Collection;

class InternalBookService
{
    /**
     * @param array<string, mixed> $filters
     * @param int $company_id
     * @return Collection<int, CompanyBook>
     */    
    public function getInternalBookList(array $filters, int $company_id): Collection
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

    /**
     * @param CompanyBook $company_book
     * @return Collection<int, CompanyBook>
     */
    public function getInternalBookItem(CompanyBook $company_book): Collection
    {
        return $company_book->with(['book', 'reviews'])->get();
    }

    // public function createInternalBook($data, $companyId)
    // {
    //     // 書籍の登録ロジック（省略）
    // }
}
