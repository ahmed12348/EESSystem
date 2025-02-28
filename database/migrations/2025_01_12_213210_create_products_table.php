<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('color')->nullable();
            $table->string('shape')->nullable();
            $table->integer('items')->default(1);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity')->default(0);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->integer('is_active')->default(1);
            $table->string('image')->nullable();
            $table->integer('min_order_quantity')->default(1000);
            $table->integer('max_order_quantity')->default(10000);
            $table->string('notes')->nullable();
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
        Schema::dropIfExists('products');
    }
}
