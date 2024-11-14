<?php

namespace App\Services;

use App\Http\Requests\Gift\SendGiftRequest;
use App\Models\GiftLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class GiftService
{
    public function createGift(SendGiftRequest $request): GiftLog
    {
        return DB::transaction(function () use ($request) {

            $sender = User::findOrFail(Auth::id());
            $receiver = User::findOrFail($request->pointReceivedUserId);

            if ($receiver->id === $sender->id) {
                throw new Exception('You cannot send a gift to yourself.');
            }

            if ($sender->special_point < $request->point) {
                throw new Exception('Insufficient points');
            }

            // ポイントの増減
            $sender->special_point -= $request->point;
            $receiver->special_point += $request->point;

            $sender->save();
            $receiver->save();

            // gift_logs に挿入
            return GiftLog::create([
                'point_send_user_id' => $sender->id,
                'point_received_user_id' => $receiver->id,
                'point' => $request->point,
            ]);
        });
    }
}
