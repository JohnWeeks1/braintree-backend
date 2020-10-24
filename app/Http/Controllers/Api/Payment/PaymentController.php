<?php

namespace App\Http\Controllers\Api\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Responses\SuccessResponse;

class PaymentController extends Controller
{
    /**
     * Payment store method to setup a Braintree payment.
     *
     * @param Request $request
     *
     * @return SuccessResponse
     */
    public function store(Request $request): SuccessResponse
    {
//        $user = $request->user();
//        $paymentMethod = $request->payment_method;
//        $plan = Plan::findOrFail(env('DEFAULT_PLAN_ID'));
//
//        $user->newSubscription(
//            'default',
//            $plan->stripe_id
//        )->create($paymentMethod);
//
//        $user->save();

        return new SuccessResponse('Sick');
    }
}
