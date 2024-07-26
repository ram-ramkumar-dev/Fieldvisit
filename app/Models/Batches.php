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
    
}
