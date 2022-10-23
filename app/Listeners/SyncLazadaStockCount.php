<?php

namespace App\Listeners;

use App\Events\TransactionFinished;
use App\Services\IntegrationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SyncLazadaStockCount implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\TransactionFinished  $event
     * @return void
     */
    public function handle(TransactionFinished $event)
    {
        $integrationService = new IntegrationService;
        $integrationService->syncLocalStockCountToLazada();
    }
}
