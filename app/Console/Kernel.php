<?php

namespace App\Console;

use App\Models\Integration;
use App\Services\IntegrationService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            // decide if to run sync
            Log::info("run schedule sync down lazada");
            $integrationService = new IntegrationService;
            $in = $integrationService->getIntegrationLazadaRecord();
            if ($in && $in->is_sync_enabled) {
                $integrationService = new IntegrationService;
                $integrationService->syncDownLazada();
            }
        })->everyMinute()->sendOutputTo('/var/www/oneboxapp.tech/storage/logs/laravel.log');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
