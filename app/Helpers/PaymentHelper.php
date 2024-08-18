<?php

namespace App\Helpers;

class PaymentHelper
{
    public static function getEndPoint($type)
    {
        $url = [
            'intent' => '/payment_intents',
            'attach' => "/attach",
            'link' => "/links",
        ];
        switch ($type) {
            case 'paymentIntent':
               $endpoint = $url['intent'];
                break;
            default:
                # code...
                break;
        }

        return $endpoint;
    }
}
