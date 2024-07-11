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
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('ic_number')->after('permissions');
            $table->integer('app_login')->after('ic_number')->nullable();
            $table->string('sensitive')->after('app_login')->nullable();
            $table->string('supervisor')->after('sensitive')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn('ic_number');
            $table->dropColumn('app_login');
            $table->dropColumn('sensitive');
            $table->dropColumn('supervisor');
        });
    }
};
