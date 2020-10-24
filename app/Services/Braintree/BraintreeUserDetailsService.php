<?php

namespace App\Services\Braintree;

use Illuminate\Http\Request;
use App\BraintreeUserDetails;

class BraintreeUserDetailsService
{
    /**
     * Braintree Gateway.
     *
     * @return \Braintree\Gateway
     */
    public function gateway(): \Braintree\Gateway
    {
        return new \Braintree\Gateway([
            'environment' => config('services.braintree.environment'),
            'merchantId'  => config('services.braintree.merchantId'),
            'publicKey'   => config('services.braintree.publicKey'),
            'privateKey'  => config('services.braintree.privateKey')
        ]);
    }

    /**
     * Braintree Transaction Gateway.
     *
     * @param Request $request
     *
     * @return \Braintree\Result\Error|\Braintree\Result\Successful
     */
    public function gatewayTransaction(Request $request)
    {
        $gateway = $this->gateway();

        return $gateway->transaction()->sale([
            'paymentMethodNonce' => $request->get('nonce'),
            'amount'             => $request->get('amount'),
            'options'            => [ 'submitForSettlement' => true ]
        ]);
    }

    /**
     * Create a Braintree User Details.
     *
     * @param Object $transaction
     *
     * @return BraintreeUserDetails
     */
    public function storeBraintreeUserDetails(Object $transaction): BraintreeUserDetails
    {
        $braintree = new BraintreeUserDetails;

        $braintree->user_id          = auth()->user()->id;
        $braintree->transaction_id   = $transaction->id;
        $braintree->card_type        = $transaction->creditCardDetails->cardType;
        $braintree->last4            = $transaction->creditCardDetails->last4;
        $braintree->expiration_month = $transaction->creditCardDetails->expirationMonth;
        $braintree->expiration_year  = $transaction->creditCardDetails->expirationYear;

        $braintree->save();

        return $braintree;
    }
}
