<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Company;
use File;
class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Company::query('')->get();
            return Datatables::of($data)

                ->addIndexColumn()
                ->addColumn('action', function($row){

                        $btn = '<a href="'.route('company.edit',$row->id).'" class="delete btn btn-primary btn-sm Edit"  data-id ="'.$row->id.'">Edit</a>  ';
                        $btn1 = '<a href="" class="delete btn btn-danger btn-sm Delete"  data-id ="'.$row->id.'">Delete</a>';

                        return $btn.$btn1;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('company.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('company.create');
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
                'company_name'=>'required',
                'vat'=>'required',
                'default_trunk_prefix'=>'required',
                'last_trunk_prefix'=>'required',
                'currency'=>'required',
                'timezone'=>'required',
                'default_dashboard'=>'required',
                'first_name'=>'required',
                'last_name'=>'required',
                'email'=>'required',
                'phone'=>'required',
                'address_line1'=>'required',
                'city'=>'required',
                'address_line2'=>'required',
                'postzip'=>'required',
                'address_line3'=>'required',
                'country'=>'required',
                'invoice_status'=>'required',
                'decimal_place'=>'required',
                'header_row'=>'required',
                'footer_row'=>'required',
                'position_left'=>'required',
                'position_right'=>'required',
                'smtp_server'=>'required',
                'email_from'=>'required',
                'smtp_user'=>'required',
                'port'=>'required',
                );
        }else{
            $rules = array(
                'image'=>'required',
                'rate_sheet'=>'required',
                'company_name'=>'required',
                'vat'=>'required',
                'default_trunk_prefix'=>'required',
                'last_trunk_prefix'=>'required',
                'currency'=>'required',
                'timezone'=>'required',
                'default_dashboard'=>'required',
                'first_name'=>'required',
                'last_name'=>'required',
                'email'=>'required',
                'phone'=>'required',
                'address_line1'=>'required',
                'city'=>'required',
                'address_line2'=>'required',
                'postzip'=>'required',
                'address_line3'=>'required',
                'country'=>'required',
                'invoice_status'=>'required',
                'decimal_place'=>'required',
                'header_row'=>'required',
                'footer_row'=>'required',
                'position_left'=>'required',
                'position_right'=>'required',
                'smtp_server'=>'required',
                'email_from'=>'required',
                'smtp_user'=>'required',
                'password'=>'required',
                'port'=>'required',
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
        if ($request->hasFile('image')) {
            $destinationPath = public_path('storage/images');
            if (!file_exists($destinationPath)) {
                File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
            }

            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $image_data  = $image->move($destinationPath, $name);
            $data['image'] = $name;
        }
        if ($request->hasFile('rate_sheet')) {
            $destinationPath = public_path('storage/csv');
            if (!file_exists($destinationPath)) {
                File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
            }

            $image = $request->file('rate_sheet');
            $name = time().'.'.$image->getClientOriginalExtension();
            $image_data  = $image->move($destinationPath, $name);
            $data['rate_sheet'] = $name;
        }


        $data['company_name'] = $request->company_name;
        $data['vat'] = $request->vat;
        $data['default_trunk_prefix'] = $request->default_trunk_prefix;
        $data['last_trunk_prefix'] = $request->last_trunk_prefix;
        $data['currency'] = $request->currency;
        $data['timezone'] = $request->timezone;
        $data['default_dashboard'] = $request->default_dashboard;
        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $data['address_line1'] = $request->address_line1;
        $data['city'] = $request->city;
        $data['address_line2'] = $request->address_line2;
        $data['postzip'] = $request->postzip;
        $data['address_line3'] = $request->address_line3;
        $data['country'] = $request->country;
        $data['invoice_status'] = $request->invoice_status;
        $data['decimal_place'] = $request->decimal_place;
        $data['header_row'] = $request->header_row;
        $data['footer_row'] = $request->footer_row;
        $data['position_left'] = $request->position_left;
        $data['position_right'] = $request->position_right;
        $data['smtp_server'] = $request->smtp_server;
        $data['email_from'] = $request->email_from;
        $data['smtp_user'] = $request->smtp_user;
        if(!empty($request->id)){
            $user = Company::find($request->id);
            $data['password'] = $request->password ? Hash::make($request->password) : $user->password;
        }else{
            $data['password'] = Hash::make($request->password);
        }
        $data['port'] = $request->port;
        $data['cdr'] = $request->cdr ? $request->cdr : 2;
        $data['acc_verification'] = $request->acc_verification ? $request->acc_verification : 2;
        $data['email_invoice'] = $request->email_invoice ? $request->email_invoice : 2;
        $data['certificate'] = $request->certificate ? $request->certificate : 2;
        $data['ssl'] = $request->ssl ? $request->ssl : 2;
       //dd($data);
       $user = Company::updateOrCreate(['id' => $request->id],$data);


        return response()->json(['message' =>'updated_successfully','data' => $user,'success'=>true,'redirect_url' => ('/company')]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Company::find($id);
        return view('company.edit',compact('user'));
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
    public function destroy($id)
    {
        //
    }
}
