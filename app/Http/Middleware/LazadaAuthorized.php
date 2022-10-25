<?php

namespace App\Http\Middleware;

use App\Http\Controllers\IntegrationController;
use App\Models\Integration;
use App\Services\IntegrationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LazadaAuthorized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $integrationService = new IntegrationService;
        $in = $integrationService->getIntegrationLazadaRecord();
        if (! $in) {
            Log::info('not authed');
            return redirect('/integration');
        }
        return $next($request);
    }
}
