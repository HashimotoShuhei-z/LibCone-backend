<?php

namespace App\Services;

use App\Http\Requests\Review\CreateReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class BookReviewService
{
    /**
     * 口コミ一覧を取得
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, Review>
     */
    public function getReviews(array $filters): Collection
    {
        $query = Review::with(['user', 'companyBook.book.author']);

        if (! empty($filters['book_name'])) {
            $query->whereHas('companyBook.book', function ($q) use ($filters) {
                $q->where('book_title', 'like', '%' . $filters['book_name'] . '%');
            });
        }

        if (! empty($filters['book_genre_id'])) {
            $query->whereHas('companyBook.book.bookGenres', function ($q) use ($filters) {
                $q->where('id', $filters['book_genre_id']);
            });
        }

        if (! empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query->get();
    }

    /**
     * 口コミを作成
     *
     * @param CreateReviewRequest $data
     * @return Review
     *
     * @throws ModelNotFoundException 口コミが重複する場合
     */
    public function createReview(CreateReviewRequest $data): Review
    {
        $login_user_id = Auth::id();

        // ユーザーが同じ書籍に対して口コミを書いていないかの重複チェック
        $company_book_id = $data->bookId;
        if (Review::where('user_id', $login_user_id)->where('company_book_id', $company_book_id)->exists()) {
            throw new ModelNotFoundException('既にこの書籍に対する口コミが存在します。');
        }

        $new_review = [
            'user_id' => $login_user_id,
            'company_book_id' => $data->bookId,
            'review_title' => $data->reviewTitle,
            'review_content' => $data->reviewContent,
            'review_rate' => $data->reviewRate,
        ];

        // 口コミの作成
        $review = Review::create($new_review);

        $user = User::findOrFail($login_user_id);

        // ユーザーの会社に紐づくreview_bonus_pointを取得
        $review_bonus_point = $user->company->review_bonus_point ?? 0;

        // 口コミを投稿したユーザーのspecial_pointにreview_bonus_pointを加算して更新
        $user->special_point += $review_bonus_point;
        $user->save();

        return $review;
    }

    /**
     * 口コミを更新
     *
     * @param Review $review
     * @param UpdateReviewRequest $data
     * @return bool
     */
    public function updateReview(Review $review, UpdateReviewRequest $data): bool
    {
        $user = Auth::user();

        // ログインユーザーが投稿者本人か、または管理者であるかを確認
        if ($review->user_id !== $user->id && $user->type_id !== 1) {
            throw new AuthorizationException('更新権限がありません。');
        }

        $update_review = [
            'review_title' => $data->reviewTitle,
            'review_content' => $data->reviewContent,
            'review_rate' => $data->reviewRate,
        ];

        return $review->update($update_review);
    }

    /**
     * 口コミを削除
     *
     * @param Review $review
     * @return bool
     */
    public function deleteReview(Review $review): bool
    {
        return $review->delete();
    }
}
