<?php

namespace App\Helpers;

use App\Models\AppNotification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Throwable;

class NotificationHelpers
{
    protected Messaging $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    public function createNotification($user, $title, $body, $type)
    {
        try {
            // $this->notifRepo->createForUser($user->id, $title, $body, $type);

            $create =  AppNotification::create([
                'user_id' => $user->id,
                'title'   => $title,
                'body'    => $body,
                'type'    => $type ?? 'general',
                'data'    => $data ?? [],
            ]);

            // $tokens = User::where('id', $user->id)->whereNotNull('fcm_token')->pluck('fcm_token');
            $tokens = User::where('id', $user->id)->whereNotNull('fcm_token')->value('fcm_token');

            if ($tokens) {
                $this->send($tokens, $title, $body);
            }

            return true;
        } catch (Throwable $e) {
            Log::error('Failed to create notification', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function send(string $fcmToken, string $title, string $body, array $data = []): bool
    {
        try {
            $message = CloudMessage::new()
                ->toToken($fcmToken)
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            $this->messaging->send($message);

            return true;
        } catch (Throwable $e) {
            Log::error('FCM send failed', [
                'token'   => $fcmToken,
                'title'   => $title,
                'error'   => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function sendToMultiple(array $tokens, string $title, string $body, array $data = []): bool
    {
        try {
            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            $this->messaging->sendMulticast($message, $tokens);

            return true;
        } catch (Throwable $e) {
            Log::error('FCM multicast failed', [
                'tokens'  => $tokens,
                'title'   => $title,
                'error'   => $e->getMessage(),
            ]);

            return false;
        }
    }
}
