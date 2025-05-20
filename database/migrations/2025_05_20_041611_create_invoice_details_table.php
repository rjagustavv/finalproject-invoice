<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->string('coil_number', 50);
            $table->decimal('width', 10, 2);
            $table->decimal('length', 10, 2);
            $table->decimal('thickness', 10, 2);
            $table->decimal('weight', 10, 2);
            $table->decimal('price', 12, 2); // Ini adalah harga per item detail
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
    }
};
