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
        Schema::create('user_outlet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->constrained('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreignId('outlets_id')->constrained('outlets')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_outlet');
    }
};
