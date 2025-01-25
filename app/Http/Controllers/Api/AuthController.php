<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create User with is_verified = 0
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'is_verified' => 'no',
            'otp' => '1234', // Default OTP, you can later integrate actual OTP service
        ]);
        $user->assignRole('customer');
        // Send OTP, for now returning it in response (you can implement OTP sending here)
        return response()->json([
            'message' => 'User registered successfully as a customer. OTP sent.',
            'user' => $user
        ], 201);
    }

    // Login User
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $credentials = $request->only('phone', 'password');

        try {
            // Attempt authentication FIRST
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Now get authenticated user AFTER JWTAuth::attempt() succeeds
            $user = auth()->user();

            // Ensure user exists
            if (!$user) {
                return response()->json(['error' => 'User not found.'], 404);
            }

            // Check status and verification
            if ($user->status !== 'active' || $user->is_verified !== 'yes') {
                return response()->json(['error' => 'Your account is either inactive or not verified.'], 403);
            }

        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }


    public function verifyOtp(Request $request)
    {
        // Validate OTP input
        $request->validate([
            'otp' => 'required|digits:4',
            'phone' => 'required|digits:11',
        ]);

        // Find vendor by phone number
        $user = user::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json(['error' => 'user not found'], 404);
        }

        // Check if the OTP is correct
        if ($request->otp !== $user->otp) {
            return response()->json(['error' => 'Invalid OTP'], 400);
        }

        // OTP is correct, update the vendor's verification status
        $user->is_verified = 'yes';
        // $user->otp = null; // Clear the OTP after successful verification
        $user->save();

        return response()->json(['message' => 'Registration successful! You are now verified.']);
    }

    public function getUser(Request $request)
    {
        $user = $request->user();  // Get the user object

        if ($user->status !== 'active' || $user->is_verified !== 'yes') {
            return response()->json(['error' => 'Your account is either inactive or not verified.'], 403);
        }
        return response()->json($user);
    }

    public function refresh()
    {
        try {
            $user = JWTAuth::user();

            if ($user->status !== 'active' || $user->is_verified !== 'yes') {
                return response()->json(['error' => 'Your account is either inactive or not verified.'], 403);
            }

            // Refresh the JWT token
            return response()->json(['token' => JWTAuth::refresh()]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not refresh token'], 500);
        }
    }

    // Logout User (Invalidate Token)
    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            if ($user->status !== 'active' || $user->is_verified !== 'yes') {
                return response()->json(['error' => 'Your account is either inactive or not verified.'], 403);
            }

            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json(['message' => 'User logged out successfully']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not logout, please try again'], 500);
        }
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        // Check if the user's status is 'yes' and verified is true
        if ($user->status !== 'active' || $user->is_verified !== 'yes' ) {
            return response()->json(['error' => 'Your account is either inactive or not verified.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'profile_picture' => 'nullable|image|max:2048',  // Image file with size check
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            // Update user information
            if ($request->has('name') && !empty($request->name)) {
                $user->name = $request->name;
            }

            if ($request->has('password') && !empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            if ($request->hasFile('profile_picture')) {
                // Handle file upload and save the path
                $filename = $user->id . '_' . time() . '.' . $request->file('profile_picture')->getClientOriginalExtension();
                $path = $request->file('profile_picture')->storeAs('profile_pictures', $filename);
                $user->profile_picture = $path;
            }

            $user->save(); // Save changes

            return response()->json(['message' => 'Profile updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the profile'], 500);
        }
    }


    public function updateProfile(Request $request)
    {
        $user = $request->user();

        // Check if the user's status is 'yes' and verified is true
        if ($user->status !== 'active' || $user->is_verified !== 'yes' ) {
            return response()->json(['error' => 'Your account is either inactive or not verified.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'profile_picture' => 'nullable|image|max:2048',  // Image file with size check
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            // Update user information
            if ($request->has('name') && !empty($request->name)) {
                $user->name = $request->name;
            }

            if ($request->has('password') && !empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            if ($request->hasFile('profile_picture')) {
                // Handle file upload and save the path
                $filename = $user->id . '_' . time() . '.' . $request->file('profile_picture')->getClientOriginalExtension();
                $path = $request->file('profile_picture')->storeAs('profile_pictures', $filename);
                $user->profile_picture = $path;
            }

            $user->save(); // Save changes

            return response()->json(['message' => 'Profile updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the profile'], 500);
        }
    }

}



