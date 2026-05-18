<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patroli_security', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('nama_area');
            $table->text('keterangan')->nullable();

            $table->string('foto');

            $table->timestamp('waktu_patroli');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patroli_security');
    }
};