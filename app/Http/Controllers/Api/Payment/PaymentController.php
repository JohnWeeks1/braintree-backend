<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\StorePaymentRequest;
use App\Services\Braintree\BraintreeUserDetailsService;
use App\Http\Resources\Braintree\BraintreeUserDetailsResource;

class PaymentController extends Controller
{
    /**
     * Braintree User Details Service.
     *
     * @var BraintreeUserDetailsService
     */
    protected $braintreeUserDetailsService;

    /**
     * PaymentController constructor.
     *
     * @param BraintreeUserDetailsService $braintreeUserDetailsService
     *
     * @return void
     */
    public function __construct(BraintreeUserDetailsService $braintreeUserDetailsService)
    {
        $this->braintreeUserDetailsService = $braintreeUserDetailsService;
    }

    /**
     * BraintreeUserDetails store method to setup a Braintree payment.
     *
     * @param StorePaymentRequest $request
     *
     * @return BraintreeUserDetailsResource
     */
    public function store(StorePaymentRequest $request): BraintreeUserDetailsResource
    {
        $gatewayResult = $this->braintreeUserDetailsService->gatewayTransaction($request);

        $userTransactionDetails = $this->braintreeUserDetailsService
            ->storeBraintreeUserDetails($gatewayResult->transaction);

        return new BraintreeUserDetailsResource($userTransactionDetails);
    }
}
