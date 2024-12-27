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
        Schema::create('reception_methods', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('type', ['iban']);
            $table->string('iban_number', 34)->nullable();
            $table->string('iban_swift')->nullable();
            $table->string('iban_holder_name')->nullable();
            $table->foreignId('user_id')->constrained();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reception_methods');
    }
};
