<?php

namespace App\Http\Controllers;

use App\Mail\GeneralMail;
use App\Mail\UpgradeUser;
use App\Models\MassEmailLog;
use App\Models\PaymentTransaction;
use App\Models\Question;
use App\Models\User;
use App\Models\VirtualAccount;
use App\Models\Wallet;
use App\Models\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {

        $event = $request['event'];

        if ($event == 'charge.success') {
            $amount = $request['data']['amount'] / 100;
            $status = $request['data']['status'];
            $reference = $request['data']['reference'];
            $channel = $request['data']['channel'];
            $currency = $request['data']['currency'];
            $email = $request['data']['customer']['email'];
            $customer_code = $request['data']['customer']['customer_code'];

            $validateTransaction = PaymentTransaction::where('reference', $reference)->first();
            if (!$validateTransaction) {

                $virtualAccount = VirtualAccount::where('customer_id', $customer_code)->first();
                $user = User::where('id', $virtualAccount->user_id)->first();

                // $creditUser = creditWallet($user, 'NGN', $amount);

                $walletCredit =  Wallet::where('user_id', $user->id)->first();
                $walletCredit->balance += $amount;
                $walletCredit->save();

                if ($walletCredit) {

                    // $transaction = transactionProcessor($user, $reference, $amount, 'successful', $currency, $channel, 'transfer_topup', 'Cash transfer from '.$user->name, 'Credit', 'regular');

                    $transaction =  PaymentTransaction::create([
                        'user_id' => $user->id,
                        'campaign_id' => 1,
                        'reference' => $reference,
                        'amount' => $amount,
                        'balance' => walletBalance($user->id),
                        'status' => 'successful',
                        'currency' => 'NGN',
                        'channel' => 'paystack',
                        'type' => 'transfer_topup',
                        'description' => 'Cash transfer from ' . $user->name,
                        'tx_type' => 'Credit',
                        'user_type' => 'regular'
                    ]);

                    if ($transaction) {
                        $subject = 'Wallet Credited';
                        $content = 'Congratulations, your wallet has been credited with ₦' . $amount;
                        //  Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));
                    }

                    //check wallet stat
                    $userBaseCurrency = baseCurrency($user);

                    $currencyParams = currencyParameter($userBaseCurrency);
                    $upgradeAmount = $currencyParams->upgrade_fee;
                    $referral_commission = $currencyParams->referral_commission;

                    if ($user->is_verified == false) {
                        if ($amount >= $upgradeAmount) {

                            $debitWallet = debitWallet($user, 'NGN', $upgradeAmount);

                            if ($debitWallet) {

                                $upgrdate = userNairaUpgrade($user, $upgradeAmount, $referral_commission);

                                if ($upgrdate) {
                                    //  Mail::to($user->email)->send(new UpgradeUser($user));
                                }
                            }
                        } else {

                            $walletCredit =  Wallet::where('user_id', $user->id)->first();
                            if ($walletCredit->balance >= $upgradeAmount) {
                                $debitWallet = debitWallet($user, 'NGN', $upgradeAmount);
                                if ($debitWallet) {

                                    $upgrdate = userNairaUpgrade($user, $upgradeAmount, $referral_commission);

                                    if ($upgrdate) {
                                        //  Mail::to($user->email)->send(new UpgradeUser($user));
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return response()->json(['status' => 'success'], 200);
        } else {
            return response()->json(['status' => 'error'], 500);
        }
    }

    public function korayPayWebhook(Request $request)
    {
        ini_set('serialize_precision', '-1');

        // Verify HMAC signature
        $signature = $request->header('x-korapay-signature');
        $secret = config('services.env.kora_sec');
        $data = $request->input('data');

        $computedSignature = hash_hmac('sha256', json_encode($data, JSON_UNESCAPED_SLASHES), $secret);

        if ($signature !== $computedSignature) {
            Log::warning('Invalid KoraPay webhook signature', [
                'received' => $signature,
                'expected' => $computedSignature,
            ]);
            return response()->json(['status' => 'invalid signature'], 200);
        }

        // Log the webhook before processing
        $webhook = Webhook::create([
            'provider' => 'korapay',
            'event' => $request->input('event'),
            'payload' => $request->all(),
            'status' => 'pending',
        ]);

        try {
            $event = $request->input('event');
            $payload = $data ?? [];

            switch ($event) {
                //  PAY-IN: CARD, BANK, MOBILE, VIRTUAL
                case 'charge.success':
                    $reference = $payload['reference'] ?? null;

                    if (!$reference) {
                        $webhook->update(['status' => 'failed', 'message' => 'No reference in payload']);
                        return response()->json(['status' => 'error', 'message' => 'Missing reference'], 200);
                    }

                    $transaction = PaymentTransaction::where('reference', $reference)->first();

                    // If not found, create transaction for virtual pay-in
                    if (!$transaction) {
                        $userId = $this->getUserFromVirtualAccount($payload);

                        if (!$userId) {
                            $webhook->update([
                                'status' => 'failed',
                                'message' => 'User not found for virtual account',
                            ]);
                            return response()->json(['status' => 'error', 'message' => 'User not found'], 200);
                        }

                        // $fundAmount = $payload['amount'] - $payload['fee'];
                        $transaction = PaymentTransaction::create([
                            'user_id' => $userId,
                            'campaign_id' => '1',
                            'reference' => time(),
                            'amount' => $payload['amount'] ?? 0,
                            'balance' => walletBalance($userId),
                            'status' => 'successful',
                            'currency' => $payload['currency'] ?? 'NGN',
                            'channel' => 'kora',
                            'type' => 'wallet_topup',
                            'description' => 'Wallet Top Up',
                            'tx_type' => 'Credit',
                            'user_type' => 'regular'
                        ]);
                    }

                    $user = User::find($transaction->user_id);

                    if (!$user) {
                        $webhook->update(['status' => 'failed', 'message' => 'Linked user not found']);
                        return response()->json(['status' => 'error', 'message' => 'User not found'], 200);
                    }

                    // Credit wallet
                    creditWallet($user, $transaction->currency, $transaction->amount);
                    $transaction->update([
                        'status' => 'successful',
                        'balance' => walletBalance($user->id)
                    ]);

                    // Mail::to($user->email)->send(new GeneralMail(
                    //     $user,
                    //     'Your wallet has been credited with ₦' . number_format($transaction->amount),
                    //     'Wallet Credited',
                    //     ''
                    // ));

                    $webhook->update(['status' => 'processed', 'message' => 'Pay-in successful']);

                    break;

                // PAYOUT: SUCCESS
                case 'transfer.success':
                    $reference = $payload['reference'] ?? null;
                    $transaction = PaymentTransaction::where('reference', $reference)->first();

                    if (!$transaction) {
                        $webhook->update(['status' => 'failed', 'message' => 'Transfer not found']);
                        return response()->json(['status' => 'error', 'message' => 'Transfer not found'], 200);
                    }

                    $transaction->update(['status' => 'successful']);
                    $webhook->update(['status' => 'processed', 'message' => 'Transfer success']);
                    break;


                // PAYOUT: FAILED
                case 'transfer.failed':
                    $reference = $payload['reference'] ?? null;
                    $transaction = PaymentTransaction::where('reference', $reference)->first();

                    if ($transaction) {
                        $transaction->update(['status' => 'failed']);
                    }

                    $webhook->update(['status' => 'processed', 'message' => 'Transfer failed']);
                    break;


                default:
                    $webhook->update(['status' => 'ignored', 'message' => 'Unhandled event']);
                    break;
            }

            return response()->json(['status' => 'success'], 200);
        } catch (Throwable $e) {
            Log::error('KoraPay webhook error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            $webhook->update(['status' => 'failed', 'message' => $e->getMessage()]);
            return response()->json(['status' => 'error'], 500);
        }
    }

    private function getUserFromVirtualAccount(array $payload)
    {
        $virtualDetails = $payload['virtual_bank_account_details']['virtual_bank_account'] ?? [];

        $bankName = $virtualDetails['bank_name'] ?? null;
        $accountName = $virtualDetails['account_name'] ?? null;
        $accountNumber = $virtualDetails['account_number'] ?? null;
        $accountReference = $virtualDetails['account_reference'] ?? null;

        if (!$accountReference && !$accountNumber) {
            Log::warning('Korapay virtual account data missing', [
                'payload' => $payload,
            ]);
            return null;
        }

        $query = VirtualAccount::query();

        if ($accountReference) {
            $query->where('customer_id', $accountReference);
        } elseif ($accountNumber) {
            $query->where('account_number', $accountNumber);
        } else {
            $query->where('account_name', $accountName);
        }

        $virtualAccount = $query->first();

        if (!$virtualAccount) {
            Log::warning('No matching virtual account found', [
                'account_reference' => $accountReference,
                'account_number' => $accountNumber,
                'account_name' => $accountName,
                'bank_name' => $bankName,
            ]);
            return null;
        }

        return $virtualAccount->user_id;
    }


    // public function korayPayWebhook(Request $request){
    //      Question::create(['content' => $request]);

    //     $event = $request['event'];

    //     if($event == 'charge.success'){

    //         // $amount = $request['data']['amount'];
    //         // $status = $request['data']['transaction_status'];
    //         $korapayReference = $request['data']['reference']; //from koraypay
    //         // $paymentReference = $request['data']['meta']['payment_reference']; //depreciated
    //         $currency = $request['data']['currency'];

    //         $validateTransaction = PaymentTransaction::where('reference', $korapayReference)->first();
    //         if($validateTransaction){

    //             $validateTransaction->reference;
    //             //fetch user
    //             $user = User::where('id', $validateTransaction->user_id)->first();

    //             $creditUser = []; //creditWallet($user, 'NGN', $validateTransaction->amount);
    //             // if($creditUser){

    //             //     $validateTransaction->status = 'successful';
    //             //     $validateTransaction->save();

    //             //     $subject = 'Wallet Credited';
    //             //     $content = 'Congratulations, your wallet has been credited with ₦'.$validateTransaction->amount;
    //             //     Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));

    //             //      return response()->json(['status' => 'success'], 200);

    //             // }

    //         }else{
    //             return response()->json(['status' => 'error', 'message' => 'Transaction not successful'], 501);
    //         }


    //     }


    // }

    public function zeptoWebhook(Request $request)
    {
        // Validate signature first
        // $signature = $request->header('producer-signature');
        // $authKey = config('services.zeptomail.webhook_auth_key');
        // $payload = $request->getContent();

        // if (!$this->isValidZeptoSignature($signature, $payload, $authKey)) {
        //     return response()->json(['error' => 'Invalid signature'], 403);
        // }

        $data = $request->json()->all();
        $eventName = $data['event_name'][0] ?? null;
        $message = $data['event_message'][0] ?? null;

        // Log webhook
        Webhook::create([
            'provider' => 'zeptomail',
            'event' => $eventName,
            'payload' => $data,
            'status' => 'pending',
        ]);

        if (!$eventName || !$message) {
            return response()->json(['error' => 'Missing event data'], 400);
        }

        $emailReference = $message['email_info']['email_reference'] ?? null;
        if (!$emailReference) {
            return response()->json(['error' => 'Missing email reference'], 400);
        }

        // Strip the @bounce-zem.freebyz.com part
        $messageId = explode('@', $emailReference)[0];

        $log = MassEmailLog::where('message_id', $messageId)->first();
        if (!$log) {
            return response()->json(['error' => 'Log not found'], 404);
        }

        $this->handleEvent($eventName, $log, $message);

        return response()->json(['status' => 'success']);
    }

    private function handleEvent(string $eventName, MassEmailLog $log, array $message): void
    {
        $campaign = $log->campaign;
        $details = $message['event_data'][0]['details'][0] ?? [];

        switch ($eventName) {
            case 'softbounce':
            case 'hardbounce':
                $log->update([
                    'status' => 'bounced',
                    'bounced_at' => now(),
                    'error_message' => $details['diagnostic_message'] ?? 'Email bounced',
                ]);
                $campaign->increment('bounced');
                break;

            case 'email_open':
                if ($log->status !== 'opened') {
                    $log->update([
                        'status' => 'opened',
                        'opened_at' => $details['time'] ?? now(),
                    ]);
                    $campaign->increment('opened');
                }
                break;

            case 'email_link_click':
                $log->update([
                    'clicked_at' => $details['time'] ?? now(),
                ]);
                $campaign->increment('clicks');

                break;
        }
    }
    private function isValidZeptoSignature($headerSignature, $payload, $authKey)
    {
        if (!$headerSignature || !$authKey) {
            return false;
        }

        // Parse header: ts=xxx;s=xxx;s-algorithm=HmacSHA256
        preg_match('/ts=([^;]+);s=([^;]+);s-algorithm=(.+)/', $headerSignature, $matches);
        if (count($matches) < 4) return false;

        [$full, $ts, $signature, $algo] = $matches;

        $computed = base64_encode(hash_hmac('sha256', $ts . $payload, $authKey, true));

        return hash_equals($computed, $signature);
    }
}
