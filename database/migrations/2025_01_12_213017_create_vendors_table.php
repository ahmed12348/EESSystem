<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('business_name')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('business_type')->nullable();
            
            $table->unsignedBigInteger('location_id')->nullable();     
            $table->string('photo')->nullable();  
            $table->string('zone')->nullable(); 
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->unsignedBigInteger('user_id')->unique(); 
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}
