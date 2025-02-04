<?php

namespace App\Http\Controllers;

use App\Traits\fireBaseAuthentication;
use Illuminate\Http\Request;
use App\Traits\FirebaseNotification;

class NotificationController extends Controller
{
    use fireBaseAuthentication;

    public function sendNotification()
    {
        
        $token = $this->authFirebase();
        dd( $token);
        return response()->json(['firebase_token' => $token]);
    }
}