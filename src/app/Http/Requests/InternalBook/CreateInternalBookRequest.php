<?php

namespace App\Http\Requests\InternalBook;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateInternalBookRequest extends FormRequest
{
    /**
     * @return array<string, string>
     */
    public function rules()
    {
        return [
            'isbn' => 'required|string',
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
