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
        Schema::create('transaksi_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->enum('status', ['queue', 'process', 'done', 'taken']);
            $table->foreignId('users_id')->constrained('users')->onUpdate('NO ACTION')->onDelete('RESTRICT');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_status');
    }
};
