<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CallHistory;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $ips = CallHistory::select('callerip')->get();
        // foreach ($ips as $ip) {
        //     $data[]= \Location::get($ip->callerip);
        // }
        // foreach ($data as $dat) {
        //     if($dat->countryCode == 'FR'){
        //             $value[]=$dat;
        //     }
        // }




        return view('home');
    }
}
