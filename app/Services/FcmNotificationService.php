<?php

namespace App\Services;

use Google_Client;
use Google\Auth\Credentials\ServiceAccountCredentials;

class FcmNotificationService
{
    private $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig(public_path('vrangerAccountKey.json'));
        $this->client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $this->client->setSubject('firebase-adminsdk-htevm@vranger-13d92.iam.gserviceaccount.com');
    }

    public function sendNotification($deviceToken, $title, $message)
    {
        $url = 'https://fcm.googleapis.com/v1/projects/your-project-id/messages:send';

        $headers = [
            'Authorization: Bearer ' . $this->getAccessToken(),
            'Content-Type: application/json',
        ];

        $payload = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $message,
                ],
            ],
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode != 200) {
            \Log::error('FCM Error: ' . $response);
        } else {
            \Log::info('FCM Response: ' . $response);
        }

        curl_close($ch);

        return $response;
    }

    private function getAccessToken()
    {
        $token = $this->client->fetchAccessTokenWithAssertion();
        return $token['access_token'];
    }
}
