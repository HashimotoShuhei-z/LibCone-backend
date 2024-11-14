<?php

namespace App\Http\Controllers;

use App\Http\Requests\Review\CreateReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use App\Http\Resources\Review\ReviewResource;
use App\Http\Resources\Review\ReviewWithBookResource;
use App\Models\Review;
use App\Services\BookReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookReviewController extends Controller
{
    protected BookReviewService $book_review_service;

    public function __construct(BookReviewService $book_review_service)
    {
        $this->book_review_service = $book_review_service;
    }

    /**
     * 社内の口コミ一覧を取得
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function reviewList(Request $request): JsonResponse
    {
        $filters = $request->only(['book_name', 'book_genre_id', 'user_id']);
        $reviews = $this->book_review_service->getReviews($filters);

        return response()->json(ReviewWithBookResource::collection($reviews), 200);
    }

    /**
     * 口コミを投稿
     *
     * @param CreateReviewRequest $request
     * @return JsonResponse
     */
    public function createReview(CreateReviewRequest $request): JsonResponse
    {
        $review = $this->book_review_service->createReview($request);

        return response()->json(new ReviewWithBookResource($review), 201);
    }

    /**
     * 口コミ詳細を取得
     *
     * @param Review $review
     * @return JsonResponse
     */
    public function reviewItem(Review $review): JsonResponse
    {
        return response()->json(new ReviewWithBookResource($review), 200);
    }

    /**
     * 口コミを編集
     *
     * @param UpdateReviewRequest $request
     * @param Review $review
     * @return JsonResponse
     */
    public function updateReview(UpdateReviewRequest $request, Review $review): JsonResponse
    {
        $this->book_review_service->updateReview($review, $request);

        return response()->json(new ReviewResource($review), 200);
    }

    /**
     * 口コミを削除
     *
     * @param Review $review
     * @return JsonResponse
     */
    public function deleteReview(Review $review): JsonResponse
    {
        $this->book_review_service->deleteReview($review);

        return response()->json(['message' => 'Review deleted'], 204);
    }
}
