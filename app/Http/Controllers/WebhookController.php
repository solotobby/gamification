<?php

namespace App\Http\Controllers;

use App\Models\VirtualAccount;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle(Request $request){
        $event = $request->event;

        switch ($event) {
            case 'customeridentification.success':
               
                VirtualAccount::create(['user_id' =>1, 'channel' => 'Webhook-Success']);
                break;
            case 'customeridentification.failed':
                VirtualAccount::create(['user_id' => 1, 'channel' => 'Webhook-failed']);
              
                break;
            case 'dedicatedaccount.assign.failed':
                VirtualAccount::create(['user_id' => 1, 'channel' => 'Webhook-assign-failed']);
               
                break;
            case 'dedicatedaccount.assign.success':
                VirtualAccount::create(['user_id' => 1, 'channel' => 'Webhook-assign-success']);
               
                break;
            default:
            VirtualAccount::create(['user_id' => 1, 'channel' => 'nothing']);
                break;
        }

        return response()->json(['status' => 'success']);

    }
}
