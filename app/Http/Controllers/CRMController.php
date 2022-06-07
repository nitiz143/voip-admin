<?php

namespace App\Http\Controllers;
use App\Models\Crm;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CRMController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Crm::query('')->get();
            return Datatables::of($data)
            ->addColumn('status', function($row){
                return  $row->status == 0 ? __('Active') : __('Inactive');

            })
            ->addColumn('created_at', function($row){
                return Carbon::parse($row->created_at)->format('d/m/Y H:i:s');

            })
            ->addColumn('updated_at', function($row){
                return Carbon::parse($row->updated_at)->format('d/m/Y H:i:s');

            })
                ->addIndexColumn()
                ->addColumn('action', function($row){

                        $btn = '<a href="'.route('crm.edit',$row->id).'" class="delete btn btn-primary btn-sm Edit mb-2"  data-id ="'.$row->id.'">Edit</a>
                        <a href="'.route('getClient',$row->id).'" class="create btn btn-info btn-sm Create mb-2"  data-id ="'.$row->id.'">Convert to account </a>
                        <a href="javascript:void(0)" class="delete btn btn-danger btn-sm Delete"  data-id ="'.$row->id.'">Delete</a>';

                        return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('crm.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users= User::query('')->get();
        return view('crm.create',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        if(!empty($request->id)){

            $rules = array(
            'lead_owner'=>'required',
            'company'=>'required',
            'firstname'=>'required',
            'lastname'=>'required',
            'email' => ['required', 'string', 'email', 'max:255','regex:/(.+)@(.+)\.(.+)/i','unique:users,email,'.$request->id],
            'phone'=>'required',
            'fax'=>'required',
            'mobile'=>'required',
            'website'=>'required',
            'lead_source'=>'required',
            'lead_status'=>'required',
            'rating'=>'required',
            'employee'=>'required',
            'skype_id'=>'required',
            'status'=>'required',
            'vat_number'=>'required',
            'description'=>'required',
            'address_line1'=>'required',
            'city'=>'required',
            'address_line2'=>'required',
            'postzip'=>'required',
            'address_line3'=>'required',
            'country'=>'required',
                );
        }else{
            $rules = array(
                'lead_owner'=>'required',
                'company'=>'required',
                'firstname'=>'required',
                'lastname'=>'required',
                'email' => ['required', 'string', 'email', 'max:255','regex:/(.+)@(.+)\.(.+)/i','unique:users,email'],
                'phone'=>'required|numeric',
                'fax'=>'required',
                'mobile'=>'required',
                'website'=>['required','regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
                'lead_source'=>'required',
                'lead_status'=>'required',
                'rating'=>'required',
                'employee'=>'required',
                'skype_id'=>'required',
                'status'=>'required',
                'vat_number'=>'required',
                'description'=>'required',
                'address_line1'=>'required',
                'city'=>'required',
                'address_line2'=>'required',
                'postzip'=>'required',
                'address_line3'=>'required',
                'country'=>'required',
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

        $user =  Crm::updateOrCreate([
            'id'   => $request->id,
         ],$request->all());

         return response()->json(['message' =>  __('Updated Successfully'),'data' => $user,'success'=>true,'redirect_url' => ('/crm')]);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data= User::query('')->get();
        $user = Crm::find($id);
        return view('crm.edit',compact('user','data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // dd($request->all());
        CRM::find($request->id)->delete();
        return response()->json(['message'=>__('Deleted Successfully'),'success'=>true]);
    }

    public function ImportClient(Request $request)
    {
        //  dd($request->id);
        $crm = CRM::find($request->id);
        if($crm){
            $data = [
                'id' => $crm->id,
                'lead_owner'=>$crm->lead_owner,
                'fax'=>$crm->fax,
                'mobile'=>$crm->mobile,
                'website'=>$crm->website,
                'skype_id'=>$crm->skype_id,
                'status'=>$crm->status,
                'vat_number'=>$crm->vat_number,
                'description'=>$crm->description,
                'address_line1'=>$crm->address_line1,
                'city'=>$crm->city,
                'address_line2'=>$crm->address_line2,
                'postzip'=>$crm->postzip,
                'address_line3'=>$crm->address_line3,
                'country'=>$crm->country,
                'company' => $crm->company,
                'firstname' => $crm->firstname,
                'lastname' => $crm->lastname,
                'email' => $crm->email,
                'phone' => $crm->phone,

            ];
            if(Client::create($data)){
               if ($crm->delete()){
                // return response()->json(['message' =>  __('Leads Converted Into Account'),'success'=>true,'redirect_url' => route('client.index')]);
                return redirect()->route('client.index')->with('success', 'Leads Converted Into Account');
            }
            }
        }

    }

}
