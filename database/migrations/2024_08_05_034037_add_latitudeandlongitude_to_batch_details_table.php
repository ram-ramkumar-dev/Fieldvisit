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
            $table->string('batchfile_latitude')->nullable();
            $table->string('batchfile_longitude')->nullable();
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
            $table->dropColumn('batchfile_latitude');
            $table->dropColumn('batchfile_longitude');
        });
    }
};
