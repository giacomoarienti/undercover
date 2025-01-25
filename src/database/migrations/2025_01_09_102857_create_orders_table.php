<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Casts\Attribute;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId("user_id")->constrained("users")->noActionOnDelete();
            $table->foreignId("coupon_id")->nullable()->constrained("coupons")->noActionOnDelete();
            $table->foreignId("payment_id")->constrained("payments")->noActionOnDelete();
            $table->foreignId("shipping_id")->nullable()->constrained("shippings")->noActionOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
