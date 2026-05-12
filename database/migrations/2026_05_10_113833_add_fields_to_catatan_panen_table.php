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
        if (!Schema::hasColumn('catatan_panen', 'id_area_kerja')) {
            $table->unsignedBigInteger('id_area_kerja')->nullable()->after('id_pegawai');
        }
        if (!Schema::hasColumn('catatan_panen', 'jumlah_tandan')) {
            $table->integer('jumlah_tandan')->nullable()->after('id_area_kerja');
        }
        if (!Schema::hasColumn('catatan_panen', 'foto_panen')) {
            $table->string('foto_panen')->nullable()->after('berat_kg');
        }
        if (!Schema::hasColumn('catatan_panen', 'catatan')) {
            $table->text('catatan')->nullable()->after('foto_panen');
        }
        if (!Schema::hasColumn('catatan_panen', 'keterangan')) {
            $table->text('keterangan')->nullable()->after('catatan');
        }
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