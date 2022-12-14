<?php

namespace App\Services;

use App\Events\TransactionFinished;
use App\Http\Controllers\IntegrationController;
use App\Models\Integration;
use App\Models\Item;
use App\Models\LazadaOrders;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\MinimumStockCount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Lazada\LazopClient;
use Lazada\LazopRequest;

class IntegrationService
{
    const LAZADA = 'lazada';

    /**
     * @param array $products Productid and quantity key value 
     */
    public function createTransaction(array $products)
    {
        foreach ($products as $id => $quantity) {
            $products[$id] = -$quantity;
        }

        $transaction = Transaction::create([
            "user_id" => 1,
            "type" => 'out',
            "comment" => "lazada sync",
        ]);

        foreach ($products as $id => $quantity) {
            $transaction->items()->attach($id, ['quantity' => $quantity]);
        }

        // after store transaction
        foreach ($transaction->items as $item) {
            $original = $item->stock_count;
            $mutate = $item->pivot->quantity;
            $new = $original + $mutate;

            $item->stock_count = $new;
            $item->save();

            $item->pivot->from_count = $original;
            $item->pivot->to_count = $new;
            $item->pivot->save();

            Log::info("Stock count ($item->name) update from $original to $new");

            $belowMinimum = $item->stock_count < $item->minimum_stock;
            if ($belowMinimum) {
                Log::info("Low stock identified (" . $item->name . ") min:$item->minimum_stock quantity left:$item->stock_count, sending notification");
                $notification = (new MinimumStockCount($item, $item->stock_count));
                User::find(1)->notify($notification);
            }
        }
    }

    /**
     * Find intersecting products, force push local stock count to lazada 
     */
    public function syncLocalStockCountToLazada()
    {
        Log::info("IntegrationService: lazada sync up");
        // fetch products from platform
        $apiProducts = $this->getAllProducts();
        // TODO: cache sameProducts to improve performance during consequtive refresh
        $sameProducts = $this->findSameProducts($apiProducts);
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
        $in = $this->getIntegrationLazadaRecord();
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


    private function findSameProducts(array $apiProducts)
    {
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
        return $sameProducts;
    }

    private function getAllProducts()
    {
        $allProducts = [];
        $in = $this->getIntegrationLazadaRecord();
        $token = $in->access_token;
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
            if (count($allProducts) >= $apiReplyTotalProducts[0]) { // only first response tell exact total products
                break;
            }
            // update create_before
            $lastProduct = end($data->products);
            $create_before = Carbon::createFromTimestampMs($lastProduct->created_time)->format('c');
        } while (true);
        return $allProducts;
    }

    private function getOrders($created_after = null)
    {
        $integrationService = new IntegrationService;
        $in = $integrationService->getIntegrationLazadaRecord();
        $client = $this->newLazopClient();
        $lazopRequest = new LazopRequest('/orders/get', 'GET');
        $lazopRequest->addApiParam('access_token', $in->access_token);
        $lazopRequest->addApiParam('created_after', $created_after);
        $lazopRequest->addApiParam('status', 'pending');
        $responseJson = $client->execute($lazopRequest);
        $json = json_decode($responseJson);
        if ($json->code != 0) {
            Log::error('Call lazop api not code 0: ' . $responseJson);
        }
        return $json->data->orders;
    }

    private function getOrderItems($order_id)
    {
        $integrationService = new IntegrationService;
        $in = $integrationService->getIntegrationLazadaRecord();
        $client = $this->newLazopClient();
        $lazopRequest = new LazopRequest('/order/items/get', 'GET');
        $lazopRequest->addApiParam('access_token', $in->access_token);
        $lazopRequest->addApiParam('order_id', $order_id);
        $responseJson = $client->execute($lazopRequest);
        $json = json_decode($responseJson);
        if ($json->code != 0) {
            Log::error('Call lazop api not code 0: ' . $responseJson);
        }
        return $json->data;
    }

    public function getNewOrderLocalItemIds()
    {
        $integrationService = new IntegrationService;
        $in = $integrationService->getIntegrationLazadaRecord();

        // if table empty use access token date
        $lazadaOrders = LazadaOrders::latest('created_at')->first();
        if (!$lazadaOrders) {
            $created_after = $in->created_at->format('c');
        } else {
            $OrderDecoded = json_decode($lazadaOrders->order);
            $created_after = Carbon::parse($OrderDecoded->created_at)->addSecond()->format('c');
        }
        Log::info("getNewOrderLocalItemIds() using created after: $created_after");

        // request
        $orders = $this->getOrders($created_after);
        // request end

        // write to db to refresh $created_after
        foreach ($orders as $order) {
            $lazadaOrders = new LazadaOrders;
            $lazadaOrders->order = json_encode($order);
            $lazadaOrders->save();
        }

        // find skus in each order
        $jsonOrderItems = [];
        foreach ($orders as $order) {
            $items = $this->getOrderItems($order->order_id);
            array_push($jsonOrderItems, ...$items);
        }

        $ids = [];
        foreach ($jsonOrderItems as $element) {
            $dbItem = Item::where('sku', $element->sku)->get()->first();
            if ($dbItem) {
                array_push($ids, $dbItem->id);
            }
        }
        return $ids;
    }

    public function syncDownLazada()
    {
        $integrationService = new IntegrationService;
        $itemIds = $integrationService->getNewOrderLocalItemIds();
        if (!$itemIds) {
            return redirect('integration/lazada')->with('message', 'Sync done, no new orders');;
        }
        // create transactions to mutate stock count
        $itemIdQuantitykv = [];
        foreach ($itemIds as $id) {
            $itemIdQuantitykv[$id] = ($itemIdQuantitykv[$id] ?? 0) + 1;
        }
        $integrationService = new IntegrationService;
        $integrationService->createTransaction($itemIdQuantitykv);
    }

    public function syncUp()
    {
        $integrationService = new IntegrationService;
        $in = $integrationService->getIntegrationLazadaRecord();
        if ($in) {
            event(new TransactionFinished());
        }
    }

    public function getIntegrationLazadaRecord()
    {
        $in = Integration::where('platform_name', self::LAZADA)->latest('created_at')->first();
        if (! $in) {
            Log::alert('Lazada db record empty');
            return;
        }
        $refreshExpireDate = Carbon::parse($in->updated_at)->addSeconds($in->refresh_expires_in);
        $isRefreshExpired = Carbon::now()->greaterThan($refreshExpireDate);
        if ($isRefreshExpired) {
            $in->delete();
            Log::alert('Lazada refresh token expired, lazada integration is paused, user need to authorise again');
            // TODO: email
            return;
        }
        $accessExpireDate = Carbon::parse($in->updated_at)->addSeconds($in->expires_in);
        $isAccessExpired = Carbon::now()->greaterThan($accessExpireDate);
        if ($isAccessExpired) {
            dump("is expired");
            $url = 'https://auth.lazada.com/rest';
            $key = env('INTEGRATION_LAZADA_APPKEY');
            $secret = env('INTEGRATION_LAZADA_APPSECRET');
            $client = new LazopClient($url, $key, $secret);
            $lazRequest = new LazopRequest('/auth/token/refresh', 'GET');
            $lazRequest->addApiParam('refresh_token', $in->refresh_token);
            $response = $client->execute($lazRequest);
            $json = json_decode($response);
            if ($json->code != 0) {
                Log::error('api /auth/token/refresh returned code 0');
                return;
            }
            // write to db
            $in->access_token = $json->access_token;
            $in->expires_in = $json->expires_in;
            $in->refresh_token = $json->refresh_token;
            $in->refresh_expires_in = $json->refresh_expires_in;
            $in->save();
            Log::info('Lazada access token expired, done refresh access token');
        }
        return $in;
    }

    private function newLazopClient($url = 'https://api.lazada.com.my/rest')
    {
        $key = env('INTEGRATION_LAZADA_APPKEY');
        $secret = env('INTEGRATION_LAZADA_APPSECRET');
        return new LazopClient($url, $key, $secret);
    }
}
