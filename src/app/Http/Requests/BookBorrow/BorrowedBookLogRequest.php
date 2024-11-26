<?php

namespace App\Http\Requests\BookBorrow;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BorrowedBookLogRequest extends FormRequest
{
    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'book_name' => 'string|nullable',
            'user_name' => 'string|nullable',
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
