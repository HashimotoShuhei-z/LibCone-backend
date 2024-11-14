<?php

namespace App\Http\Requests\BookPurchaseRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class bookPurchaseReqListRequest extends FormRequest
{
    /**
     * @return array<string, string|null>
     */
    public function rules(): array
    {
        return [
            'book_name' => 'string|nullable',
            'position_id' => 'integer|nullable|exists:positions,id'
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
