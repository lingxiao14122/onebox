<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $result = File::copyDirectory(base_path() . '/a-materials/product-mages', storage_path('/app/public/itemImages'));
        Log::info("book seeder image copy is: $result");

        DB::table('items')->insert([
            'name' => "MX Master 3s Logitech mouse",
            'sku' => "LOGI-MOU",
            'image' => "itemImages/mx-master-3s-mouse-top-view-graphite.jpg",
            'description' => "A logitech mouse",
            'purchase_price' => 59,
            'selling_price' => 65,
            'minimum_stock' => 5,
            'stock_count' => 0,
            'lead_time' => 7
        ]);
        DB::table('items')->insert([
            'name' => "MX Keys Mini Logitech keyboard",
            'sku' => "LOGI-MXKEYSMINI",
            'image' => "itemImages/mx-keys-mini-top-pale-gray-us.jpg",
            'description' => "A logitech keyboard with minified size",
            'purchase_price' => 90,
            'selling_price' => 100,
            'minimum_stock' => 5,
            'stock_count' => 0,
            'lead_time' => 7
        ]);
        DB::table('items')->insert([
            'name' => "MX Mechanical Logitech keyboard",
            'sku' => "LOGI-MXTECH",
            'image' => "itemImages/mx-mechanical-keyboard-top-view-graphite-us.jpg",
            'description' => "A logitech keyboard with mechanical keys",
            'purchase_price' => 95,
            'selling_price' => 100,
            'minimum_stock' => 5,
            'stock_count' => 0,
            'lead_time' => 7
        ]);
        DB::table('items')->insert([
            'name' => "MX Keys Logitech keyboard",
            'sku' => "LOGI-MXKEYS",
            'image' => "itemImages/us-mx-keys-gallery-graphite-front.jpg",
            'description' => "A logitech keyboard under MX brand",
            'purchase_price' => 90,
            'selling_price' => 100,
            'minimum_stock' => 5,
            'stock_count' => 0,
            'lead_time' => 7
        ]);
        DB::table('items')->insert([
            'name' => "Black bag",
            'sku' => "BAG-BLK",
            'image' => "itemImages/bag_black.jpg",
            'description' => "A black bag under",
            'minimum_stock' => 12,
            'stock_count' => 00,
            'lead_time' => 7
        ]);
        DB::table('items')->insert([
            'name' => "Pink Foldable Chair",
            'sku' => "RDS-CHA",
            'image' => "itemImages/chair.jpg",
            'description' => "A pink portable stool",
            'minimum_stock' => 5,
            'stock_count' => 0,
            'lead_time' => 7
        ]);
        DB::table('items')->insert([
            'name' => "Glasses",
            'sku' => "RDS-GLA",
            'image' => "itemImages/glasses.png",
            'description' => "A black bag under",
            'minimum_stock' => 5,
            'stock_count' => 0,
            'lead_time' => 7
        ]);
        DB::table('items')->insert([
            'name' => "Steel hammer new wooden handle",
            'sku' => "MRD-STE",
            'image' => "itemImages/hammer.jpg",
            'description' => "Steel hammer with wooden handle",
            'minimum_stock' => 5,
            'stock_count' => 0,
            'lead_time' => 7
        ]);
        DB::table('items')->insert([
            'name' => "Motherboard Asus",
            'sku' => "ROG-MOT",
            'image' => "itemImages/motherboard.jpg",
            'description' => "Asus motherboard full size",
            'minimum_stock' => 5,
            'stock_count' => 0,
            'lead_time' => 7
        ]);
        DB::table('items')->insert([
            'name' => "Red Pot",
            'sku' => "RDS-POT",
            'image' => "itemImages/pot.jpg",
            'description' => "A red pot with black handle",
            'minimum_stock' => 5,
            'stock_count' => 0,
            'lead_time' => 7
        ]);
        DB::table('items')->insert([
            'name' => "SIMMAX 8GB USB",
            'sku' => "SIM-USB",
            'image' => "itemImages/usb.jpg",
            'description' => "USB dongle with 3 different color",
            'minimum_stock' => 5,
            'stock_count' => 0,
            'lead_time' => 7
        ]);
        DB::table('items')->insert([
            'name' => "G-shock watch white transparent",
            'sku' => "RDS-WAT",
            'image' => "itemImages/watch.jpg",
            'description' => "G shock white transaparant watch digital",
            'minimum_stock' => 5,
            'stock_count' => 0,
            'lead_time' => 7
        ]);
    }
}
