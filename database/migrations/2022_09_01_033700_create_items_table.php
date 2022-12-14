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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('sku')->unique();
            $table->string('image')->nullable();
            $table->string('description')->nullable();
            $table->integer('purchase_price')->nullable();
            $table->integer('selling_price')->nullable();
            $table->integer('minimum_stock')->nullable();
            $table->integer('stock_count')->nullable()->unsigned();
            $table->integer('lead_time')->nullable();
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
        Schema::dropIfExists('items');
    }
};
