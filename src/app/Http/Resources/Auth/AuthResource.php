<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     *
     * @return array<string,string>
     */
    public function toArray($request): array
    {
        return [
            'token' => $this->resource['token'],
        ];
    }
}
