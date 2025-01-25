<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

   
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
    
        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            $user = Auth::user();
            $role = $user->roles[0]->name; // Assuming the user has one role
    
            return $this->sendLoginResponse($request);
        }
    
        return $this->sendFailedLoginResponse($request);
    }
    

    protected function authenticated(Request $request, $user)
    {
        $role = $user->roles[0]->name; // Assuming each user has one role
    
        if ($role === 'admin') {
            return redirect()->route('admin.index');
        } elseif ($role === 'vendor') {
            return redirect()->route('vendorControl.index');
        }
        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }  

}
