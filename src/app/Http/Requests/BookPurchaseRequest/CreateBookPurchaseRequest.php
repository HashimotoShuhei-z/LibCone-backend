<?php

namespace App\Http\Requests\BookPurchaseRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateBookPurchaseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:20',
            'itemPrice' => 'required|integer|min:0',
            'itemUrl' => 'required|string|url',
            'userId' => 'required|integer|exists:users,id',
            'purchaseType' => 'required|integer|in:0,1',
            'hopeDeliveryAt' => 'required|date', //TODO: |after:today入れると405エラーが起きる
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
