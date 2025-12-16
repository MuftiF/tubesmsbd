<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Perbaiki format datetime yang salah di tabel attendances
        $attendances = DB::table('attendances')
            ->whereNotNull('check_in')
            ->orWhereNotNull('check_out')
            ->get();

        foreach ($attendances as $attendance) {
            $updates = [];
            
            // Perbaiki check_in
            if ($attendance->check_in) {
                $updates['check_in'] = $this->fixDateTimeFormat($attendance->check_in);
            }
            
            // Perbaiki check_out
            if ($attendance->check_out) {
                $updates['check_out'] = $this->fixDateTimeFormat($attendance->check_out);
            }
            
            if (!empty($updates)) {
                DB::table('attendances')
                    ->where('id', $attendance->id)
                    ->update($updates);
            }
        }
    }

    private function fixDateTimeFormat($datetime)
    {
        // Format salah: 2025-12-16T15:03:46:000000Z
        // Format benar: 2025-12-16 15:03:46
        
        // Hapus 'T' dan microsecond
        $datetime = str_replace('T', ' ', $datetime);
        
        // Hapus microsecond dan 'Z'
        if (preg_match('/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}):\d{6}Z$/', $datetime, $matches)) {
            return $matches[1];
        }
        
        // Hapus 'Z' jika ada
        $datetime = rtrim($datetime, 'Z');
        
        return $datetime;
    }

    public function down()
    {
        // Tidak bisa rollback karena data sudah diubah
    }
};