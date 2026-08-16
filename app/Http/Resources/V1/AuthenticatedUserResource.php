<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthenticatedUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            "id" => (int)$this->id,
            'name' => $this->name,
            'email' => $this->email,
            'verified_at' => $this->verified_at,
            'mobile_country_code' => $this->mobile_country_code,
            'mobile' => $this->mobile,
            'token' => $this->token  ?? str_replace("Bearer ", "",  request()->header("authorization")),
        ];
    }
}
