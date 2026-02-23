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
        Schema::create('acaras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('undangan_id')->constrained('undangans')->onDelete('cascade');
            $table->string('nama_acara'); // Resepsi, Ngunduh Mantu
            $table->time('waktu_acara');
            $table->text('detail_lokasi_acara');
            $table->geography('lokasi_acara', subtype: 'POINT', srid: 4326)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acaras');
    }
};
