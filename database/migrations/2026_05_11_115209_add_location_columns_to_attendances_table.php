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

            // Lokasi saat check in
            $table->decimal('checkin_latitude', 10, 7)->nullable()->after('photo_path');
            $table->decimal('checkin_longitude', 10, 7)->nullable()->after('checkin_latitude');

            // Lokasi saat check out
            $table->decimal('checkout_latitude', 10, 7)->nullable()->after('checkout_photo_path');
            $table->decimal('checkout_longitude', 10, 7)->nullable()->after('checkout_latitude');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {

            $table->dropColumn([
                'checkin_latitude',
                'checkin_longitude',
                'checkout_latitude',
                'checkout_longitude',
            ]);

        });
    }
};