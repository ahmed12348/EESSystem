<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles,SoftDeletes;
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'type',
        'status',
        'photo',
        
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
        return $this->hasOne(Vendor::class, 'user_id', 'id');
    }
    


    public function isActive()
    {
        return $this->status === 'approved';
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
        return 'id';
    }


    public function cart() {
        return $this->hasMany(Cart::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id', 'id');
    }
}
