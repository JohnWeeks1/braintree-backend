<?php


namespace App\Http\Responses;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Responsable;

class SuccessResponse implements Responsable
{
    /**
     * Message instance.
     *
     * @var $message
     */
    protected $message;

    /**
     * SuccessResponse constructor.
     *
     * @param $message
     */
    public function __construct(string $message)
    {
        $this->message = $message ?? 'OK';
    }

    /**
     * Success Response.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'message' => $this->message
        ], 200);
    }
}
