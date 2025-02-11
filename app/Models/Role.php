<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission as Permission;
class Role extends Model
{
    use HasFactory,HasRoles;
    protected $guard_name = 'web';  
    protected $fillable = ['name'];
    
    public function user()
    {
        return $this->hasMany(User::class);
    }
    
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}

