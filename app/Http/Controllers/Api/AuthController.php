<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_name' => 'required|string',
            'business_type' => 'required|string',
            'phone' => 'required|string',
            // 'tax_id' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'address' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'region_id' => 'required|exists:regions,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        try {
       
            $location = Location::create([
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'city_id' => $request->city_id,
                'region_id' => $request->region_id,
            ]);
    
            // إنشاء المستخدم
            $user = User::create([
                'phone' => $request->phone,
                'type' => 'customer',
                'status' => 'inactive',
            ]);
    
            $vendor = Vendor::create([
                'business_name' => $request->business_name,
                'business_type' => $request->business_type,
                // 'tax_id' => $request->tax_id,
                'location_id' => $location->id, 
                'user_id' => $user->id,  
            ]);
    
      
            $user->assignRole('customer');
    
            return response()->json([
                'message' => 'تم تسجيل المستخدم والتاجر بنجاح.',
                'data' => [
                    'vendor' => $vendor
                ]
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ أثناء تسجيل المستخدم والتاجر.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    
    public function login(Request $request)
    {
        // التحقق من صحة المدخلات
        $validator = Validator::make($request->all(), [
            'phone' => 'required', // التأكد من أن رقم الهاتف موجود
            'otp' => 'required', // التأكد من أن OTP موجود
        ]);
    
        // إذا فشلت عملية التحقق من المدخلات
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        // العثور على المستخدم عبر الهاتف
        $user = User::where('phone', $request->phone)->first();
    
        // إذا لم يتم العثور على المستخدم
        if (!$user) {
            Log::error("Login Failed: User with phone {$request->phone} not found.");
            return response()->json(['error' => 'المستخدم غير موجود.'], 404);
        }
    
        // التحقق من القيمة المخزنة للـ OTP
        if ($request->otp !== $user->otp) {
            return response()->json(['error' => 'OTP غير صحيح'], 400); // Return error if OTP doesn't match
        }
    
        // التحقق من حالة الحساب إذا كانت نشطة
        if ($user->status !== 'active') {
            Log::error("Login Failed: User {$request->phone} has inactive status.");
            return response()->json(['error' => 'حسابك غير مفعل.'], 403);
        }
    
        // إذا كانت كل الأشياء صحيحة، نقوم بتوليد التوكن باستخدام الـ JWT
        try {
            // توليد التوكن للمستخدم باستخدام الـ JWT
            $token = JWTAuth::fromUser($user);
    
            // إذا لم يتم الحصول على توكن
            if (!$token) {
                Log::error("JWTAuth::fromUser() failed for user {$request->phone}");
                return response()->json(['error' => 'غير مصرح.'], 401);
            }
        } catch (\Exception $e) {
            Log::error("JWT Token Generation Error: " . $e->getMessage());
            return response()->json(['error' => 'لم نتمكن من إنشاء التوكن.'], 500);
        }
    
        // إرجاع استجابة ناجحة مع التوكن
        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'token' => $token,
            'user' => $user->vendor,
        ], 200); // حالة 200 تعني نجاح العملية
    }
    
    


    public function verifyOtp(Request $request)
    {
        try {
            // Validate OTP input
            $request->validate([
                'otp' => 'required|digits:4', // Ensures OTP is a 4-digit number
                'phone' => 'required', // Ensures phone is 11 digits
            ]);

            // Find user by phone number
            $user = User::where('phone', $request->phone)->first(); // Look up user by phone number

            // Check if user exists
            if (!$user) {
                return response()->json(['error' => 'المستخدم غير موجود'], 404); // Return error if user is not found
            }

            // Check if the OTP entered is correct
            if ($request->otp !== $user->otp) {
                return response()->json(['error' => 'OTP غير صحيح'], 400); // Return error if OTP doesn't match
            }

            $user->is_verified =1; 
            $user->save(); 

      
            return response()->json([
                'message' => 'تم التحقق من المستخدم بنجاح!',
                'data' => [
                    'user' => $user->vendor 
                ]
            ], 200); 

        } catch (\Exception $e) {
            // Handle any errors
            return response()->json([
                'error' => 'حدث خطأ أثناء التحقق من المستخدم.',
                'message' => $e->getMessage() 
            ], 500); 
        }
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

    public function logout(Request $request)
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'تم تسجيل الخروج بنجاح'], 200);
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



