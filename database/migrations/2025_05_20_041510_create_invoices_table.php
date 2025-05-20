<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Untuk relasi ke user
            $table->string('invoice_number', 50)->unique();
            $table->string('customer_name', 100);
            $table->date('delivery_date');
            $table->timestamp('submit_date')->nullable(); // Bisa diisi saat submit atau sama dengan created_at
            $table->decimal('total_amount', 15, 2)->default(0); // Untuk menyimpan total harga
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};