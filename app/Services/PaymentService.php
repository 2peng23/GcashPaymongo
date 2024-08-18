<?php

namespace App\Services;

use App\Helpers\PaymentHelper;
use Illuminate\Support\Facades\Http;
use Paymongo\PaymongoClient;

class PaymentService
{
    protected $client;

    public function __construct()
    {
        $this->client = new PaymongoClient([
            'secret_key' => config('paymongo.key.secret'),
            'public_key' => config('paymongo.key.public'),
        ]);
    }

    public function createPaymentIntent($amount, $currency = 'PHP')
    {
        $baseUrl = env('PAYMONGO_BASE_URL');
        $endpoint = PaymentHelper::getEndPoint('paymentIntent');
        $url = $baseUrl . $endpoint;
        // Make sure to properly base64 encode the secret key
        $secretKey = config('paymongo.key.secret');
        $encodedKey = base64_encode($secretKey);

        $data = [
            'data' => [
                'attributes' => [
                    'amount' => $amount * 100,
                    'payment_method_allowed' => ['gcash'],
                    'currency' => $currency
                ]
            ]
        ];
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'authorization' => 'Basic ' . $encodedKey,
            'content-type' => 'application/json',
        ])->post($url, $data);
        return $response->json();
    }

    public function attachPaymentMethod($paymentIntentId, $paymentMethodId = 'pm_2wQboHysLCTaotTBk1Rk1cDh')
    {
        $baseUrl = env('PAYMONGO_BASE_URL');
        $endpoint = PaymentHelper::getEndPoint('paymentIntent');
        $url = $baseUrl . $endpoint. '/' . $paymentIntentId . '/attach';
        // Make sure to properly base64 encode the secret key
        $secretKey = config('paymongo.key.secret');
        $encodedKey = base64_encode($secretKey);

        $data = [
            'data' => [
                'attributes' => [
                    'payment_method' => $paymentMethodId,
                    'return_url' => "https:www.google.com"
                ]
            ]
        ];
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'authorization' => 'Basic ' . $encodedKey,
            'content-type' => 'application/json',
        ])->post($url, $data);

        return $response->json();
    }

    public function createSource($amount, $currency = 'PHP', $type = 'gcash')
    {
        return $this->client->sources->create([
            'amount' => $amount * 100, // Amount should be in centavos
            'currency' => $currency,
            'type' => $type,
            'redirect' => [
                'success' => route('payment.success'),
                'failed' => route('payment.failed'),
            ],
        ]);
    }
}
