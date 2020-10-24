<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Services\Braintree\BraintreeTransactionsService;
use App\Http\Requests\Payments\StorePaymentRequest;
use App\Http\Resources\Braintree\BraintreeTransactionResource;

class PaymentController extends Controller
{
    /**
     * Transactions Service.
     *
     * @var BraintreeTransactionsService
     */
    protected $transactionsService;

    /**
     * PaymentController constructor.
     *
     * @param BraintreeTransactionsService $transactionsService
     *
     * @return void
     */
    public function __construct(BraintreeTransactionsService $transactionsService)
    {
        $this->transactionsService = $transactionsService;
    }

    /**
     * BraintreeTransactionsService store method to setup a Braintree payment.
     *
     * @param StorePaymentRequest $request
     *
     * @return BraintreeTransactionResource
     */
    public function store(StorePaymentRequest $request): BraintreeTransactionResource
    {
        $gatewayResult = $this->transactionsService->gatewayTransaction($request);

        $transactionDetails = $this->transactionsService
            ->storeBraintreeUserDetails($gatewayResult->transaction);

        return new BraintreeTransactionResource($transactionDetails);
    }
}
