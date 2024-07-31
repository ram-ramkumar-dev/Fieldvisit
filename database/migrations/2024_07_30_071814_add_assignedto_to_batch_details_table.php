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
            $table->bigInteger('fileid')->after('id');
            $table->bigInteger('assignedto')->unsigned()->nullable();
            $table->bigInteger('uploadedby')->nullable();
            $table->bigInteger('assignedby')->nullable();
            $table->string('status')->nullable(); 
            
            $table->foreign('assignedto')->references('id')->on('drivers'); // Assuming a `drivers` table exists 
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
            $table->dropColumn('fileid');
            $table->dropColumn('assignedto');
            $table->dropColumn('uploadedby');
            $table->dropColumn('assignedby');
            $table->dropColumn('status');
        });
    }
};
