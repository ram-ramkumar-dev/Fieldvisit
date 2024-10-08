<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $table = 'companies';
    protected $fillable = ['company_name', 'company_address', 'company_city', 'company_state', 'company_postcode', 'company_phone', 'status'];
    
}
