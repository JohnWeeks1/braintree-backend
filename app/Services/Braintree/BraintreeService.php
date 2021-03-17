<?php

namespace App\Services\Braintree;

use Braintree\Exception;
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
     * @return mixed|string|null
     */
    public function createCustomer(Request $request)
    {
        try {

            $gateway = $this->gatewayInit();

            return $gateway->customer()->create([
                'firstName'          => auth()->user()->first_name,
                'lastName'           => auth()->user()->last_name,
                'email'              => auth()->user()->email,
                'paymentMethodNonce' => $request->get('nonce'),
            ]);

        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    /**
     * Create one off payment.
     *
     * @param Request $request
     * @param int $braintreeId
     *
     * @return \Braintree\Result\Error|\Braintree\Result\Successful|string
     */
    public function oneOffPayment(Request $request, int $braintreeId)
    {
        try {

            $gateway = $this->gatewayInit();

            return $gateway->transaction()->sale([
                'customerId' => $braintreeId,
                'amount'     => $request->get('amount'),
                // 'options'    => [ 'submitForSettlement' => true ]
            ]);

        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    /**
     * Store Braintree user details to the user table.
     *
     * @param Object $newTransaction
     *
     * @return string
     */
    public function storeBraintreeUserDetails(Object $newTransaction)
    {
        try {

            $user        = auth()->user();
            $transaction = $newTransaction->transaction;

            $user->braintree_id   = $transaction->customer['id'];
            $user->card_type      = $transaction->creditCardDetails->cardType;
            $user->card_last_four = $transaction->creditCardDetails->last4;

            $user->save();

        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    /**
     * Does the user have a braintree_id.
     *
     * @param Request $request
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function getUserBraintreeId(Request $request)
    {
        $user = auth()->user();

        if (is_null($user->braintree_id)) {
            $customer     = $this->createCustomer($request);
            $braintree_id = $customer->customer->id;
        } else {
            $braintree_id = $user->braintree_id;
        }

        if (!is_null($braintree_id)) {

            return $braintree_id;
        }

        throw new Exception('Something went wrong?');
    }

    public function getUserFromBraintreeServerByBraintreeId(int $id)
    {
        $gateway = $this->gatewayInit();

        $gateway->paymentMethod()->update(
            'bvw3xtw',
            [
              'options' => [
                'makeDefault' => true
              ]
            ]
          );

          sleep(2);

          $customer = $gateway->customer()->find($id);

        return $customer->defaultPaymentMethod();
    }
}
