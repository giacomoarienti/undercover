<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_specific_products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId("order_id")->constrained("orders")->noActionOnDelete();
            $table->foreignId("specific_product_id")->constrained("specific_products")->noActionOnDelete();
            $table->softDeletes();
            $table->integer("quantity");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_specific_products');
    }
};
