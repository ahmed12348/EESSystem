<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        // dd('here');
        $search = $request->input('search');
        $query = User::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        // Paginate the results
        $users = $query->paginate(10); // 10 users per page, adjust as needed

        return view('admin.users.index', compact('users'));
    }

}
