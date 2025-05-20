<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change(); // Ubah menjadi nullable
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null'); // Tambahkan kembali constraint jika perlu
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Kembalikan ke kondisi semula (non-nullable)
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};