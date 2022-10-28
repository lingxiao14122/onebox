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

class UserService
{
    public function updatePass($user_id, $pass)
    {
        $user = User::find($user_id);
        $user->update([
            'password' => bcrypt($pass)
        ]);
        return $user;
    }
}
