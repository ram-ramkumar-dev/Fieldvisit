<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batches extends Model
{
    use HasFactory;
    protected $table = 'batches';
    protected $fillable = ['batch_no', 'client_id', 'status_code', 'company_id', 'status'];
    protected $casts = [
        'status_code' => 'array', 
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    
    public function batchDetails()
    {
        return $this->hasMany(BatchDetail::class, 'batch_id');
    }
    
    public function surveys()
    {
        return $this->hasMany(Survey::class, 'batch_id');
    }

    public function getStatusDetails()
    {
        // Assuming status_code is an array or comma-separated list in your batches table
        $statusCodes = is_array($this->status_code) ? $this->status_code : explode(',', $this->status_code);
        
        $statuses = Status::whereIn('id', $statusCodes)->get();

        // Map through the statuses and concatenate the status_name and description
        $statusDetails = $statuses->map(function($status) {
            return $status->statuscode . ' (' . $status->description . ')';
        });

        // Convert the collection to an array and implode into a string
        return implode(', ', $statusDetails->toArray());
    }
}
