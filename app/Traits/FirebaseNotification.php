<?php

namespace App\Traits;

use Google\Client;
use Google\Service\FirebaseCloudMessaging;

trait FirebaseNotification
{
    protected function sendFirebaseNotification($title, $body, $deviceToken)
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/firebase_credentials.json'));
        $client->addScope(FirebaseCloudMessaging::CLOUD_PLATFORM);

        $service = new FirebaseCloudMessaging($client);
        
        $message = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body
                ],
            ]
        ];

        $url = 'https://fcm.googleapis.com/v1/projects/' . env('FIREBASE_PROJECT_ID') . '/messages:send';

        $response = $client->request('POST', $url, [
            'json' => $message
        ]);

        return json_decode($response->getBody(), true);
    }
}
