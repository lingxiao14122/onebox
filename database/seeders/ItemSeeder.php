<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->insert([
            'name' => "MX Master 3s Logitech mouse",
            'sku' => "LOGI-MOU",
            'image' => "itemImages/mx-master-3s-mouse-top-view-graphite.jpg",
            'description' => "A logitech mouse",
            'purchase_price' => 59,
            'selling_price' => 65,
            'minimum_stock' => 5,
            'stock_count' => 1
        ]);
        DB::table('items')->insert([
            'name' => "MX Keys Mini Logitech keyboard",
            'sku' => "LOGI-MXKEYSMINI",
            'image' => "itemImages/mx-keys-mini-top-pale-gray-us.jpg",
            'description' => "A logitech keyboard with minified size",
            'purchase_price' => 90,
            'selling_price' => 100,
            'minimum_stock' => 5,
            'stock_count' => 2
        ]);
        DB::table('items')->insert([
            'name' => "MX Mechanical Logitech keyboard",
            'sku' => "LOGI-MXTECH",
            'image' => "itemImages/mx-mechanical-keyboard-top-view-graphite-us.jpg",
            'description' => "A logitech keyboard with mechanical keys",
            'purchase_price' => 95,
            'selling_price' => 100,
            'minimum_stock' => 5,
            'stock_count' => 13
        ]);
        DB::table('items')->insert([
            'name' => "MX Keys Logitech keyboard",
            'sku' => "LOGI-MXKEYS",
            'image' => "itemImages/us-mx-keys-gallery-graphite-front.jpg",
            'description' => "A logitech keyboard under MX brand",
            'purchase_price' => 90,
            'selling_price' => 100,
            'minimum_stock' => 5,
            'stock_count' => 5
        ]);
    }
}
