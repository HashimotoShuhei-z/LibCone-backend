<?php

namespace App\Services;

use App\Http\Requests\BookBorrow\BorrowBookRequest;
use App\Http\Requests\BookBorrow\CreateBorrowedBookLogRequest;
use App\Models\BorrowedBookLog;
use App\Models\CompanyBook;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BorrowedBookService
{
    /**
     * 社内書籍を借りる
     *
     * @param BorrowBookRequest $request
     * @param CompanyBook $company_book
     * @return BorrowedBookLog|null
     */
    public function borrowBook(BorrowBookRequest $request, CompanyBook $company_book): ?BorrowedBookLog
    {
        return DB::transaction(function () use ($request, $company_book) {
            $user = Auth::user();
    
            // 貸出可能か確認
            if (! $company_book->in_office) {
                return null;
            }
    
            // 貸出ログの作成
            $borrowLog = BorrowedBookLog::create([
                'user_id' => $user->id,
                'company_book_id' => $company_book->id,
                'start_date' => $request->startDate,
                'end_date' => $request->endDate,
            ]);
    
            // 書籍の貸出状態を更新
            $company_book->in_office = false;
            $company_book->save();
    
            return $borrowLog;
        });
    }

    /**
     * 社内書籍の貸出履歴一覧を取得
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, BorrowedBookLog>
     */
    public function getBorrowedBookLogs(array $filters): Collection
    {
        $query = BorrowedBookLog::with(['user', 'companyBook.book']);

        if (! empty($filters['book_name'])) {
            $query->whereHas('companyBook.book', function ($q) use ($filters) {
                $q->where('book_title', 'like', '%' . $filters['book_name'] . '%');
            });
        }

        if (! empty($filters['user_name'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['user_name'] . '%');
            });
        }

        return $query->get();
    }

    /**
     * 社内書籍の貸出履歴を作成
     *
     * @param CreateBorrowedBookLogRequest $request
     * @return BorrowedBookLog|null
     */
    public function createBorrowedBookLog(CreateBorrowedBookLogRequest $request): BorrowedBookLog|null
    {
        return DB::transaction(function () use ($request) {
            $company_book = CompanyBook::where('id', $request->book_id)
                ->where('in_office', true)
                ->first();
    
            // 書籍が見つからない、または貸出中の場合は null を返す
            if (! $company_book) {
                return null;
            }
    
            // 貸出履歴を作成
            $borrowed_book_log = BorrowedBookLog::create([
                'user_id' => $request->user_id,
                'company_book_id' => $company_book->id,
                'start_date' => $request->startDate,
                'end_date' => $request->endDate,
                'returned_at' => $request->returnDate ?? null,
            ]);
    
            // 書籍の貸出状態を更新
            $company_book->in_office = false;
            $company_book->save();
    
            return $borrowed_book_log;
        });
    }

    /**
     * 社内書籍の貸出履歴を更新
     *
     * @param BorrowedBookLog $borrowed_book_log
     * @param CreateBorrowedBookLogRequest $request
     * @return BorrowedBookLog
     */
    public function updateBorrowedBookLog(CreateBorrowedBookLogRequest $request, BorrowedBookLog $borrowed_book_log): BorrowedBookLog
    {
        $borrowed_book_log->update([
            'user_id' => $request->user_id,
            'company_book_id' => $request->user_id,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
            'returned_at' => $request->returnDate ?? null,
        ]);

        return $borrowed_book_log;
    }

    /**
     * 社内書籍の貸出履歴を削除
     *
     * @param BorrowedBookLog $borrowed_book_log
     * @return bool
     */
    public function deleteBorrowedBookLog(BorrowedBookLog $borrowed_book_log): bool
    {
        return $borrowed_book_log->delete();
    }
}
