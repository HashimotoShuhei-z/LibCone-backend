<?php

namespace App\Http\Controllers;

use App\Http\Requests\Gift\SendGiftRequest;
use App\Http\Resources\Gift\GiftLogResource;
use App\Services\GiftService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class GiftController extends Controller
{
    protected GiftService $giftService;

    public function __construct(GiftService $giftService)
    {
        $this->giftService = $giftService;
    }

    public function sendGift(SendGiftRequest $request): JsonResponse
    {
        // リクエストのバリデーション済みデータをサービス層にそのまま渡す
        $giftLog = $this->giftService->createGift($request);

        return response()->json(new GiftLogResource($giftLog), Response::HTTP_CREATED);
    }
}
