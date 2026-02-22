<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_mempelais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('undangan_id')->constrained('undangans')->onDelete('cascade');
            $table->string('foto_pria_path')->nullable();
            $table->string('nama_panggilan_pria');
            $table->string('nama_lengkap_pria');
            $table->string('instagram_url_pria')->nullable();
            $table->string('tiktok_url_pria')->nullable();
            $table->string('x_url_pria')->nullable();
            $table->string('keterangan_keluarga_pria')->nullable();
            $table->string('foto_wanita_path')->nullable();
            $table->string('nama_panggilan_wanita');
            $table->string('nama_lengkap_wanita');
            $table->string('instagram_url_wanita')->nullable();
            $table->string('tiktok_url_wanita')->nullable();
            $table->string('x_url_wanita')->nullable();
            $table->string('keterangan_keluarga_wanita')->nullable();
            $table->text('text_penutup')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_mempelais');
    }
};