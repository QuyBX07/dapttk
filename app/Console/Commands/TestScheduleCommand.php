<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
class TestScheduleCommand extends Command
{
   protected $signature = 'app:test-schedule';
    protected $description = 'Test lệnh schedule đơn giản';

    public function handle()
    {
        Log::info('✅ Scheduler đã chạy vào command!');
        $this->info('✅ Scheduler đã chạy vào command!');
    }
}
