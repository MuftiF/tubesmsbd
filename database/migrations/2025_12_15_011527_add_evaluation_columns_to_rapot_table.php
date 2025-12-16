<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan tabel rapot sudah ada
        if (!Schema::hasTable('rapot')) {
            return;
        }

        Schema::table('rapot', function (Blueprint $table) {

            // Periode
            if (!Schema::hasColumn('rapot', 'periode_start')) {
                $table->date('periode_start')->nullable()->after('periode');
            }

            if (!Schema::hasColumn('rapot', 'periode_end')) {
                $table->date('periode_end')->nullable()->after('periode_start');
            }

            // TOTAL JAM (INI YANG SEBELUMNYA ERROR)
            if (!Schema::hasColumn('rapot', 'total_jam')) {
                $table->double('total_jam')
                      ->default(0)
                      ->after('periode_end');
            }

            // Hari kerja
            if (!Schema::hasColumn('rapot', 'hari_kerja')) {
                $table->integer('hari_kerja')
                      ->default(0)
                      ->after('total_jam');
            }

            // Evaluasi & catatan
            if (!Schema::hasColumn('rapot', 'evaluasi_kerja')) {
                $table->string('evaluasi_kerja', 255)
                      ->nullable()
                      ->after('detail_absen');
            }

            if (!Schema::hasColumn('rapot', 'saran_perbaikan')) {
                $table->string('saran_perbaikan', 255)
                      ->nullable()
                      ->after('evaluasi_kerja');
            }

            if (!Schema::hasColumn('rapot', 'catatan')) {
                $table->text('catatan')
                      ->nullable()
                      ->after('saran_perbaikan');
            }

            // Status rapot
            if (!Schema::hasColumn('rapot', 'status')) {
                $table->string('status', 50)
                      ->default('draft')
                      ->after('catatan');
            }

            // Detail absen (JSON)
            if (!Schema::hasColumn('rapot', 'detail_absen')) {
                $table->json('detail_absen')
                      ->nullable()
                      ->after('hari_kerja');
            }

            // Timestamp regenerasi
            if (!Schema::hasColumn('rapot', 'regenerated_at')) {
                $table->timestamp('regenerated_at')
                      ->nullable()
                      ->after('updated_at');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('rapot')) {
            return;
        }

        Schema::table('rapot', function (Blueprint $table) {

            $columns = [
                'periode_start',
                'periode_end',
                'total_jam',
                'hari_kerja',
                'evaluasi_kerja',
                'saran_perbaikan',
                'catatan',
                'status',
                'detail_absen',
                'regenerated_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('rapot', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
