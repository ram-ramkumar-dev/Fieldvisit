<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Driver extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'drivers';

    protected $fillable = [
        'username', 'name', 'phone_number', 'password', 'permissions', 'ic_number', 'app_login', 'sensitive', 'supervisor', 'latitude', 'longitude', 'devicetoken'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'permissions' => 'array',
        'supervisor' => 'array',
    ];
} 