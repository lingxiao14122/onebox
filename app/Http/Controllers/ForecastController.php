<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ForecastController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::all();
        $periodDays = $request->period ?? 30;

        $items->each(function ($item, $index) use ($periodDays) {
            $item['demandCount'] = $this->getDemandCount($periodDays, $item->id);
            if ($item['demandCount'] == 0) {
                $item['demandCount'] = null;
                return;
            }
            $item['demandPerDay'] = round($this->getDemandCount($periodDays, $item->id) ? $item->demandCount/$periodDays : '', 2);
            $item['daysLeft'] = round($item->stock_count / $item['demandPerDay'], 0);
            $outOfStockDate = Carbon::now()->addDays($item['daysLeft']);
            $item['outOfStock'] = $outOfStockDate->format('d/m/Y');
            $item['restockDate'] = $outOfStockDate->subDays($item->lead_time);
        });
        return view('forecast.index', ['items' => $items, 'period' => $periodDays]);
    }

    private function getDemandCount($periodDays, $item_id)
    {
        $queryDate = Carbon::now()->subDays($periodDays)->toDateTimeString();
        $rawDemandCount = DB::select("
            SELECT SUM(quantity) as sum from item_transaction
            WHERE item_transaction.item_id = $item_id
            AND item_transaction.transaction_id
            IN (select id from transactions WHERE transactions.created_at >= '$queryDate')
            AND item_transaction.transaction_id 
            IN (SELECT id from transactions WHERE transactions.type='out')
        ")[0]->sum;
        $demandCount = $rawDemandCount <= 0 ? -$rawDemandCount : $rawDemandCount ;
        return $demandCount;
    }
}
