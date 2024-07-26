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
        Schema::create('batch_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('ic_no');
            $table->string('account_no');
            $table->string('bill_no');
            $table->decimal('amount', 8, 2);
            $table->string('address');
            $table->string('district_la');
            $table->string('taman_mmid');
            $table->string('roadname');
            $table->string('state');
            $table->string('post_code');
            $table->string('building');
            $table->string('building_id');
            $table->string('mail_name');
            $table->string('mail_add');
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
        Schema::dropIfExists('batch_details');
    }
};
