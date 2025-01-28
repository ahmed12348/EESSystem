<?php

// app/Http/Controllers/OTPController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;

class OTPController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function sendOTP(Request $request)
    {
        $validatedData = $request->validate(['phone' => 'required|string']);
        // Logic to send OTP is done on the client-side
        // You can log the action or perform other tasks here
        return response()->json(['message' => 'OTP sent'], 200);
    }

    public function verifyOTP(Request $request)
    {
        $validatedData = $request->validate([
            'verificationId' => 'required|string',
            'verificationCode' => 'required|string',
        ]);

        // Logic to verify OTP is done on the client-side
        // You can log the action or perform other tasks here
        return response()->json(['message' => 'OTP verified'], 200);
    }
}