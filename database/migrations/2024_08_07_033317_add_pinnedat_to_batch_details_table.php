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
            $table->timestamp('pinned_at')->after('assignedon')->nullable();  
            $table->string('softdelete')->after('pinned_at')->default(0);  
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
            $table->dropColumn('pinned_at');
            $table->dropColumn('softdelete');
        });
    }
};
