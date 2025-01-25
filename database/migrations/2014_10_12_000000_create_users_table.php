<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zone')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable()->unique();
            $table->string('otp')->nullable();
            $table->string('profile_picture')->nullable();
            $table->enum('is_verified', ['yes', 'no'])->default('no');
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->rememberToken();
            $table->timestamps();      
            $table->softDeletes();
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
}
