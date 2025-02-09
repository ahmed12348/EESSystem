<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateADSTable extends Migration
{
     
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            // $table->unsignedBigInteger('vendor_id')->index();
            $table->enum('type', ['product', 'category', 'zone'])->nullable();
            $table->unsignedBigInteger('reference_id')->nullable(); 
            $table->string('zone')->nullable(); 
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
             // Foreign key constraints
            //  $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads');
    }
}
