<?php

namespace App\Http\Resources\User;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'first_name'     => $this->first_name,
            'last_name'      => $this->last_name,
            'email'          => $this->email,
            'braintree_id'   => $this->braintree_id,
            'card_type'      => $this->card_type,
            'card_last_four' => $this->card_last_four,
            'cookie_expire'  => Carbon::now()->addMinutes(env('SESSION_LIFETIME'))->format('Y-m-d H:i:s')
        ];
    }
}
