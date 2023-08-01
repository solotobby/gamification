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
                // Handle success event here
                $VirtualAccount = VirtualAccount::create(['user_id' => auth()->user()->id, 'channel' => 'Webhook-Success']);
                break;
            case 'customeridentification.failed':
                $VirtualAccount = VirtualAccount::create(['user_id' => auth()->user()->id, 'channel' => 'Webhook-failed']);
                // Handle failed event here
                break;
            default:
                // Handle unrecognized event here (optional)
                break;
        }

        return response()->json(['status' => 'success']);

    }
}
