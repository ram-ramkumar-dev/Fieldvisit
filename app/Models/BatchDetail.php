<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchDetail extends Model
{
    use HasFactory;
    
    // Fillable attributes
    protected $fillable = [
        'fileid', 'batch_id', 'name', 'ic_no', 'account_no', 'bill_no', 'amount', 'address', 'district_la', 
        'taman_mmid', 'roadname', 'state', 'post_code', 'building', 'building_id', 'mail_name', 'mail_add',
        'assignedto', 'uploadedby', 'assignedby', 'status', 'batchfile_latitude', 'batchfile_longitude'
    ];

    // Define the relationship with the Batch model
    public function batch()
    {
        return $this->belongsTo(Batches::class, 'batch_id');
    }

    // Define the relationship with the Survey model
    public function surveys()
    {
        return $this->hasMany(Survey::class, 'batch_detail_id');
    }

    // Method to get driver name
    public function getDriverName()
    {
        return \DB::table('drivers')
            ->where('id', $this->assignedto)
            ->value('name');
    }
}

