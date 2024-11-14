<?php

namespace App\Http\Requests\BookPurchaseRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ConfirmPurchaseRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'request_ids' => 'required|array',
            'ids.*' => 'integer|exists:book_purchase_requests,id',  // 各idはbook_purchase_requestsテーブルに存在する
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'code' => 500,
            'error' => $validator->errors(),
        ], 500));
    }
}
