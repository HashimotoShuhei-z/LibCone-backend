<?php

namespace App\Http\Requests\BookBorrow;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BorrowBookRequest extends FormRequest
{
    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'startDate' => 'required|date',
            'endDate' => 'required|date',
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
