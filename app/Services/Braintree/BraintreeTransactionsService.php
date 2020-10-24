<?php

namespace App\Services\Braintree;

use App\BraintreeTransaction;
use Illuminate\Http\Request;

class BraintreeTransactionsService
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
     * Braintree BraintreeTransaction Gateway.
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
     * @return BraintreeTransaction
     */
    public function storeBraintreeUserDetails(Object $transaction): BraintreeTransaction
    {
        $newTransaction = new BraintreeTransaction;

        $newTransaction->transaction_id   = $transaction->id;
        $newTransaction->user_id          = auth()->user()->id;
        $newTransaction->card_type        = $transaction->creditCardDetails->cardType;
        $newTransaction->last4            = $transaction->creditCardDetails->last4;
        $newTransaction->expiration_month = $transaction->creditCardDetails->expirationMonth;
        $newTransaction->expiration_year  = $transaction->creditCardDetails->expirationYear;

        $newTransaction->save();

        return $newTransaction;
    }
}
