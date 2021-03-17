<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Http\Responses\SuccessResponse;
use App\Services\Braintree\BraintreeService;
use App\Http\Requests\Payments\StorePaymentRequest;

class PaymentController extends Controller
{
    /**
     * Braintree Service.
     *
     * @var BraintreeService
     */
    protected $braintreeService;

    /**
     * PaymentController constructor.
     *
     * @param BraintreeService $braintreeService
     *
     * @return void
     */
    public function __construct(BraintreeService $braintreeService)
    {
        $this->braintreeService = $braintreeService;
    }

    /**
     * tore method to setup a Braintree payment.
     *
     * @param StorePaymentRequest $request
     *
     * @return SuccessResponse
     *
     * @throws \Braintree\Exception
     */
    public function store(StorePaymentRequest $request): SuccessResponse
    {
        $braintree_id   = $this->braintreeService->getUserBraintreeId($request);
        $newTransaction = $this->braintreeService->oneOffPayment($request, $braintree_id);
        $this->braintreeService->storeBraintreeUserDetails($newTransaction);

        return new SuccessResponse('Payment Successful!');
    }

    public function show(int $id)
    {
        return response()->json([
            $this->braintreeService->getUserFromBraintreeServerByBraintreeId($id)
        ]);
    }
}
