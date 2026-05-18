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
        Schema::create('kinerja_cleanings', function (Blueprint $table) {

            $table->id();

            // Relasi ke user cleaning
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Area yang dibersihkan
            $table->string('area')->nullable();

            // Keterangan pekerjaan
            $table->text('keterangan');

            // Path foto bukti
            $table->string('foto');

            // Tanggal pekerjaan
            $table->date('tanggal');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kinerja_cleanings');
    }
};