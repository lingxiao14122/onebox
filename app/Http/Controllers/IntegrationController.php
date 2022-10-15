<?php

namespace App\Http\Controllers;

use App\Models\Integration;
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
        }
        return redirect('integration')->with('message', 'Authrorization complete');
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
        dd("update");
    }

    /**
     * Run sync
     */
    public function sync($platform)
    {
        if ($platform == self::LAZADA) {
            $products = $this->getAllProducts();
            dd($products);
            // find same product in database using sku
            // create transaction to update count
            // write log
        }
        dd("huh");
    }

    private function getAllProducts()
    {
        $allProducts = [];
        $in = Integration::where('platform_name', self::LAZADA)->latest('created_at')->first();
        $token = $in->access_token;
        // ISO 8601 date
        $create_before = Carbon::now()->format('c');
        
        do {
            // call api
            $client = $this->newLazopClient();
            $lazopRequest = new LazopRequest('/products/get', 'GET');
            $lazopRequest->addApiParam('access_token', $token);
            $lazopRequest->addApiParam('create_before', $create_before);
            $responseJson = $client->execute($lazopRequest);
            Log::info('Call lazop api returned: '.$responseJson);
            $json = json_decode($responseJson);
            if ($json->code != 0) {
                Log::error('Call lazop api not code 0: '.$responseJson);
                break;
            }
            // data
            $data = $json->data;
            // when data empty
            if (!(array)$data) {
                Log::info('Call lazop api return no data, breaking loop');
                break;
            }
            // push products to allProducts
            $allProducts = array_merge($allProducts, $data->products);
            // update create_before
            $lastProduct = end($data->products);
            $create_before = Carbon::createFromTimestampMs($lastProduct->created_time)->format('c');
        } while (true);
        return $allProducts;
    }

    private function newLazopClient($url = 'https://api.lazada.com.my/rest')
    {
        $key = env('INTEGRATION_LAZADA_APPKEY');
        $secret = env('INTEGRATION_LAZADA_APPSECRET');
        return new LazopClient($url, $key, $secret);
    }
}
