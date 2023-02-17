<?php

namespace App\Http\Controllers;
use App\Models\Crm;
use App\Models\Client;
use App\Models\User;
use App\Models\Comment;
use App\Models\Billing;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail;
use Auth;

class CRMController extends Controller
{

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
                    <a href="javascript:void(0)" class="delete btn btn-danger btn-sm Delete mb-2"  data-id ="'.$row->id.'">Delete</a>  
                    <a href="'.route('crm.comment',$row->id).'" class=" btn btn-success btn-sm "  data-id ="'.$row->id.'">View</a>';

                    return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('crm.index');
    }

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

   
    public function store(Request $request)
    {

        if(!empty($request->id)){

            $rules = array(
            'lead_owner'=>'required',
            'company'=>'required',
            'firstname'=>'required',
            'lastname'=>'required',
            'email' => ['required', 'string', 'email', 'max:255','regex:/(.+)@(.+)\.(.+)/i','unique:users,email,'.$request->id],
                );
        }else{
            $rules = array(
                'lead_owner'=>'required',
                'company'=>'required',
                'firstname'=>'required',
                'lastname'=>'required',
                'email' => ['required', 'string', 'email', 'max:255','regex:/(.+)@(.+)\.(.+)/i','unique:users,email'],
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


    public function edit($id)
    {
        
        $user = Crm::find($id);
        $data= User::where('id', $user->lead_owner)->get();
        return view('crm.edit',compact('user','data'));
    }


    public function destroy(Request $request)
    {
        CRM::find($request->id)->delete();
        return response()->json(['message'=>__('Deleted Successfully'),'success'=>true]);
    }

    public function ImportClient(Request $request)
    {
        $crm = CRM::where('id',$request->id)->first();
        $lead_owner = User::where('id',$crm->lead_owner)->first();
       return view('crm.convert_to_account',compact('crm','lead_owner'));
    }

    public function Convert_to_Client(Request $request)
    {
        $rules = array(
            'account_name'=>'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            $response = \Response::json([
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()
                ]);
            return $response;
        }
        
        $crm = CRM::find($request->id);
        if($crm){
            $data = [
                // 'id' => $crm->id,
                'lead_owner'=>$request->lead_owner,
                'fax'=>$request->fax,
                'mobile'=>$request->mobile,
                'website'=>$request->website,
                'skype_id'=>$request->skype_id,
                'status'=>$request->status,
                'vat_number'=>$request->vat_number,
                'description'=>$request->description,
                'address_line1'=>$request->address_line1,
                'city'=>$request->city,
                'address_line2'=>$request->address_line2,
                'postzip'=>$request->postzip,
                'address_line3'=>$request->address_line3,
                'country'=>$request->country,
                'company' => $request->company,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'phone' => $request->phone,

            ];
           
            $client = Client::create($data);
            if(!empty($client)){
                $billingdata["account_id"] = $client->id;
                $billingdata["billing_class"] = $request->billing_class;
                $billingdata["billing_type"] = $request->billing_type;
                $billingdata["billing_timezone"] = $request->billing_timezone;
                $billingdata["billing_startdate"] = $request->billing_startdate;
                $billingdata["billing_cycle"] = $request->billing_cycle;
                if($request->billing_cycle == 'in_specific_days'){
                    $billingdata["billing_cycle_startday"] = $request->billing_cycle_startday_for_days;
                }elseif ($request->billing_cycle == 'monthly_anniversary') {
                    $billingdata["billing_cycle_startday"] = $request->billing_cycle_startday_for_monthly;
                }elseif ($request->billing_cycle == 'weekly') {
                    $billingdata["billing_cycle_startday"] = $request->billing_cycle_startday;
                }else{
                    $billingdata["billing_cycle_startday"] = NULL;
                }
                $billingdata["auto_pay"] = $request->auto_pay;
                $billingdata["auto_pay_method"] = $request->auto_pay_method;
                $billingdata["send_invoice_via_email"] = $request->send_invoice_via_email;
                $billingdata["next_invoice_date"] = $request->next_invoice_date;
                $billingdata["next_charge_date"] = $request->next_charge_date;
    
                if(Billing::create($billingdata)){
                    if($crm->delete()){

                        return response()->json(['message' =>  __('CRM Convert to Account Successfully'),'success'=>true,'redirect_url' => route('client.index')]);
                    }
                }
            }
        }
    }

    public function getUser()
    {
        $users = User::query('');
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

    public function comment(Request $request){
        $crm = Crm::where('id',$request->id)->first();
        $data= User::where('id', $crm->lead_owner)->first();
        $comments = Comment::where('crm_id',$request->id)->with('user')->get();
       
        return view('crm.comment',compact('crm','data' ,'comments'));
    }

    public function commentstore(Request $request){
        $rules = array(
            'comment'=>'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            $response = \Response::json([
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()
                ]);
            return $response;
        }
        $data=array();
        $data['crm_id'] = $request->id;
        $data['comment'] = $request->comment;
        $data['user_id'] = Auth::user()->id;
        $Comment = Comment::create($data);
        return response()->json(['message' =>  __('Comment Save Successfully'),'success'=>true]);
    }
}
