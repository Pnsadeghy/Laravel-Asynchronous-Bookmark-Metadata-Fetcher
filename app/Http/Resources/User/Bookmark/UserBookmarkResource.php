<?php

namespace App\Http\Resources\User\Bookmark;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserBookmarkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "url" => $this->url,
            "title" => $this->title,
            "description" => $this->description,
            "created_at" => $this->created_at,
            "deleted" => $this->trashed()
        ];
    }
}
