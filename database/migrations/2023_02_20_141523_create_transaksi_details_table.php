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
        Schema::create('transaksi_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreignId('produks_id')->constrained('produks')->onUpdate('NO ACTION')->onDelete('RESTRICT');
            $table->double('harga');
            $table->float('jumlah');
            $table->double('total');
            $table->text('note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_details');
    }
};
