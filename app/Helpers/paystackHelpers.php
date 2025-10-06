<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use App\Models\LoginPoints;
use App\Models\PaymentTransaction;
use Illuminate\Support\Env;
use App\Models\Statistics;
use App\Models\User;
use App\Models\UserLocation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Stevebauman\Location\Facades\Location;
use Flutterwave\Payload;
use Flutterwave\Service\VirtualCard;
use Flutterwave\Util\Currency;
use Illuminate\Support\Facades\Log;

class PaystackHelpers
{

    public static function countryList()
    {

        $url = 'https://api.paystack.co/country';
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY')
        ])->get($url)->throw();

        return json_decode($res->getBody()->getContents(), true)['data'];
    }
    public static function bankList()
    {

        // country=nigeria
        $url = 'https://api.paystack.co/bank?country=nigeria';
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY')
        ])->get($url)->throw();

        return $bankList = json_decode($res->getBody()->getContents(), true)['data'];

        // foreach($bankList as $bank){
        //     $group = $bank['type'];
        //     $groupedBank[$group][] = $bank;
        //  }
        //  return $groupedBank;
    }

    public static function resolveBankName($account_number, $bank_code)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY')
        ])->get('https://api.paystack.co/bank/resolve?account_number=' . $account_number . '&bank_code=' . $bank_code);
        return json_decode($res->getBody()->getContents(), true);
    }

    public static function recipientCode($name, $account_number, $bank_code)
    {

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY')
        ])->post('https://api.paystack.co/transferrecipient', [
            "type" => "nuban",
            "name" => $name,
            "account_number" => $account_number,
            "bank_code" => $bank_code,
            "currency" => "NGN"
        ]);

        return json_decode($res->getBody()->getContents(), true);
    }

    public static function transferFund($amount, $recipient, $reason)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY')
        ])->post('https://api.paystack.co/transfer', [
            "source" => "balance",
            "amount" => $amount,
            "recipient" => $recipient,
            "reason" => $reason
        ]);

        return json_decode($res->getBody()->getContents(), true);
    }

    public static function bulkFundTransfer($transfers)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY')
        ])->post('https://api.paystack.co/transfer/bulk', [
            "currency" => "NGN",
            "source" => "balance",
            "transfers" => $transfers
        ]);

        return json_decode($res->getBody()->getContents(), true);
    }

    public static function initiateTrasaction($ref, $amount, $redirect_url)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY')
        ])->post('https://api.paystack.co/transaction/initialize', [
            'email' => auth()->user()->email,
            'amount' => $amount * 100,
            'channels' => ['card'],
            'currency' => 'NGN',
            'reference' => $ref,
            'callback_url' => url($redirect_url),
            "metadata" => [
                "user_id" => auth()->user()->id,
            ]
        ]);
        return $res['data']['authorization_url'];
    }

    public static function verifyTransaction($ref)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY')
        ])->get('https://api.paystack.co/transaction/verify/' . $ref)->throw();

        return json_decode($res->getBody()->getContents(), true);
    }

    public static function virtualAccount($data)
    {

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY')
        ])->post('https://api.paystack.co/dedicated_account', $data);

        return json_decode($res->getBody()->getContents(), true);
    }


    public static function createCustomer($data)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY')
        ])->post('https://api.paystack.co/customer', $data);

        return json_decode($res->getBody()->getContents(), true);
    }

    public static function fetchCustomer($email)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY')
        ])->get('https://api.paystack.co/customer/' . $email);

        return json_decode($res->getBody()->getContents(), true);
    }

    public static function updateCustomer($email, $payload)
    {

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY')
        ])->put('https://api.paystack.co/customer/' . $email, $payload);

        return json_decode($res->getBody()->getContents(), true);
    }


    //fluterwave apis
    public static function listFlutterwaveTransaction()
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('FL_SECRET_KEY')
        ])->get('https://api.flutterwave.com/v3/transactions')->throw();

        return json_decode($res->getBody()->getContents(), true);
    }

    public static function initiateFlutterwavePayment($payload)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('FL_SECRET_KEY')
        ])->post('hhttps://api.flutterwave.com/v3/payments', $payload)->throw();

        return json_decode($res->getBody()->getContents(), true);
    }

    ///system functions

    public static function getLocation()
    {
        // if(env('APP_ENV') == 'local_test'){
        //     $ip = '48.188.144.248';
        // }else{

        // }
        $ip = request()->ip();
        $location = Location::get($ip);
        return $location->countryName;
    }
    public static function userLocation($type)
    {
        if (env('APP_ENV') == 'local_test') {
            $ip = '48.188.144.248';
        } else {
            $ip = request()->ip();
        }

        if ($type == 'Login') {
            $check = UserLocation::where('user_id', auth()->user()->id)->whereDate('created_at', today())->first();

            if (!$check) {
                $location = Location::get($ip);
                UserLocation::create([
                    'user_id' => auth()->user()->id,
                    'activity' => $type,
                    'ip' => $ip,
                    'countryName' => $location->countryName,
                    'countryCode' => $location->countryCode,
                    'regionName' => $location->regionName,
                    'regionCode' => $location->regionCode,
                    'cityName' => $location->cityName,
                    'zipCode' => $location->zipCode,
                    'areaCode' => $location->areaCode,
                    'timezone' => $location->timezone
                ]);
            }
        } else {
            $location = Location::get($ip);
            UserLocation::create([
                'user_id' => auth()->user()->id,
                'activity' => $type,
                'ip' => $ip,
                'countryName' => $location->countryName,
                'countryCode' => $location->countryCode,
                'regionName' => $location->regionName,
                'regionCode' => $location->regionCode,
                'cityName' => $location->cityName,
                'zipCode' => $location->zipCode,
                'areaCode' => $location->areaCode,
                'timezone' => $location->timezone
            ]);
        }
    }

    public static function paymentTrasanction($userId, $campaign_id, $ref, $amount, $status, $type, $description, $tx_type, $user_type)
    {
        return PaymentTransaction::create([
            'user_id' => $userId,
            'campaign_id' => $campaign_id,
            'reference' => $ref,
            'amount' => $amount,
            'status' => $status,
            'currency' => auth()->user()->wallet->base_currency == 'Naira' ? 'NGN' : 'USD',
            'channel' => auth()->user()->wallet->base_currency == 'Naira' ? 'paystack' : 'paypal',
            'type' => $type,
            'description' => $description,
            'tx_type' => $tx_type,
            'user_type' => $user_type
        ]);
    }

    public static function paymentUpdate($ref, $status)
    {
        $fetchPaymentTransaction = PaymentTransaction::where('reference', $ref)->first();
        $fetchPaymentTransaction->status = $status;
        $fetchPaymentTransaction->save();
        return $fetchPaymentTransaction;
    }

    public static function sendNotificaion($number, $message)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://api.ng.termii.com/api/sms/send', [
            "to" => $number,
            "from" => "FREEBYZ",
            "sms" => $message,
            "type" => "plain",
            "channel" => "generic",
            "api_key" => env('TERMI_KEY')
        ]);

        return json_decode($res->getBody()->getContents(), true);
    }


    ///////////////////capital sage
    public static function access_token()
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://sagecloud.ng/api/v2/merchant/authorization', [
            "email" => "farohunbi.st@gmail.com",
            "password" => "Solomon001"
        ]);
        return json_decode($res->getBody()->getContents(), true)['data']['token']['access_token'];
    }

    public static function loadNetworkData($access_token, $network)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $access_token
        ])->get('https://sagecloud.ng/api/v2/internet/data/lookup?provider=' . $network)->throw();
        return json_decode($res->getBody()->getContents(), true)['data'];
    }

    public static function purchaseData($access_token, $code, $network_type, $provider, $phone, $ref)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $access_token
        ])->post('https://sagecloud.ng/api/v2/internet/data', [
            "reference" => $ref,
            "type" => $network_type,
            "code" => $code,
            "network" => $provider,
            "phone" => $phone,
            "provider" => $provider
        ])->throw();
        return json_decode($res->getBody()->getContents(), true);
    }

    public static function buyAirtime($payload, $access_token)
    {
        $res =  Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $access_token
        ])->post('https://sagecloud.ng/api/v2/epin/purchase', $payload)->throw();
        return json_decode($res->getBody()->getContents(), true);
    }

    /////////////////////////// statistics
    public static function sendBulkSMS($number, $message)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://v3.api.termii.com/api/sms/send/bulk', [
            // "to"=> $number,
            "to" => $number,
            "from" => "Freebyz",
            "sms" => $message,
            "type" => "plain",
            "channel" => "generic",
            "api_key" =>config('services.env.termii')
        ]);
        Log::info($res->json());

        return $res->json();
    }

    public static function formatAndArrange(array $phones): array
    {
        $formatted = [];

        foreach ($phones as $phone) {
            if (empty($phone)) {
                continue;
            }

            $clean = preg_replace('/\D/', '', $phone);
            $formattedPhone = Str::startsWith($clean, '234')
                ? '+' . $clean
                : '+234' . ltrim($clean, '0');

            $formatted[] = $formattedPhone;
        }

        return array_values(array_unique($formatted));
    }
    public static function flutterwaveCreateCard()
    {

        $payload = new Payload();
        $service = new VirtualCard();
        $payload->set("first_name", "PHP");
        $payload->set("last_name", "SDK");
        $payload->set("date_of_birth", "1994-03-01");
        $payload->set("title", "Mr");
        $payload->set("gender", "M"); //M or F
        $payload->set("email", "developers@flutterwavego.com");
        $payload->set("currency", Currency::NGN);
        $payload->set("amount", "5000");
        // $payload->set("debit_currency", Currency::NGN);
        $payload->set("phone", "+234505394568");
        $payload->set("billing_name", "Abraham Ola");
        $payload->set("firstname", "Abraham");
        $response = $service->create($payload);
        return $response;
        // print_r($response);
    }
}
