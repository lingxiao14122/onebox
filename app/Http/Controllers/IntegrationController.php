<?php

namespace App\Http\Controllers;

use App\Models\Integration;
use App\Models\Item;
use App\Models\Transaction;
use App\Notifications\MinimumStockCount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        dd("update");
    }

    /**
     * Run sync
     */
    public function sync($platform)
    {
        if ($platform == self::LAZADA) {
            $this->syncLazadaStockCount();
            $in = Integration::where('platform_name', self::LAZADA)->latest('created_at')->first();
            $client = $this->newLazopClient();
            $lazopRequest = new LazopRequest('/orders/get', 'GET');
            $lazopRequest->addApiParam('access_token', $in->access_token);
            $lazopRequest->addApiParam('created_after', $in->created_at->format('c'));
            $responseJson = $client->execute($lazopRequest);
            $json = json_decode($responseJson);
            if ($json->code != 0) {
                Log::error('Call lazop api not code 0: ' . $responseJson);
            }
            dd($json);
        }
        dd("bad platform name, please check url");
    }

    private function getAllProducts()
    {
        $allProducts = [];
        $in = Integration::where('platform_name', self::LAZADA)->latest('created_at')->first();
        $token = $in->access_token;
        // TODO: wrap get token into a method that validates access token before returning
        // ISO 8601 date
        $create_before = Carbon::now()->format('c');
        $apiReplyTotalProducts = [];

        do {
            // call api
            $client = $this->newLazopClient();
            $lazopRequest = new LazopRequest('/products/get', 'GET');
            $lazopRequest->addApiParam('access_token', $token);
            $lazopRequest->addApiParam('create_before', $create_before);
            $lazopRequest->addApiParam('limit', 50); // 50 is maximum according to docs
            $responseJson = $client->execute($lazopRequest);
            // Log::info('Call lazop api returned: ' . $responseJson);
            $json = json_decode($responseJson);
            if ($json->code != 0) {
                Log::error('Call lazop api not code 0: ' . $responseJson);
                break;
            }
            // data
            $data = $json->data;
            // when data empty
            if (!(array)$data) {
                Log::info('Call lazop api return no data, breaking loop');
                break;
            }
            // push total products
            array_push($apiReplyTotalProducts, $data->total_products);
            // push products to allProducts
            $allProducts = array_merge($allProducts, $data->products);
            // determine if already fetch all products, if so break early
            if (count($allProducts) >= $apiReplyTotalProducts[0]){ // only first response tell exact total products
                break;
            }
            // update create_before
            $lastProduct = end($data->products);
            $create_before = Carbon::createFromTimestampMs($lastProduct->created_time)->format('c');
        } while (true);
        return $allProducts;
    }

    private function syncLazadaStockCount()
    {
        // fetch products from platform
        $apiProducts = $this->getAllProducts();
        // find same product in database using sku
        // TODO: cache sameProducts to improve performance during consequtive refresh
        $products = Item::all();
        $sameProducts = [];
        foreach ($products as $product) {
            foreach ($apiProducts as $apiProduct) {
                $localSku = $product->sku;
                $isSameSku = $apiProduct->skus[0]->SellerSku == $localSku;
                if ($isSameSku) {
                    array_push($sameProducts, $product);
                }
            }
        }
        // update quantity on platform 
        $skus = "";
        foreach ($sameProducts as $product) {
            $skus .= "
            <Sku>
                <SellerSku>$product->sku</SellerSku>
                <SellableQuantity>$product->stock_count</SellableQuantity>
            </Sku>
            ";
        }
        $payload = "<Request><Product><Skus>$skus</Skus></Product></Request>";
        $in = Integration::where('platform_name', self::LAZADA)->latest('created_at')->first();
        $token = $in->access_token;
        $client = $this->newLazopClient();
        $lazopRequest = new LazopRequest('/product/stock/sellable/update', 'GET');
        $lazopRequest->addApiParam('access_token', $token);
        $lazopRequest->addApiParam('payload', $payload);
        $responseJson = $client->execute($lazopRequest);
        $json = json_decode($responseJson);
        if ($json->code != 0) {
            Log::error('Call lazop api not code 0: ' . $responseJson);
        }
    }

    private function lazadaInitialSync()
    {
        $this->syncLazadaStockCount();
    }

    private function newLazopClient($url = 'https://api.lazada.com.my/rest')
    {
        $key = env('INTEGRATION_LAZADA_APPKEY');
        $secret = env('INTEGRATION_LAZADA_APPSECRET');
        return new LazopClient($url, $key, $secret);
    }
}
