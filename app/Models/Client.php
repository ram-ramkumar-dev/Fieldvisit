<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
    // Define the relationship to the State model
    public function getStateName()
    {
        return DB::table('states')
            ->where('id', $this->state) // Assuming 'state_abbreviation' is the column in 'states' table
            ->value('state_name');
    }

    public function clientgroup()
    {
        return $this->belongsTo(ClientGroup::class, 'client_group_id');
    }
}
