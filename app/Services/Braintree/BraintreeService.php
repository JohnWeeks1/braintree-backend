<?php

namespace App\Services\Braintree;

use App\User;
use Illuminate\Http\Request;

class BraintreeService
{
    /**
     * Braintree Gateway.
     *
     * @return \Braintree\Gateway
     */
    public function gatewayInit(): \Braintree\Gateway
    {
        return new \Braintree\Gateway([
            'environment' => config('services.braintree.environment'),
            'merchantId'  => config('services.braintree.merchantId'),
            'publicKey'   => config('services.braintree.publicKey'),
            'privateKey'  => config('services.braintree.privateKey')
        ]);
    }

    /**
     * Braintree create customer.
     *
     * @param Request $request
     *
     * @return \Braintree\Result\Error|\Braintree\Result\Successful
     */
    public function createCustomer(Request $request)
    {
        $gateway = $this->gatewayInit();

        $newCustomer = $gateway->customer()->create([
            'firstName'          => auth()->user()->first_name,
            'lastName'           => auth()->user()->last_name,
            'email'              => auth()->user()->email,
            'paymentMethodNonce' => $request->get('nonce'),
        ]);

        if($newCustomer->success) {
            return $newCustomer->customer;
        }
    }

    /**
     * Create one off payment.
     *
     * @param Request $request
     * @param int $braintreeId
     *
     * @return \Braintree\Result\Error|\Braintree\Result\Successful
     */
    public function oneOffPayment(Request $request, int $braintreeId)
    {
        $gateway = $this->gatewayInit();

        $transaction = $gateway->transaction()->sale([
            'customerId' => $braintreeId,
            'amount'     => $request->get('amount'),
            'options'    => [ 'submitForSettlement' => true ]
        ]);

        if ($transaction->success) {
            return $transaction;
        }
    }

    /**
     * Store Braintree user details to the user table.
     *
     * @param Object $newTransaction
     *
     * @return void
     */
    public function storeBraintreeUserDetails(Object $newTransaction): void
    {
        $user        = auth()->user();
        $transaction = $newTransaction->transaction;

        $user->braintree_id   = $transaction->customer['id'];
        $user->card_type      = $transaction->creditCardDetails->cardType;
        $user->card_last_four = $transaction->creditCardDetails->last4;

        $user->save();
    }

    /**
     * Does the user have a braintree_id.
     *
     * @param Request $request
     *
     * @return string
     */
    public function getUserBraintreeId(Request $request): string
    {
        $user = auth()->user();

        if (is_null($user->braintree_id)) {
            $customer     = $this->createCustomer($request);
            $braintree_id = $customer->id;
        } else {
            $braintree_id = $user->braintree_id;
        }

        return $braintree_id;
    }
}
