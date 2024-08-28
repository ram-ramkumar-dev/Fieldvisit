<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FireBase
{
    public function sendPushNotification($deviceToken, $title, $body, $data = [])
    {
        // Initialize Firebase with service account credentials
        $factory = (new Factory)->withServiceAccount(config('firebase.credentials'));
        $messaging = $factory->createMessaging();

        // Create notification message
        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        // Send the notification
        $messaging->send($message);
    }
}