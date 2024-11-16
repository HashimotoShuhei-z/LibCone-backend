<?php

namespace App\Http\Controllers;

use App\Http\Resources\User\MypageResource;
use App\Http\Resources\User\ReadingLogResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * マイページデータを取得
     *
     * @return JsonResponse
     */
    public function myPage(): JsonResponse
    {
        $user = Auth::user();

        return response()->json(new MypageResource($user));
    }

    /**
     * 特定のユーザーの読書履歴を取得
     *
     * @param int $user_id
     * @return JsonResponse
     */
    public function readingLog(int $user_id): JsonResponse
    {
        $user = User::with(['reviews.book', 'reviews.book.bookGenres', 'reviews.book.author'])->findOrFail($user_id);

        return response()->json(ReadingLogResource::collection($user->reviews));
    }
}
