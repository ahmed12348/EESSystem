<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Location;
use App\Models\Region;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
class LocationController extends Controller
{
 
    public function getCities()
    {
        $cities = City::all(['id', 'name']);
        return response()->json(['cities' => $cities], 200);
    }

 
    public function getRegionsByCity($city_id)
    {
        $regions = Region::where('city_id', $city_id)->get(['id', 'name']);
        
        if ($regions->isEmpty()) {
            return response()->json(['message' => 'No regions found for this city.'], 404);
        }

        return response()->json(['regions' => $regions], 200);
    }
}
