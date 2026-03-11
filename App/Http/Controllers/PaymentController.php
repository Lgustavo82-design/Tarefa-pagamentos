<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentService;

class PaymentController
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function purchase(Request $request)
    {
        $data = $request->validate([
            'amount'     => 'required|integer',
            'name'       => 'required|string',
            'email'      => 'required|email',
            'cardNumber' => 'required|string|size:16',
            'cvv'        => 'required|string|size:3',
        ]);

        try {
            $result = $this->paymentService->process($data);

            return response()->json([
                'status' => 'success',
                'data' => $result
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
