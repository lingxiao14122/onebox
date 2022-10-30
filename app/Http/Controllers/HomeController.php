<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect('login');
        }
        if (Auth::user()->is_admin) {
            return $this->dashboard();
        }
        return redirect('item');
    }

    private function dashboard()
    {
        $past_week_to_date = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $item_count = DB::select("SELECT COUNT(*) AS item_count FROM `items`")[0]->item_count;
        $total_stock_count = DB::select("SELECT SUM(stock_count) AS total_stock_count FROM items")[0]->total_stock_count;
        $week_stock_out = DB::select("SELECT SUM(quantity) as week_stock_out from item_transaction
            WHERE item_transaction.transaction_id
            IN (select id from transactions WHERE transactions.created_at >= '$past_week_to_date')
            AND item_transaction.transaction_id 
            IN (SELECT id from transactions WHERE transactions.type='out')
        ")[0]->week_stock_out;
        $week_stock_out = $week_stock_out < 0 ? -$week_stock_out : $week_stock_out;

        $week_stock_in = DB::select("SELECT SUM(quantity) as week_stock_in from item_transaction
            WHERE item_transaction.transaction_id
            IN (select id from transactions WHERE transactions.created_at >= '$past_week_to_date')
            AND item_transaction.transaction_id 
            IN (SELECT id from transactions WHERE transactions.type='in')
        ")[0]->week_stock_in;

        $quantities = [$item_count, $total_stock_count, $week_stock_out, $week_stock_in];

        $low_stock_count = Item::whereRaw('minimum_stock > stock_count')->get();

        $forecastController = new ForecastController;
        $forecast = $forecastController->getItemsExpiredRestock(30);
        
        $last_seven_days = Carbon::now()->subWeek();
        $items = Item::all();
        $top_selling = [];
        foreach ($items as $item) {
            $demand = $forecastController->getDemandCount(7, $item->id);
            if ($demand > 0) {
                $item['demand'] = $demand;
                array_push($top_selling, $item);
            }
        }

        return view('dashboard', [
            'quantities' => $quantities,
            'low_stock_count' => $low_stock_count,
            'forecast' => $forecast,
            'top_selling' => $top_selling,
        ]);
    }
}
