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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_no');
            $table->bigInteger('client_id')->unsigned()->nullable();
            $table->longText('status_code');
            $table->smallInteger('status')->default(1);
            $table->bigInteger('company_id')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('clients'); // Assuming a `clients` table exists      
            $table->foreign('company_id')->references('id')->on('companies'); // Assuming a `companies` table exists
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batches');
    }
};
