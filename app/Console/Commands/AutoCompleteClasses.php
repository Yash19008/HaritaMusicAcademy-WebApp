<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ClassBooking;

class AutoCompleteClasses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-complete-classes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically mark scheduled classes as completed once their end time has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        $updated = ClassBooking::where('status', 'scheduled')
            ->where('ends_at', '<', $now)
            ->update(['status' => 'completed']);
            
        if ($updated > 0) {
            $this->info("Auto-completed {$updated} class(es).");
        }
    }
}
