<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Helpers\ApiResponse;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'business_name' => 'required|string',
                'business_type' => 'required|string',
                'phone' => 'required|numeric|digits_between:8,15',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'address' => 'required|string',
                'city_id' => 'required|exists:cities,id',
                'region_id' => 'required|exists:regions,id',
            ]);
    
            if ($validator->fails()) {
                return ApiResponse::error('فشل التحقق من البيانات', $validator->errors());
            }
    
            // Insert new location
            $locationData = $request->only(['address', 'latitude', 'longitude', 'city_id', 'region_id']);
            $location = Location::create($locationData);
    
            // Insert new user
            $userData = ['phone' => $request->phone, 'type' => 'customer'];
            $user = User::create($userData);
    
            // Insert new vendor
            $vendorData = [
                'business_name' => $request->business_name,
                'business_type' => $request->business_type,
                'location_id' => $location->id,
                'user_id' => $user->id,
            ];
            $vendor = Vendor::create($vendorData);
    
            if (Role::where('name', 'customer')->exists()) {
                $user->assignRole('customer');
            } else {
                Role::create(['name' => 'customer']);
                $user->assignRole('customer');
            }
    
            $cityName = $location->city ? $location->city->name : null;
            $regionName = $location->region ? $location->region->name : null;
    
            return ApiResponse::success('تم تسجيل المستخدم والتاجر بنجاح', [
                'user' => [
                    'phone' => $user->phone,
                    'type' => $user->type
                ],
                'vendor' => [
                    'business_name' => $vendor->business_name,
                    'business_type' => $vendor->business_type,
                ],
                'location' => [
                    'address' => $location->address,
                    'latitude' => $location->latitude,
                    'longitude' => $location->longitude,
                    'city_name' => $cityName,
                    'region_name' => $regionName 
                ]
            ]);
    
        } catch (\Exception $e) {
            Log::error('خطأ في التسجيل: ' . $e->getMessage());
            return ApiResponse::error('حدث خطأ أثناء التسجيل', ['exception' => $e->getMessage()], 500);
        }
    }
    
    

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|string',
                'otp' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                return ApiResponse::error('فشل التحقق من البيانات', $validator->errors());
            }
    
            $user = User::where('phone', $request->phone)->first();
    
            if (!$user || $request->otp !== $user->vendor->otp) {
                return ApiResponse::error('رقم الهاتف أو رمز التحقق غير صحيح');
            }
    
            if ($user->status !== 'approved' || $user->vendor->is_verified != 1) {
                return ApiResponse::error('حسابك غير نشط أو لم يتم التحقق منه');
            }
    
            if ($user->type !== 'customer') {
                return ApiResponse::error('حسابك غير صحيح');
            }
    
            $token = JWTAuth::fromUser($user);
    
            return ApiResponse::success('تم تسجيل الدخول بنجاح', [
                'phone' => $user->phone,
                'token' => $token
            ]);
    
        } catch (JWTException $e) {
            Log::error('خطأ في إنشاء رمز التحقق: ' . $e->getMessage());
            return ApiResponse::error('لا يمكن إنشاء رمز التحقق', [], 500);
        }
    }
    

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return ApiResponse::success('تم تسجيل الخروج بنجاح');
        } catch (JWTException $e) {
            return ApiResponse::error('حدث خطأ أثناء تسجيل الخروج', [], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'otp' => 'required|digits:4',
                'phone' => 'required|string',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error('فشل التحقق من البيانات', $validator->errors());
            }

            $user = User::where('phone', $request->phone)->first();

            if (!$user || $request->otp !== $user->otp) {
                return ApiResponse::error('رقم الهاتف أو رمز التحقق غير صحيح');
            }

            $user->is_verified = 1;
            $user->save();

            return ApiResponse::success('تم التحقق من المستخدم بنجاح', []);

        } catch (\Exception $e) {
            Log::error('خطأ في التحقق من الرمز: ' . $e->getMessage());
            return ApiResponse::error('حدث خطأ أثناء التحقق من الرمز', ['exception' => $e->getMessage()], 500);
        }
    }


    public function profile(Request $request)
    {
        try {
            $user = $request->user();
    
            if ($user->status !== 'approved' || $user->is_verified != 1) {
                return ApiResponse::error('حسابك غير نشط أو لم يتم التحقق منه');
            }
    
            // Retrieve vendor details if the user is a vendor
            $vendor = Vendor::where('user_id', $user->id)->first();
            
            // Retrieve location details
            $location = Location::where('id', $vendor->location_id ?? null)
                ->with(['city', 'region']) // Load city and region relationships
                ->first();
    
            return ApiResponse::success('تم جلب بيانات المستخدم بنجاح', [
                'user' => [
                    'phone' => $user->phone,
                    'type' => $user->type
                ],
                'vendor' => $vendor ? [
                    'business_name' => $vendor->business_name,
                    'business_type' => $vendor->business_type,
                ] : null,
                'location' => $location ? [
                    'address' => $location->address,
                    'latitude' => $location->latitude,
                    'longitude' => $location->longitude,
                    'city_name' => $location->city ? $location->city->name : null, // Get city name
                    'region_name' => $location->region ? $location->region->name : null // Get region name
                ] : null
            ]);
    
        } catch (\Exception $e) {
            return ApiResponse::error('حدث خطأ أثناء جلب بيانات المستخدم', ['exception' => $e->getMessage()], 500);
        }
    }
    
    public function sendOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|numeric|digits_between:8,15'
            ]);
    
            $user = User::where('phone', $request->phone)->first();

            if (!$user) {
                return ApiResponse::error('فشل التحقق من البيانات');
            }

            if ($user) {
                $otp = $user->otp;
                $message = 'تم إعادة إرسال رمز التحقق بنجاح';
            } 

            return ApiResponse::success($message, [
                'phone' => $request->phone,
                'otp' => $otp 
            ]);
    
        } catch (\Exception $e) {
            Log::error('خطأ في إرسال رمز التحقق: ' . $e->getMessage());
            return ApiResponse::error('حدث خطأ أثناء إرسال رمز التحقق', ['exception' => $e->getMessage()], 500);
        }
    }
    
     
}
