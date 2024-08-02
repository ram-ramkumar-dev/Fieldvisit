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
        Schema::table('batch_details', function (Blueprint $table) {
            $table->bigInteger('fileid')->nullable()->change(); 
            $table->string('name')->nullable()->change();
            $table->string('ic_no')->nullable()->change();
            $table->string('account_no')->nullable()->change();
            $table->string('bill_no')->nullable()->change();
            $table->decimal('amount', 8, 2)->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->string('district_la')->nullable()->change();
            $table->string('taman_mmid')->nullable()->change();
            $table->string('roadname')->nullable()->change();
            $table->string('state')->nullable()->change();
            $table->string('post_code')->nullable()->change();
            $table->string('building')->nullable()->change();
            $table->string('building_id')->nullable()->change();
            $table->string('mail_name')->nullable()->change();
            $table->string('mail_add')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batch_details', function (Blueprint $table) {
            $table->bigInteger('fileid')->nullable(false)->change(); 
            $table->string('name')->nullable(false)->change();
            $table->string('ic_no')->nullable(false)->change();
            $table->string('account_no')->nullable(false)->change();
            $table->string('bill_no')->nullable(false)->change();
            $table->decimal('amount', 8, 2)->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
            $table->string('district_la')->nullable(false)->change();
            $table->string('taman_mmid')->nullable(false)->change();
            $table->string('roadname')->nullable(false)->change();
            $table->string('state')->nullable(false)->change();
            $table->string('post_code')->nullable(false)->change();
            $table->string('building')->nullable(false)->change();
            $table->string('building_id')->nullable(false)->change();
            $table->string('mail_name')->nullable(false)->change();
            $table->string('mail_add')->nullable(false)->change();
        });
    }
};
