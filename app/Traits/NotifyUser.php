<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait NotifyUser
{
    use fireBaseAuthentication;

    protected $url = 'https://fcm.googleapis.com/v1/projects/sanedny-9e95e/messages:send';

    public function pushNotifications($title, $body, $type, $FcmToken, $image = null)
    {
        $data = ['message' => [
            'token' => $FcmToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'image' => $image ? $image : 'https://develop.sanedny.com/assets/images/logo-3.png',
            ],
            'data' => [
                'type' => $type,
                'date_time' => \Carbon\Carbon::now()->format('Y-m-d H:i'),
            ],

        ]
        ];
        $this->send_notify($data);
    }

    public function pushNotificationsForSADAD($titleEn, $bodyEn, $type, $FcmToken, $response)
    {
        $data = [
            'registration_ids' => $FcmToken,
            'notification' => [
                'title' => '#' . $titleEn,
                'body' => $bodyEn,
            ],
            'data' => [
                'type' => $type,
                'date_time' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'response' => $response,
            ],

        ];
        $this->send_notify($data);
    }

    public function send_notify($data)
    {
        Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->authFirebase()
        ])->post('https://fcm.googleapis.com/v1/projects/sanedny-9e95e/messages:send', $data);
    }
}
