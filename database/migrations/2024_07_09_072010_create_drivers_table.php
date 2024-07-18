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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('name');
            $table->string('phone_number'); 
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->longText('permissions');
            $table->string('ic_number');
            $table->boolean('app_login');
            $table->string('sensitive')->nullable();
            $table->string('supervisor')->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('company_id')->nullable();
            $table->timestamps();
            $table->foreign('company_id', 'fk_company_id')->references('id')->on('companies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drivers');
    }
};
