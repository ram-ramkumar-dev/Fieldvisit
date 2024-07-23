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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned(); // Auto-incrementing ID
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->smallInteger('groups')->default(3);
            $table->smallInteger('status')->default(1);
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
            $table->longText('permissions')->nullable()->charset('utf8mb4')->collation('utf8mb4_bin');
            $table->timestamp('deleted_at')->nullable();
            $table->bigInteger('company_id')->unsigned()->nullable();

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
        Schema::dropIfExists('users');
    }
};
