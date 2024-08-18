<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private $paymentService;
    private $currency = "PHP";
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    public function createPayment(Request $request)
    {
        $amount = $request->amount;
        $source = $this->paymentService->createPaymentIntent($amount, $this->currency);
        return response()->json([
            'id' => $source['data']
        ]);
    }
    public function attachPayment(Request $request)
    {
        $id = $request->id;
        $paymentAttach = $this->paymentService->attachPaymentMethod($id);
        dd($paymentAttach);
    }
}
