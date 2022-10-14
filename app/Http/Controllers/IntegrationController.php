<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    const LAZADA = 'lazada';

    /**
     * Show integration page
     */
    public function index()
    {
        return view('integration.index');
    }

    /**
     * Redirect user to authorization url
     */
    public function auth($platform)
    {
        if ($platform == self::LAZADA) {
            $appkey = env('INTEGRATION_LAZADA_APPKEY', 000000);
            $callbackUrl = "https://oneboxapp.tech/integration/callback/lazada";
            $url = "https://auth.lazada.com/oauth/authorize?response_type=code&force_auth=true&redirect_uri=$callbackUrl&client_id=$appkey";
            // dd($url);
            return redirect($url);
        } else {
            abort(404, "unknown platform");
        }
    }

    /**
     * Retrive authorization code from callback
     */
    public function callback($platform, Request $request)
    {
        dd($platform);
        dd($request->code);
    }
}
