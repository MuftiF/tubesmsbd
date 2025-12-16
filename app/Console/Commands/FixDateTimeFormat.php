<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use Carbon\Carbon;

class FixDateTimeFormat extends Command
{
    protected $signature = 'fix:datetime';
    protected $description = 'Fix incorrect datetime formats in attendance table';

    public function handle()
    {
        $this->info('Memperbaiki format datetime...');
        
        $attendances = Attendance::whereNotNull('check_in')
            ->orWhereNotNull('check_out')
            ->get();
        
        $fixed = 0;
        
        foreach ($attendances as $attendance) {
            $updated = false;
            
            // Perbaiki check_in
            if ($attendance->check_in) {
                $fixedTime = $this->fixFormat($attendance->check_in);
                if ($fixedTime != $attendance->check_in) {
                    $attendance->check_in = $fixedTime;
                    $updated = true;
                }
            }
            
            // Perbaiki check_out
            if ($attendance->check_out) {
                $fixedTime = $this->fixFormat($attendance->check_out);
                if ($fixedTime != $attendance->check_out) {
                    $attendance->check_out = $fixedTime;
                    $updated = true;
                }
            }
            
            if ($updated) {
                $attendance->save();
                $fixed++;
                $this->line("Fixed ID: {$attendance->id}");
            }
        }
        
        $this->info("Selesai. {$fixed} data diperbaiki.");
        return 0;
    }
    
    private function fixFormat($datetime)
    {
        // Format 1: 2025-12-16T15:03:46:000000Z
        // Format 2: 2025-12-08T17:00:00:000000Z
        
        // Ganti 'T' dengan spasi
        $datetime = str_replace('T', ' ', $datetime);
        
        // Hapus microsecond (6 digit setelah :)
        if (preg_match('/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}):\d{6}Z$/', $datetime, $matches)) {
            return $matches[1];
        }
        
        // Hapus 'Z' jika ada
        $datetime = rtrim($datetime, 'Z');
        
        return $datetime;
    }
}