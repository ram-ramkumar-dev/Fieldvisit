<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('batch_detail_id');
            $table->unsignedBigInteger('user_id');
            $table->string('has_water_meter')->nullable(); 
            $table->string('water_meter_no')->nullable();  
            $table->string('has_water_bill')->nullable();  
            $table->string('water_bill_no')->nullable();  
            $table->string('is_correct_address')->nullable();   
            $table->string('correct_address')->nullable();   
            $table->string('ownership')->nullable();   
            $table->string('contact_person_name')->nullable();   
            $table->string('contact_number')->nullable();   
            $table->string('email')->nullable();   
            $table->string('nature_of_business_code')->nullable();   
            $table->string('shop_name')->nullable();   
            $table->string('dr_code')->nullable();   
            $table->string('property_code')->nullable();   
            $table->string('occupancy')->nullable();   
            $table->longText('remark')->nullable(); 
            $table->date('visitdate')->nullable();
            $table->time('visittime')->nullable();
            $table->longText('photo1')->nullable();
            $table->longText('photo2')->nullable();
            $table->longText('photo3')->nullable();
            $table->longText('photo4')->nullable();
            $table->longText('photo5')->nullable(); 
            $table->timestamps();

            // Foreign keys
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            $table->foreign('batch_detail_id')->references('id')->on('batch_details')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('drivers')->onDelete('cascade');
      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surveys');
    }
};
