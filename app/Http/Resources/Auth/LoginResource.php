<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'token_type' => env('AUTH_TOKEN_TYPE', 'Bearer'),
            'access_token' => $this->generateAuthToken(),
            'user' => [
                'email' => $this->email,
                'name' => $this->name
            ]
        ];
    }
}
