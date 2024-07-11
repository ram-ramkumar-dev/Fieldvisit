<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Driver extends Model
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'username', 'name', 'phone_number', 'password', 'permissions', 'ic_number', 'app_login', 'sensitive', 'supervisor'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'permissions' => 'array',
        'supervisor' => 'array',
    ];
}
