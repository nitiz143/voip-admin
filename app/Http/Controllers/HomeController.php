<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CallHistory;
use Location;

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

        $call_history = CallHistory::select('callerip')->get()->unique('callerip');


        foreach($call_history as $h){
            $data[]= \Location::get($h->callerip);
        }
        foreach( $data as $d){
            $ips[] = CallHistory::where('callerip', $d->ip)->get();
        }
        foreach ($ips as $i) {
            $france_call[] =  count($i);
        }

        // dd($data);

        return view('home',compact('france_call','data'));
    }
}
