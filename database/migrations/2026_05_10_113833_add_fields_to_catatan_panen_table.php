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
        Schema::table('catatan_panen', function (Blueprint $table) {

            $table->unsignedBigInteger('id_area_kerja')->nullable()->after('id_pegawai');

            $table->integer('jumlah_tandan')->default(0)->after('id_area_kerja');

            $table->text('catatan')->nullable()->after('berat_kg');

            $table->string('foto_panen')->nullable()->after('catatan');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catatan_panen', function (Blueprint $table) {

            $table->dropColumn([
                'id_area_kerja',
                'jumlah_tandan',
                'catatan',
                'foto_panen'
            ]);

        });
    }
};