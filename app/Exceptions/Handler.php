<?php

namespace App\Exceptions;

use Dotenv\Exception\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    public function render($request, Throwable $exception)
    {
     
        if ($request->expectsJson()) {
        
            // Unauthorized (Token expired or invalid)
            if ($exception instanceof AuthenticationException) {
                return response()->json([
                    'statusCode' => 401,
                    'message' => 'غير مصرح لك بالوصول، يرجى تسجيل الدخول مجدداً.', // Arabic message
                ], 401);
            }

            if ($exception instanceof UnauthorizedHttpException) {
                return response()->json([
                    'statusCode' => 401,
                    'message' => 'غير مصرح به. يرجى تسجيل الدخول أو تحديث الرمز المميز.',
                    'error' => 'Unauthorized'
                ], 401);
            }
    
            if ($exception instanceof TokenExpiredException) {
                // Token expired
                return response()->json([
                    'error' => 'الرمز المميز منتهي الصلاحية'
                ], 401);
            }
    
            if ($exception instanceof TokenInvalidException) {
                // Invalid token
                return response()->json([
                    'error' => 'الرمز المميز غير صالح'
                ], 401);
            }
    
            if ($exception instanceof JWTException) {
                // General JWT error
                return response()->json([
                    'error' => 'حدث خطأ في معالجة الرمز المميز'
                ], 401);
            }

            // Model Not Found (e.g., trying to fetch a non-existent resource)
            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'statusCode' => 404,
                    'message' => 'العنصر غير موجود.', // Arabic message
                ], 404);
            }
    
            // Invalid URL or Route Not Found
            if ($exception instanceof NotFoundHttpException) {
                return response()->json([
                    'statusCode' => 404,
                    'message' => 'لم يتم العثور على الصفحة المطلوبة.', // Arabic message
                ], 404);
            }
    
            // Validation Error
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'statusCode' => 422,
                    'message' => 'هناك خطأ في البيانات المدخلة.', // Arabic message
                    'errors' => $exception->errors(),
                ], 422);
            }
    
            // Generic Server Error
            return response()->json([
                'statusCode' => 500,
                'message' => 'حدث خطأ غير متوقع، يرجى المحاولة لاحقاً.', // Arabic message
                'error' => $exception->getMessage(), // Remove in production
            ], 500);
        }
        if ($exception instanceof NotFoundHttpException) {
            return response()->view('errors.404', [], 404);
        }
    
        // Handle other exceptions normally
        return parent::render($request, $exception);

        // if ($request->expectsJson()) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
        // return parent::render($request, $exception);
    }



    // public function render($request, Throwable $exception): JsonResponse
    // {
    //     if ($request->expectsJson()) {
    //         return response()->json([
    //             'statusCode' => 500,
    //             'message' => 'حدث خطأ أثناء معالجة الطلب.',
    //             'error' => $exception->getMessage(), // Debugging purposes (remove in production)
    //         ], 500);
    //     }

    //     return parent::render($request, $exception);
    // }

}
