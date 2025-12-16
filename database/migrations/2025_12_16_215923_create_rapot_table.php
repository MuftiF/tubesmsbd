<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Buat tabel rapot jika belum ada
        if (!Schema::hasTable('rapot')) {
            Schema::create('rapot', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
                
                // Periode informasi
                $table->string('periode', 100);
                $table->date('periode_start')->nullable();
                $table->date('periode_end')->nullable();
                
                // Data kinerja
                $table->double('total_jam')->default(0);
                $table->integer('hari_kerja')->default(0);
                $table->double('rata_jam_perhari')->default(0);
                $table->double('nilai')->default(0);
                
                // Detail absen (JSON format)
                $table->json('detail_absen')->nullable();
                
                // Evaluasi kinerja
                $table->foreignId('evaluator_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('evaluasi_kerja', 255)->nullable();
                $table->string('saran_perbaikan', 255)->nullable();
                $table->text('catatan')->nullable();
                $table->json('data_evaluasi')->nullable();
                
                // Status dan tipe
                $table->string('status', 50)->default('draft');
                $table->string('tipe', 50)->default('standar'); // 'standar' atau 'evaluasi'
                
                // Timestamps
                $table->timestamp('generated_at')->nullable();
                $table->timestamp('regenerated_at')->nullable();
                $table->timestamps();
                
                // Indexes untuk performa
                $table->index('id_user');
                $table->index('periode_start');
                $table->index('periode_end');
                $table->index('status');
                $table->index('tipe');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('rapot');
    }
};