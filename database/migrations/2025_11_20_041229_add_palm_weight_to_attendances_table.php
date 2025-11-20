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
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('palm_weight', 8, 2)->nullable()->after('photo_path')->comment('Berat sawit dalam kg');
            $table->string('checkout_photo_path')->nullable()->after('palm_weight')->comment('Foto saat checkout');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['palm_weight', 'checkout_photo_path']);
        });
    }
};
