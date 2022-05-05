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
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin');
        }
        if(Auth::user()->role == 'Rate Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin');
        }
        if(Auth::user()->role == 'Sales Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin');
        }
        if(Auth::user()->role == 'Billing Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin');
        }
        $users = $users->get();
        return view('users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       // $roles = Config::get('constants.ROLES');
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
        $user =  User::updateOrCreate([
            'id'   => $request->id,
         ],$request->all());

        return response()->json(['message' =>  __('updated_successfully'),'data' => $user,'success'=>true,'redirect_url' => route('users.index')]);
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
        return view('users.edit',compact('user'));
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
}
