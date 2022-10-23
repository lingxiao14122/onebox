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
            ]);
            $this->lazadaInitialSync();
            // TODO: start job fetching orders
            return redirect('integration')->with('message', 'Authrorization complete');
        }
        dd("bad platform name, please check url");
    }

    /**
     * Edit integration preferences
     */
    public function edit()
    {
        return view('integration.edit');
    }

    /**
     * Save the updated integration preferences
     */
    public function update()
    {
        // TODO: do this
        dd("update");
    }

    public function syncDown($platform, Request $request)
    {
        if ($platform == self::LAZADA) {
            $integrationService = new IntegrationService;
            $itemIds = $integrationService->getNewOrderLocalItemIds();
            if (!$itemIds) {
                return redirect('integration/lazada');
            }
            // create transactions to mutate stock count
            $itemIdQuantitykv = [];
            foreach ($itemIds as $id) {
                $itemIdQuantitykv[$id] = ($itemIdQuantitykv[$id] ?? 0) + 1;
            }
            $integrationService = new IntegrationService;
            $integrationService->createTransaction($itemIdQuantitykv);
            
            if ($request->expectsJson()) {
                return response()->json(['data' => 'ok']);
            }
            
            return redirect('integration/lazada');
        }
        dd("bad platform name, please check url");
    }

    private function lazadaInitialSync()
    {
        $integrationService = new IntegrationService;
        $integrationService->syncLocalStockCountToLazada();
    }
}
