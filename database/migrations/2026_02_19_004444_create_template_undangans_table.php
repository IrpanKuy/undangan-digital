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
        Schema::create('template_undangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('undangan_id')->constrained('undangans')->onDelete('cascade');
            $table->foreignId('kategori_undangan_id')->constrained('kategori_undangans');
            $table->string('thumbnail_template');
            $table->string('judul_undangan');
            $table->boolean('template_premium')->default(false);
            $table->string('file_name'); // Nama file view blade
            $table->boolean('is_active')->default(true);
            $table->string('music_url')->nullable();
            $table->integer('usage')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_undangans');
    }
};
