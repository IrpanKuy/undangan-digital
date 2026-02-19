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
        Schema::create('poin_langganans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('langganan_id')->constrained('langganans')->onDelete('cascade');
            $table->string('judul');
            $table->boolean('is_checklis')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poin_langganans');
    }
};
