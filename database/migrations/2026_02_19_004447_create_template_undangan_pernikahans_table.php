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
            $table->string('prosesi');
            $table->date('tanggal_mulai_akad');
            $table->time('waktu_mulai_akad');
            $table->time('waktu_selesai_akad')->nullable();
            $table->text('detail_lokasi_akad_nikah');
            $table->geography('lokasi_akad_nikah', subtype: 'POINT', srid: 4326)->nullable();
            $table->text('doa_pengantinn_pria');
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
