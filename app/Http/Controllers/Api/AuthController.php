<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'business_name' => 'required|string',
                'business_type' => 'required|string',
                'phone' => 'required|string|unique:users,phone',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'address' => 'required|string',
                'city_id' => 'required|exists:cities,id',
                'region_id' => 'required|exists:regions,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'StatusCode' => 400,
                    'message' => 'فشل التحقق من البيانات',
                    'errors' => $validator->errors()
                ], 400);
            }

            $location = Location::create($request->only(['address', 'latitude', 'longitude', 'city_id', 'region_id']));
            $user = User::create(['phone' => $request->phone, 'type' => 'customer']);
            $vendor = Vendor::create([
                'business_name' => $request->business_name,
                'business_type' => $request->business_type,
                'location_id' => $location->id,
                'user_id' => $user->id,
            ]);

            $user->assignRole('customer');

            return response()->json([
                'StatusCode' => 200,
                'message' => 'تم تسجيل المستخدم والتاجر بنجاح',
                'user' => $user,
                'vendor' => $vendor
            ], 200);

        } catch (\Exception $e) {
            Log::error('خطأ في التسجيل: ' . $e->getMessage());
            return response()->json([
                'StatusCode' => 500,
                'message' => 'حدث خطأ أثناء التسجيل'
            ], 500);
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
                return response()->json([
                    'StatusCode' => 400,
                    'message' => 'فشل التحقق من البيانات',
                    'errors' => $validator->errors()
                ], 400);
            }

            $user = User::where('phone', $request->phone)->first();

            if (!$user || $request->otp !== $user->otp) {
                return response()->json(['StatusCode' => 400, 'message' => 'رقم الهاتف أو رمز التحقق غير صحيح'], 400);
            }

            if ($user->status !== 'active') {
                return response()->json(['StatusCode' => 400, 'message' => 'حسابك غير نشط'], 400);
            }

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'StatusCode' => 200,
                'message' => 'تم تسجيل الدخول بنجاح',
                'token' => $token,
                'user' => $user,
            ], 200);

        } catch (JWTException $e) {
            Log::error('خطأ في إنشاء رمز التحقق: ' . $e->getMessage());
            return response()->json([
                'StatusCode' => 500,
                'message' => 'لا يمكن إنشاء رمز التحقق'
            ], 500);
        }
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'StatusCode' => 200,
                'message' => 'تم تسجيل الخروج بنجاح'
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'StatusCode' => 500,
                'message' => 'حدث خطأ أثناء تسجيل الخروج'
            ], 500);
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
                return response()->json([
                    'StatusCode' => 400,
                    'message' => 'فشل التحقق من البيانات',
                    'errors' => $validator->errors()
                ], 400);
            }

            $user = User::where('phone', $request->phone)->first();

            if (!$user || $request->otp !== $user->otp) {
                return response()->json(['StatusCode' => 400, 'message' => 'رقم الهاتف أو رمز التحقق غير صحيح'], 400);
            }

            $user->update(['is_verified' => 1]);

            return response()->json([
                'StatusCode' => 200,
                'message' => 'تم التحقق من المستخدم بنجاح',
                'user' => $user
            ], 200);

        } catch (\Exception $e) {
            Log::error('خطأ في التحقق من الرمز: ' . $e->getMessage());
            return response()->json([
                'StatusCode' => 500,
                'message' => 'حدث خطأ أثناء التحقق من الرمز'
            ], 500);
        }
    }

    public function refresh()
    {
        try {
            return response()->json([
                'StatusCode' => 200,
                'message' => 'تم تحديث الرمز بنجاح',
                'token' => JWTAuth::refresh()
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'StatusCode' => 500,
                'message' => 'لا يمكن تحديث الرمز'
            ], 500);
        }
    }

    public function profile(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->status !== 'active' || $user->is_verified != 1) {
                return response()->json(['StatusCode' => 200, 'message' => 'حسابك غير نشط أو لم يتم التحقق منه'], 200);
            }

            return response()->json([
                'StatusCode' => 200,
                'message' => 'تم جلب بيانات المستخدم بنجاح',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'StatusCode' => 500,
                'message' => 'حدث خطأ أثناء جلب بيانات المستخدم'
            ], 500);
        }
    }
}
