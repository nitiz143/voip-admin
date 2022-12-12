<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Trunk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use Yajra\DataTables\DataTables;
use Rap2hpoutre\FastExcel\FastExcel;
use Auth;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
                $data = Setting::query('');
                return Datatables::of($data)
                ->addColumn('protocol', function($row){
                    return  $row->protocol == 1 ? __('FTP - File Transfer Protocol') : __('SFTP - SSH File Transfer Protocol');

                })
                ->addIndexColumn()
                ->addColumn('action', function($row){

                        $btn = '<a href="'.route('setting.edit',$row->id).'" class="Edit btn btn-primary btn-sm Edit"  data-id ="'.$row->id.'">Edit</a>&nbsp;&nbsp;
                        <a href="javascript:void(0)" class="delete btn btn-danger btn-sm Delete"  data-id ="'.$row->id.'">Delete</a>';

                        return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);


            }
        return view('setting.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('setting.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Setting $setting)
    {
        // dd($request->all());
        if(!empty($request->id)){
            $rules = array(
            'protocol'=>'required',
            'host'=>'required',
            'port'=>'required',
            'username'=>'required',
            'csv_path'=>'required',
                );
        }else{
            $rules = array(
                'protocol'=>'required',
                'host'=>'required',
                'port'=>'required',
                'username'=>'required',
                'password'=> 'required',
                'csv_path'=>'required',


            );

        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            $response = \Response::json([
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()
                ]);
            return $response;
        }
        if(!empty($request->id)){
            $setting = Setting::find($request->id);
            $request['password'] = $request->password;
            //DB::table('users')->where('id',$request->id)->delete();
        }else{
            $request['password'] = Hash::make($request->password);
        }
        //dd(Auth::id());
        //$request['parent_id']=Auth::id();

        $setting =  Setting::updateOrCreate([
            'id'   => $request->id,
         ],$request->all());


    return response()->json(['message' =>  __('Updated Successfully'),'success'=>true,'redirect_url' => ('/setting')]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
            //   dd($request->all());
            // $user = User::
       $setting = Setting::find($setting->id);
       return view ('setting.edit',compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $setting = Setting::find($id);
        // dd($request->id);
        $setting->delete();
        // Setting::find($request->id)->delete();
        return response()->json(['message'=>__('Deleted Successfully'),'success'=>true]);
    }

    public function trunkIndex(Request $request){
        if ($request->ajax()) {
            $data = Trunk::query('');
            return Datatables::of($data)
            ->filter(function ($instance) use ($request) {
                if ($request->get('status') == '0' || $request->get('status') == '1') {
                    $instance->where('status', $request->get('status'));
                }
            })
            ->addColumn('action', function($row){

                    $btn = '<a href="#" class="Edit btn btn-primary btn-sm Edit"  data-id ="'.$row->id.'">Edit</a>&nbsp;&nbsp;';
                    return $btn;
            })
            ->rawColumns(['action','status'])
            ->make(true);


        }
        return view('trunk.index');
    }

    public function trunkStore(Request $request){
        if(!empty($request->TrunkID)){
            $rules = array(
            'title'=>'required',
                );
        }else{
            $rules = array(
                'title'=>'required',
            );
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            $response = \Response::json([
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()
                ]);
            return $response;
        }
        $data = array();
        $data['title'] = $request->title; 
        $data['rate_prefix'] = $request->RatePrefix; 
        $data['area_prefix'] = $request->AreaPrefix; 
        $data['prefix'] = $request->Prefix; 
        $data['status'] = !empty($request->Status) ? $request->Status: 0; 
        $Trunk =  Trunk::updateOrCreate(['id'   => $request->TrunkID],$data);


    return response()->json(['message' =>  __('Updated Successfully'),'success'=>true]);
    }

    public function trunkEdit(Request $request){
        $trunk = Trunk::where('id',$request->id)->first();
        return $trunk;
    }

    public function trunks_xlsx(Request $request){
       
        $downloads = Trunk::where('status',$request->status)->get();
        $list =array();
        foreach ( $downloads as $key => $value) {
            $data =array();
            $data['Trunks'] =  $value->title;
            $data['area_prefix'] =  !empty($value->area_prefix) ? $value->area_prefix :"";
            $data['rate_prefix'] =   !empty($value->rate_prefix) ? $value->rate_prefix :"";
            $data['prefix'] =   !empty($value->prefix) ? $value->prefix :"";

            $list[]= $data;
        }
        return (new FastExcel($list))->download('trunk.xlsx');
    }

    public function trunks_csv(Request $request){
        $downloads = Trunk::where('status',$request->status)->get();
        $list =array();
        foreach ( $downloads as $key => $value) {
            $data =array();
            $data['Trunks'] =  $value->title;
            $data['area_prefix'] =  !empty($value->area_prefix) ? $value->area_prefix :"";
            $data['rate_prefix'] =   !empty($value->rate_prefix) ? $value->rate_prefix :"";
            $data['prefix'] =   !empty($value->prefix) ? $value->prefix :"";

            $list[]= $data;
        }
        return (new FastExcel($list))->download('trunk.csv');
    }
}
