<?php

namespace App\Traits;

trait fireBaseAuthentication
{
    public function authFirebase()
    {
        $credentialsFilePath = file_get_contents('https://usi-eg.com/Elbatal/EES/public/eessystem-firebase.json');

        $json = json_decode($credentialsFilePath, true);

        $client = new \Google_Client();
        $client->setAuthConfig($json);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();
        return $token['access_token'];
    }

}
