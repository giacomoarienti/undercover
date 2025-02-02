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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price');
            $table->foreignId('material_id')->constrained('materials');
            $table->foreignId('phone_id')->constrained('phones');
            $table->foreignId('user_id')->constrained('users');
            $table->string('slug')->unique();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['name', 'phone_id', 'user_id'], 'products_name_phone_user_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
