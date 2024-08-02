<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BatchDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'fileid', 'batch_id', 'name', 'ic_no', 'account_no', 'bill_no', 'amount', 'address', 'district_la', 'taman_mmid', 'roadname', 'state', 'post_code', 'building', 'building_id', 'mail_name', 'mail_add','assignedto ', 'uploadedby', 'assignedby', 'status'
    ];
    
    public function batch()
    {
        return $this->belongsTo(Batches::class);
    }
    // Define the relationship to the State model
    public function getDriverName()
    {
        return DB::table('drivers')
            ->where('id', $this->assignedto) // Assuming 'state_abbreviation' is the column in 'states' table
            ->value('name');
    }
}
