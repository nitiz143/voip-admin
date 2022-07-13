<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use Yajra\DataTables\DataTables;
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
    public function destroy(Setting $setting, $id)
    {

        $setting = Setting::find($id);
        dd($request->id);
        $setting->delete();
        // Setting::find($request->id)->delete();
        return response()->json(['message'=>__('Deleted Successfully'),'success'=>true]);
    }
}
