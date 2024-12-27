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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("type");
            $table->string("number", 16)->nullable();
            $table->date("expiration_date")->nullable();
            $table->string("cvv", 3)->nullable();
            $table->string("email")->nullable();
            $table->foreignId("user_id")->nullable()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
