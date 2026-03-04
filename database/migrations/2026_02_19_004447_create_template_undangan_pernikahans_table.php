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
        Schema::create('template_undangan_pernikahans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('undangan_id')->constrained('undangans')->onDelete('cascade');
            $table->string('nama_prosesi');
            $table->date('tanggal_mulai');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai')->nullable();
            $table->text('detail_lokasi_nikah');
            $table->boolean('show_map')->default(false);
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->integer('zoom')->default(13);
            $table->text('doa_pengantin_pria');
            $table->text('doa_pengantin_wanita');
            $table->text('no_rek_amplop')->nullable();
            $table->text('lokasi_pengiriman_kado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_undangan_pernikahans');
    }
};
