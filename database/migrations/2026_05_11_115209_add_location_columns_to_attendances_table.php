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
        if (!Schema::hasColumn('attendances', 'checkin_latitude')) {
            $table->decimal('checkin_latitude', 10, 7)->nullable()->after('checkout_photo_path');
        }
        if (!Schema::hasColumn('attendances', 'checkin_longitude')) {
            $table->decimal('checkin_longitude', 10, 7)->nullable()->after('checkin_latitude');
        }
        if (!Schema::hasColumn('attendances', 'checkout_latitude')) {
            $table->decimal('checkout_latitude', 10, 7)->nullable()->after('checkin_longitude');
        }
        if (!Schema::hasColumn('attendances', 'checkout_longitude')) {
            $table->decimal('checkout_longitude', 10, 7)->nullable()->after('checkout_latitude');
        }
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