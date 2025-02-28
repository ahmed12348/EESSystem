<?php

namespace App\Providers;


use App\Models\Ads;
use App\Models\Discount;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Product;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ApprovedScopeServiceProvider extends ServiceProvider
{
    // public function boot()
    // {
    //     if (!Auth::check() || Auth::user()->type !== 'admin') {
        
    //         // Apply the scope to User, Product, and Vendor
    //         User::addGlobalScope('approved', function (Builder $builder) {
    //             $builder->where('status', 'approved');
    //         });

    //         Product::addGlobalScope('approved', function (Builder $builder) {
    //             $builder->where('status', 'approved');
    //         });

    //         Discount::addGlobalScope('approved', function (Builder $builder) {
    //             $builder->where('status', 'approved')
    //                     ->where('start_date', '<=', Carbon::now())
    //                     ->where('end_date', '>=', Carbon::now());
    //         });

    //         // Active Ads: Approved + Start Date Passed + Not Expired
    //         Ads::addGlobalScope('approved', function (Builder $builder) {
    //             $builder->where('status', 'approved')
    //                     ->where('start_date', '<=', Carbon::now())
    //                     ->where('end_date', '>=', Carbon::now());
    //         });
    //     }
    // }
}


