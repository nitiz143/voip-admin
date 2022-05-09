<?php

namespace App\Http\Controllers;
use App\Models\User;
// use Config;
use Illuminate\Support\Facades\Hash;
use DB;
use Datatables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(Auth::user()->whereRole('Super Admin'));
        $users = User::where('role','!=','Admin');
        if(Auth::user()->role == 'Super Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin');
        }
        if(Auth::user()->role == 'NOC Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','Billing Executive')->where('role','!=','Rate Executive')->where('role','!=','Sales Executive');
        }
        if(Auth::user()->role == 'Rate Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','NOC Executive')->where('role','!=','Sales Executive')->where('role','!=','Billing Executive');
        }
        if(Auth::user()->role == 'Sales Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','NOC Executive')->where('role','!=','Rate Executive')->where('role','!=','Billing Executive');
        }
        if(Auth::user()->role == 'Billing Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','NOC Executive')->where('role','!=','Rate Executive')->where('role','!=','Sales Executive');
        }
        $users = $users->paginate(10);
        return view('users.index',compact('users'));
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
        //dd($request->all());

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

        return response()->json(['message' =>  __('updated_successfully'),'data' => $user,'success'=>true,'redirect_url' => ('users.index')]);
    }


    public function getUsers(Request $request){

        $users = User::orderby('id','asc')->where('role',$request->role)->select('*')->get();

        // Fetch all records
        $response['data'] = $users;

        $html = '';
        if($users->isNotEmpty()){
            $html.='<option selected disabled>--select role--</option>';
            foreach($users as $user)
            if($user->role!='Admin' && $user->role!='Super Admin'){
                $html .= ' <option  value="'.$user->id.'">'.$user->name.'</option>';
            }
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
         $assigns = user::query('')->get();
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
        // dd($request->id);
        User::find($request->id)->delete();
        return response()->json(['message'=>__('deleted_successfully'),'success'=>true]);

    }
    // public function user_edit()
    // {
    //     $user = User::('id','Asc')->get();
    //     //dd($user);
    //     return view('users.edit',compact('user'));
    // }

    public function autocomplete(Request $request)
    {

        if(Auth::user()->role == 'Admin'){
            $data = User::where('role','!=','Admin')
                    ->where("name","LIKE","%{$request->name}%");
                    $data = $data->paginate(10);
                    $html = " ";
                   if($data->isNotEmpty()){
                        foreach ($data as $d) {
                            $html.= '<tr>
                            <td>'.$d->id.'</td>
                            <td>'.$d->name.'</td>
                            <td>'.$d->email.'</td>
                            <td>'.$d->role.'</td>
                            <td><a href="'.route('users.edit',$d->id).'" class="delete btn btn-primary btn-sm Edit"  data-id ="'.$d->id.'">Edit</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="delete btn btn-danger btn-sm Delete"  data-id ="'.$d->id.'">Delete</a> </td></tr>';
                        }
                    }
            return $html;
                }

                //for Super Admin

        if(Auth::user()->role == 'Super Admin'){
        $data = User::where('role','!=','Admin')->where('role','!=','Super Admin')
                ->where("name","LIKE","%{$request->name}%");
                $data = $data->paginate(10);
                $html = " ";
               if($data->isNotEmpty()){
                    foreach ($data as $d) {
                        $html.= '<tr>
                        <td>'.$d->id.'</td>
                        <td>'.$d->name.'</td>
                        <td>'.$d->email.'</td>
                        <td>'.$d->role.'</td>
                        <td><a href="'.route('users.edit',$d->id).'" class="delete btn btn-primary btn-sm Edit"  data-id ="'.$d->id.'">Edit</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="delete btn btn-danger btn-sm Delete"  data-id ="'.$d->id.'">Delete</a> </td></tr>';
                    }
                }
        return $html;
            }

            //for NOC Admin

        if(Auth::user()->role == 'NOC Admin'){
        $data = User::where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','Billing Executive')->where('role','!=','Rate Executive')->where('role','!=','Sales Executive')
                ->where("name","LIKE","%{$request->name}%");
                $data = $data->paginate(10);
                $html = " ";
                if($data->isNotEmpty()){
                    foreach ($data as $d) {
                        $html.= '<tr>
                        <td>'.$d->id.'</td>
                        <td>'.$d->name.'</td>
                        <td>'.$d->email.'</td>
                        <td>'.$d->role.'</td>
                        <td><a href="'.route('users.edit',$d->id).'" class="delete btn btn-primary btn-sm Edit"  data-id ="'.$d->id.'">Edit</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="delete btn btn-danger btn-sm Delete"  data-id ="'.$d->id.'">Delete</a> </td></tr>';
                     }
                }
        return $html;
            }

                       //for Rate Admin

                    if(Auth::user()->role == 'Rate Admin'){
                        $data = User::where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','NOC Executive')->where('role','!=','Sales Executive')->where('role','!=','Billing Executive')
                                ->where("name","LIKE","%{$request->name}%");
                                $data = $data->paginate(10);
                                $html = " ";
                               if($data->isNotEmpty()){
                                    foreach ($data as $d) {
                                        $html.= '<tr>
                                        <td>'.$d->id.'</td>
                                        <td>'.$d->name.'</td>
                                        <td>'.$d->email.'</td>
                                        <td>'.$d->role.'</td>
                                        <td><a href="'.route('users.edit',$d->id).'" class="delete btn btn-primary btn-sm Edit"  data-id ="'.$d->id.'">Edit</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="delete btn btn-danger btn-sm Delete"  data-id ="'.$d->id.'">Delete</a> </td></tr>';
                                    }
                                }
                        return $html;
                            }

                             //for Sales Admin

                            if(Auth::user()->role == 'Sales Admin'){
                                $data = User::where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','NOC Executive')->where('role','!=','Rate Executive')->where('role','!=','Billing Executive')
                                        ->where("name","LIKE","%{$request->name}%");
                                        $data = $data->paginate(10);
                                        $html = " ";
                                       if($data->isNotEmpty()){
                                            foreach ($data as $d) {
                                                $html.= '<tr>
                                                <td>'.$d->id.'</td>
                                                <td>'.$d->name.'</td>
                                                <td>'.$d->email.'</td>
                                                <td>'.$d->role.'</td>
                                                <td><a href="'.route('users.edit',$d->id).'" class="delete btn btn-primary btn-sm Edit"  data-id ="'.$d->id.'">Edit</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="delete btn btn-danger btn-sm Delete"  data-id ="'.$d->id.'">Delete</a> </td></tr>';
                                            }
                                        }
                                return $html;
                                }

                                //for Billing Admin

                        if(Auth::user()->role == 'Billing Admin'){
                            $data = User::where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','NOC Executive')->where('role','!=','Rate Executive')->where('role','!=','Sales Executive')
                            ->where("name","LIKE","%{$request->name}%");
                             $data = $data->paginate(10);
                             $html = " ";
                                if($data->isNotEmpty()){
                                    foreach ($data as $d) {
                                         $html.= '<tr>
                                            <td>'.$d->id.'</td>
                                            <td>'.$d->name.'</td>
                                            <td>'.$d->email.'</td>
                                            <td>'.$d->role.'</td>
                                             <td><a href="'.route('users.edit',$d->id).'" class="delete btn btn-primary btn-sm Edit"  data-id ="'.$d->id.'">Edit</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="delete btn btn-danger btn-sm Delete"  data-id ="'.$d->id.'">Delete</a> </td></tr>';
                                                }
                                            }
                                    return $html;
                                    }
    }
}
