<?php

namespace App\Http\Resources\Braintree;

use Illuminate\Http\Resources\Json\JsonResource;

class BraintreeTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'transaction_id'   => $this->transaction_id,
            'card_type'        => $this->card_type,
            'last4'            => $this->last4,
            'expiration_month' => $this->expiration_month,
            'expiration_year'  => $this->expiration_year,
        ];
    }
}
