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
use Mail;
use Auth;

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
            $data = Crm::query('');
            $getUsers = $this->getUser();
            if(Auth::user()->role != 'Admin'){
                $data = $data->whereIn('lead_owner',$getUsers);
            }
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
                    <a href="'.route('getClient',$row->id).'" class="delete btn btn-info btn-sm Create mb-2"  data-id ="'.$row->id.'">Convert to account </a>
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

        $users= User::query('');
        if(Auth::user()->role == 'Super Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin');
        }
        if(Auth::user()->role == 'NOC Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','Billing Executive')->where('role','!=','Rate Executive')->where('role','!=','Sales Executive')->where('parent_id',Auth::id());
        }
        if(Auth::user()->role == 'Rate Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','NOC Executive')->where('role','!=','Sales Executive')->where('role','!=','Billing Executive')->where('parent_id',Auth::id());
        }
        if(Auth::user()->role == 'Sales Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','NOC Executive')->where('role','!=','Rate Executive')->where('role','!=','Billing Executive')->where('parent_id',Auth::id());
        }
        if(Auth::user()->role == 'Billing Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','NOC Executive')->where('role','!=','Rate Executive')->where('role','!=','Sales Executive')->where('parent_id',Auth::id());
        }
        if(Auth::user()->role == 'NOC Executive' || Auth::user()->role == 'Rate Executive' || Auth::user()->role == 'Sales Executive' || Auth::user()->role == 'Billing Executive' ){
            $users = $users->where('id',Auth::id());
        }
        $users = $users->get();
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


        $cms = array();
        if(!empty($request->id)){
            $cms =  Crm::where('id',$request->id)->select('id','lead_owner','company','firstname','lastname','email','phone','fax','mobile','website','lead_source','lead_status',	'rating','employee','skype_id','status','vat_number','description','address_line1','city','address_line2','postzip','address_line3','country')->first()->toArray();
        }
        $data = array();
        $data["lead_owner"]	= $request->lead_owner;
        $data["company"] = $request->company;
        $data["firstname"] = $request->firstname;
        $data["lastname"] = $request->lastname;
        $data["email"] = $request->email;
        $data["phone"] = $request->phone;
        $data["fax"] = $request->fax;
        $data["mobile"]	= $request->mobile;
        $data["website"] = $request->website;
        $data["lead_source"] = $request->lead_source;
        $data["lead_status"] = $request->lead_status;	
        $data["rating"] = $request->rating;
        $data["employee"] = $request->employee;
        $data["skype_id"] = $request->skype_id;
        $data["status"]	= $request->status;
        $data["vat_number"]	 = $request->vat_number;
        $data["description"] = $request->description;
        $data["address_line1"] = $request->address_line1;
        $data["city"] = $request->city;	
        $data["address_line2"] = $request->address_line2;		
        $data["postzip"] = $request->postzip;
        $data["address_line3"] = $request->address_line3;	
        $data["country"] = $request->country;
        $user =  Crm::updateOrCreate(['id'   => $request->id],$data);

        if(!empty($request->id)){
         
            $info = $request->except('_token');
            $updated_values = array_diff($info, $cms);
            $old_values = array_diff( $cms,$info);
            $user =User::where('role','Admin')->first();

            Mail::send('emails.email',  ["updated_values"=> $updated_values ,"old_values"=>$old_values] , function ($message) use($user)
            {
                $message->to($user->email)
                    ->subject('Update Alert!');
                $message->from(Auth::user()->email);
            });
            return response()->json(['message' =>  __('Updated Successfully'),'data' => $user,'success'=>true,'redirect_url' => ('/crm')]);
        }else{
            return response()->json(['message' =>  __('Created Successfully'),'data' => $user,'success'=>true,'redirect_url' => ('/crm')]);
        }
         


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
        
        $user = Crm::find($id);
        $data= User::where('id', $user->lead_owner)->get();
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
        CRM::find($request->id)->delete();
        return response()->json(['message'=>__('Deleted Successfully'),'success'=>true]);
    }

    public function ImportClient(Request $request)
    {
        //  dd($request->id);
        $crm = CRM::find($request->id);
        if($crm){
            $data = [
                // 'id' => $crm->id,
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

    public function getUser()
    {
        $users = User::query('');
        if(Auth::user()->role == 'Super Admin'){

            $users = $users->where('role','!=','Admin');
        }
        if(Auth::user()->role == 'NOC Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','Billing Executive')->where('role','!=','Rate Executive')->where('role','!=','Sales Executive')->where('parent_id',Auth::id())->orwhere('id',Auth::id());
        }
        if(Auth::user()->role == 'Rate Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','NOC Executive')->where('role','!=','Sales Executive')->where('role','!=','Billing Executive')->where('parent_id',Auth::id())->orwhere('id',Auth::id());
        }
        if(Auth::user()->role == 'Sales Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Billing Admin')->where('role','!=','NOC Executive')->where('role','!=','Rate Executive')->where('role','!=','Billing Executive')->where('parent_id',Auth::id())->orwhere('id',Auth::id());
        }
        if(Auth::user()->role == 'Billing Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','NOC Executive')->where('role','!=','Rate Executive')->where('role','!=','Sales Executive')->where('parent_id',Auth::id())->orWhere('id', '=', Auth::id());
        }
        if(Auth::user()->role == 'NOC Executive' || Auth::user()->role == 'Rate Executive' || Auth::user()->role == 'Sales Executive' || Auth::user()->role == 'Billing Executive' ){
            $users = $users->where('id',Auth::id());
        }
        $users = $users->get();
        $user_ids = [];
        if($users->isNotEmpty()){
            foreach ($users as $user) {
                $user_ids[] = $user->id;
            }
        }
        return $user_ids;
    }

    public function changestatus(Request $request){
        $cms =  Crm::where('id',$request->id)->first();
        $status =Crm::where('id',$request->id)->update(['lead_status'=>$request->status]);
       
        $info = [
                'new_status' => $request->status,
                'old_status' => $cms->lead_status
            ];
        $user =User::where('role','Admin')->first();
        Mail::send('emails.update_email', ["info"=>$info] , function ($message) use($user)
        {
            $message->to($user->email)
                ->subject('Lead Status Update Alert!');
            $message->from(Auth::user()->email);
        });
    }

}
