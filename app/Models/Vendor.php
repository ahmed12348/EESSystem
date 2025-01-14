<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'phone_number',
        'city',
        'state',
        'country',
        'description',
        'verification_status'
    ];
}
