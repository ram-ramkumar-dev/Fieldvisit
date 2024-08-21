<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google_Client;
use Google_Service_FirebaseCloudMessaging;

class FcmNotificationService
{
    private $auth;
    private $projectId;

    public function __construct()
    {
        $this->projectId = 'vranger-13d92'; // Replace with your project ID
        
        // Path to the service account JSON file
        $serviceAccountPath = public_path('vrangerAccountKey.json'); 

        // Initialize the Service Account Credentials
        $this->auth = new ServiceAccountCredentials(
            'https://www.googleapis.com/auth/firebase.messaging',
            $serviceAccountPath
        );
    }

    public function sendNotification($deviceToken, $title, $message)
    {
        $url = 'https://fcm.googleapis.com/v1/projects/' . $this->projectId . '/messages:send';

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
        // Create a new Google Client instance
        $client = new Google_Client();
        $client->setAuthConfig(public_path('vrangerAccountKey.json')); // Correct path to your service account key
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        
        // Fetch the access token with assertion
        $client->fetchAccessTokenWithAssertion();

        return $client->getAccessToken()['access_token'];
    }
}
