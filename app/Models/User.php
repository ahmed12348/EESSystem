<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles,SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'zone',
        'address',
        'phone',
        'user_role',
        'status',
        'is_verified',
        'otp',
        'profile_picture'
    ];
    
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

  
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    public function isVendor()
    {
        return $this->hasRole('vendor');
    }

    public function isApproved()
    {
        return $this->status === 'Active';
    }

    public function orders()
    {
        return $this->hasMany(Order::class);  // Assuming an Order model exists
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

   
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAuthIdentifierName()
    {
        return 'phone';
    }
}
