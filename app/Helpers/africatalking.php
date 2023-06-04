<?php

 namespace App\Helpers;

 use AfricasTalking\SDK\AfricasTalking;

 class AfricaTalkingHandlers{

     public static  function  sendairtime()
     {
        return 'working';
        //  $username = "sandbox";
        //  $apiKey = env('AFRICA_TALKING_SANDBOX');

        //  $AT = new AfricasTalking($username, $apiKey);

        //  $airtime = $AT->airtime();

        //  // Use the service
        //  $recipients = [[
        //      "phoneNumber"  => "+2348137331282",
        //      "currencyCode" => "NGN",
        //      "amount"       => 100
        //  ]];

        //  try {
        //      // That's it, hit send and we'll take care of the rest
        //      $results = $airtime->send([
        //          "recipients" => $recipients
        //      ]);


        //  } catch(\Exception $e) {
        //      echo "Error: ".$e->getMessage();
        //  }
        //  return json_decode($results->getBody()->getContents(), true);
     }
 }
