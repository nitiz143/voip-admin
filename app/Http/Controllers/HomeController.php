<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;
use App\Models\CallHistory;
// use App\Models\Trunk;
// use App\Models\Setting;
// use DateTime;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;



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
        
        return view('home');
    }

    public function null_record(Request $request)
    {
        if ($request->ajax()) {
            
            $data = CallHistory::where([['account_id', null],['vendor_account_id', null]]);
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('account', function($row){
                    $account = Client::where('id',$row->account_id)->first();
                    return  !empty($account->account_name) ? $account->account_name : "";
                })
                ->addColumn('Connect_time', function($row){
                    $date = new \DateTime();
                    $value = $row->starttime;
                    $startTime =  $date->setTimestamp($value/1000);
                    return !empty($startTime) ? $startTime->format('Y-m-d H:i:s') : "";
                })
                ->addColumn('Disconnect_time', function($row){
                    $date = new \DateTime();
                    $value = $row->stoptime;
                    $stopTime =  $date->setTimestamp($value/1000);
                    return !empty($stopTime) ? $stopTime->format('Y-m-d H:i:s') : "";
                })
                ->addColumn('Cost', function($row){
                    if(!empty($row->fee)){
                        return "$".$row->fee;
                    }else{
                        return '$0.00';
                    }
                })
                ->addColumn('Prefix', function($row){
                        $Prefix = $row->callerareacode;
                    return $Prefix;
                })
                ->addColumn('Avrage_cost', function($row){
                    if(!empty($row->feetime)){
                        $timepersec = $row->fee/$row->feetime;
                        $persec =  round($timepersec, 7);
                        return  '$'.$persec*60;                    
                    }else{
                        return '$0.00';
                    }
                   
                })->addColumn('billing_duration', function($row){

                    if(!empty($row->feetime)){
                        return  $row->feetime;
                    }else{
                        return 0;
                    }
                   
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-target="#ajaxModel" class="view btn btn-primary btn-sm view callhistoryForm" data-id="'.$row->id.'">View</a>' ;
                    return $btn;
                }) 
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    
    public function profile()
    {
        $user = Auth::user();
        return view('profile',compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => ['required', 'string', 'email', 'max:255','regex:/(.+)@(.+)\.(.+)/i','unique:users,email,'.Auth::id()],
            'password_confirmation' => 'same:password',

        ]);
        $user = Auth::user();
        $request['password'] = $request->password ? Hash::make($request->password) : $user->password;

        if($user->update($request->all())){
            return response()->json(['message' =>  __('Profile Updated Successfully')]);
        }
    }
}
