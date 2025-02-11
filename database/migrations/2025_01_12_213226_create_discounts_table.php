<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->integer('discount_value'); // Percentage (e.g., 10, 20, 30)
            $table->string('type'); // product, category, vendor, zone
            $table->unsignedBigInteger('type_id')->nullable(); // ID of selected type (category_id, vendor_id, zone_id)
            $table->text('product_ids')->nullable(); // Store product IDs as comma-separated values (e.g., "1,3,5,7,9")
            $table->date('start_date');
            $table->date('end_date');
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
        Schema::dropIfExists('discounts');
    }
}
