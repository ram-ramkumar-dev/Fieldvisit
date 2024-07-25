<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_name',
        'registration_no',
        'address',
        'city',
        'state',
        'postcode',
        'phone1',
        'client_group_id',
        'company_id'
    ];

    public function clientgroup()
    {
        return $this->belongsTo(ClientGroup::class, 'id');
    }
}
