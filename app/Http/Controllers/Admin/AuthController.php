<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        // Validate request
        $request->validate([
            'phone' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('phone', $request->phone)->first();
        
        if (!$user) {
            return back()->withErrors(['phone' => 'User not found.']);
        }

        if ($user->status == 'inactive') {
            return back()->withErrors(['phone' => 'Admin has not approved your account yet.']);
        }
      
        if ($user->roles->first()->name !== 'admin') {
            return back()->withErrors(['phone' => 'failed Login.']);
        }

        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password])) {
            return redirect()->route('admin.index');
        }

        return back()->withErrors(['phone' => 'Invalid credentials.']);
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('admin.login');
    }

}
