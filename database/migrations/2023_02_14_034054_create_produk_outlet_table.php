<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk_outlet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produks_id')->constrained('produks')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreignId('outlets_id')->constrained('outlets')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk_outlet');
    }
};
