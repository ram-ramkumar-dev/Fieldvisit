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
        Schema::create('clients', function (Blueprint $table) {
            $table->id('client_id');
            $table->string('clientId');
            $table->string('client_name');
            $table->integer('clientgroupId');
            $table->string('registration_no');
            $table->longText('address');
            $table->string('city');
            $table->string('state');
            $table->string('postcode');
            $table->string('phone1');
            $table->string('phone2');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
};
