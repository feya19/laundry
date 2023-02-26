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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlets_id')->constrained('outlets')->onUpdate('NO ACTION')->onDelete('RESTRICT');
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onUpdate('NO ACTION')->onDelete('RESTRICT');
            $table->string('no_invoice');
            $table->dateTime('deadline');
            $table->double('diskon');
            $table->double('potongan');
            $table->double('biaya_tambahan');
            $table->double('total');
            $table->double('bayar');
            $table->double('kembali');
            $table->dateTime('payment_date')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('users_id')->constrained('users')->onUpdate('NO ACTION')->onDelete('RESTRICT');
            $table->string('updated_by', 64)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
