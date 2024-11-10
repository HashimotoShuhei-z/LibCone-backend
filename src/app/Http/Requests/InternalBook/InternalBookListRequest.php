<?php

namespace App\Http\Requests\InternalBook;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class InternalBookListRequest extends FormRequest
{
    /**
     * @return array<string, string|null>
     */
    public function rules(): array
    {
        return [
            'book_name' => 'string|nullable',
            'book_genre_id' => 'integer|nullable'
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