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
            $table->unsignedBigInteger('user_id')->unique(); // Links to users table
            $table->string('business_name')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('business_type')->nullable();
            $table->string('photo')->nullable();
            $table->string('otp')->default('1234');
            $table->boolean('is_verified')->default(false);
            $table->enum('zone', ['zone_1', 'zone_2', 'zone_3'])->default('zone_1');
            // References to Cities & Regions
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('region_id')->nullable();
            $table->string('address')->nullable();
            $table->text('notes')->nullable();
            // Geo Coordinates
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
            $table->softDeletes();
            // Foreign Key Constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
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
