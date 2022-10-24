<?php

namespace App\Http\Controllers;

use App\Models\Integration;
use App\Models\Item;
use App\Models\LazadaOrders;
use App\Services\IntegrationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Lazada\LazopClient;
use Lazada\LazopRequest;

class IntegrationController extends Controller
{
    const LAZADA = 'lazada';

    /**
     * Show integration page
     */
    public function index()
    {
        // TODO: validate access token here, refresh if needed, if > 30 days remove db row and re-auth
        $lazada = Integration::where('platform_name', self::LAZADA)->latest('created_at')->first();
        return view('integration.index', ['lazada' => collect($lazada)]);
    }

    /**
     * Redirect user to authorization url
     */
    public function auth($platform)
    {
        if ($platform == self::LAZADA) {
            $appkey = env('INTEGRATION_LAZADA_APPKEY');
            $callbackUrl = "https://oneboxapp.tech/integration/callback/lazada";
            $url = "https://auth.lazada.com/oauth/authorize?response_type=code&force_auth=true&redirect_uri=$callbackUrl&client_id=$appkey";
            return redirect($url);
        } else {
            abort(404, "unknown platform");
        }
    }

    /**
     * Callback for oauth protocol, responsible for getting a token
     */
    public function callback($platform, Request $request)
    {
        if ($platform == self::LAZADA) {
            $url = 'https://auth.lazada.com/rest';
            $key = env('INTEGRATION_LAZADA_APPKEY');
            $secret = env('INTEGRATION_LAZADA_APPSECRET');
            $client = new LazopClient($url, $key, $secret);
            $lazRequest = new LazopRequest('/auth/token/create', 'GET');
            $lazRequest->addApiParam('code', $request->code);
            $json = $client->execute($lazRequest);
            $result = json_decode($json);
            if ($result->code != 0) {
                return redirect('integration')->with('message', 'Authrorization failed, wrong code from lazada');
            }
            Integration::create([
                'platform_name' => "lazada",
                'access_token' => $result->access_token,
                'expires_in' => $result->expires_in,
                'refresh_token' => $result->refresh_token,
                'refresh_expires_in' => $result->refresh_expires_in,
                'account_email' => $result->account,
                'is_sync_enabled' => true,
            ]);
            $this->lazadaInitialSync();
            return redirect('integration')->with('message', 'Authrorization complete');
        }
        dd("bad platform name, please check url");
    }

    /**
     * Edit integration preferences
     */
    public function edit()
    {
        $in = Integration::where('platform_name', self::LAZADA)->latest('created_at')->first();
        return view('integration.edit', compact('in'));
    }

    /**
     * Save the updated integration preferences
     */
    public function update(Request $request)
    {
        $in = Integration::where('platform_name', self::LAZADA)->latest('created_at')->first();
        $in->is_sync_enabled = $request->checked;
        $in->save();
        return response()->json(['is_sync_enabled' => $in->is_sync_enabled]);
    }

    public function syncDown($platform, Request $request)
    {
        if ($platform == self::LAZADA) {
            $integrationService = new IntegrationService;
            $integrationService->syncDownLazada();
            
            return redirect('integration/lazada')->with('message', 'Sync done');;
        }
        dd("bad platform name, please check url");
    }

    private function lazadaInitialSync()
    {
        $integrationService = new IntegrationService;
        $integrationService->syncLocalStockCountToLazada();
    }
}
