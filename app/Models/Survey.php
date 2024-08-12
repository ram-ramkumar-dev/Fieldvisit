<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id', 'batch_detail_id', 'user_id', 'has_water_meter', 'water_meter_no', 'has_water_bill', 'water_bill_no',
        'is_correct_address', 'correct_address', 'ownership', 'contact_person_name', 'contact_number', 'email',
        'nature_of_business_code', 'shop_name', 'dr_code', 'property_code', 'occupancy', 'remark', 'visitdate', 'visittime',
        'photo1', 'photo2', 'photo3', 'photo4', 'photo5'
    ];

    // Define the relationship with BatchDetail model
    public function batchDetail()
    {
        return $this->belongsTo(BatchDetail::class, 'batch_detail_id');
    }

    // Define the relationship with Driver model
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'user_id');
    }

    // Define the relationship with Batches model
    public function batch()
    {
        return $this->belongsTo(Batches::class, 'batch_id');
    }
}
