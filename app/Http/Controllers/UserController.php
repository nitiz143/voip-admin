<?php

namespace App\Http\Controllers;
use App\Models\User;
// use Config;
use Illuminate\Support\Facades\Hash;
use DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;
use Redirect;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role == 'NOC Executive'||auth()->user()->role == 'Rate Executive'||auth()->user()->role == 'Sales Executive'||auth()->user()->role == 'Billing Executive'){
                Redirect::to('home')->send();
            }
            return $next($request);
        });
     
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
        $users = User::where('role','!=','Admin');
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
        $data = $users;
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($row){

               $btn = '<a href="'.route('users.edit',$row->id).'" class="delete btn btn-primary btn-sm Edit"  data-id ="'.$row->id.'">Edit</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="delete btn btn-danger btn-sm Delete"  data-id ="'.$row->id.'">Delete</a>';

                return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('users.create');
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
                'name'=>'required',
                'email' => ['required', 'string', 'email', 'max:255','regex:/(.+)@(.+)\.(.+)/i','unique:users,email,'.$request->id],
                //'password'=> 'required',
                // 'password_confirmation'=> 'required',
                'role' => 'required',
                    );
            }else{
                $rules = array(
                    'name'=>'required',
                    'email' => ['required', 'string', 'email', 'max:255','regex:/(.+)@(.+)\.(.+)/i','unique:users,email'],
                    'password'=> 'required',
                    // 'password_confirmation'=> 'required',
                    'role' => 'required',
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
            $user = User::find($request->id);
            $request['password'] = $request->password ? Hash::make($request->password) : $user->password;
            //DB::table('users')->where('id',$request->id)->delete();
        }else{
            $request['password'] = Hash::make($request->password);
        }
        //dd(Auth::id());
        //$request['parent_id']=Auth::id();

        $user =  User::updateOrCreate([
            'id'   => $request->id,
         ],$request->all());

    return response()->json(['message' =>  __('Updated Successfully'),'data' => $user,'success'=>true,'redirect_url' => ('/users')]);
    }


    public function getUsers(Request $request){
        // dd($request->role);

        $users = User::orderby('id','asc');
        if($request->role == 'NOC Executive'){
            $users = $users->where('role','NOC Admin');
        }elseif($request->role == 'Rate Executive'){
            $users = $users->where('role','Rate Admin');
        }elseif($request->role == 'Sales Executive'){
            $users = $users->where('role','Sales Admin');
        }elseif($request->role == 'Billing Executive'){
            $users = $users->where('role','Billing Admin');
        }
        $users = $users->select('*')->get();
        // Fetch all records
        $response['data'] = $users;

        $html = '';
        if($users->isNotEmpty()){
            $html.='<option selected disabled>--select role--</option>';
            foreach($users as $user)
            // if($user->role!='Admin' && $user->role!='Super Admin'){
                $html .= ' <option  value="'.$user->id.'">'.$user->name.' ('.$user->role.')</option>';
            // }
        }
        return $html;
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
        // dd($id);
        $user = User::find($id);
        $assigns = User::query('')->get();
        return view('users.edit',compact('user','assigns'));
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
        //  dd($request->id);
        User::find($request->id)->delete();
        return response()->json(['message'=>__('Deleted Successfully'),'success'=>true]);

    }


}
