<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RateUpload;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Validator;
use App\Models\RateTable;
use App\Models\VendorDownloadProcess;
use App\Models\Trunk;
use App\Models\Client;
use Auth;
use App\Models\VendorTrunk;
use Yajra\DataTables\DataTables;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::where("Vendor", "=",1)->get();
        $rate_table = RateTable::select('*')->get();
        return view('rate.rate_upload',compact('clients','rate_table'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $table = '';
        if($request->id){
            $table = RateTable::find($request->id);
        }
        $trunks = Trunk::where('status', 1)->get();
        return view('rate.rate-table.crateTable',compact('table','trunks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array('csv_file' => 'required|mimes:csv,xlsx,txt');

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            $response = \Response::json([
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()
                ]);
            return $response;
        }
        $path = $request->file('csv_file')->store('csv-files');
        $users = (new FastExcel)->import(storage_path('app/' .$path), function ($line) {
                $validator = Validator::make($line, [
                'DESTINATION' => 'required',
                'DESTINATION CODE' => 'required',
                'EFFECTIVE DATE' => 'required',
                'RATES (US$)' => 'required',
                'BILLING INCREMENT' => 'required',
            ])->validate();
            $check_if_exist = RateUpload::whereDestinationCode($line['DESTINATION CODE'])->first();
            if(empty($check_if_exist)){
                return RateUpload::create([
                    'destination' => $line['DESTINATION'],
                    'destination_code' => $line['DESTINATION CODE'],
                    'effective_date' => \Carbon\Carbon::parse($line['EFFECTIVE DATE'])->format('d-m-y') ,
                    'rate' => $line['RATES (US$)'],
                    'billing_increment' => $line['BILLING INCREMENT'],
                    'deletion_date' => !empty($line['DELETION DATE']) ? \Carbon\Carbon::parse($line['DELETION DATE'])->format('d-m-y') : NULL,

                ]);
                
            }
         });
        if(!$users->isEmpty()){
            if(!empty($request->Vendor)){
                $array = explode(' ', $request->Trunk);
                $client =  Client::where('id',$request->Vendor)->first();
                    VendorDownloadProcess::create([
                        'name'=>$client->name,
                        'client_id'=>$request->Vendor,
                        'trunks'=> json_encode($array),
                        'created_by'=>Auth::user()->id ?? '',
                        'type'=>'Upload',
                    ]);
            }  
        }  
          
        return response()->json(['message' =>'File Imported Successfully']);

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
        //
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
        $table = RateTable::find($request->id);
        if(!empty($table)){
            if($table->delete()){
                return response()->json(['message' =>'Data Deleted Successfully']);
            }
        }

    }

    public function rateIndex(Request $request)
    {
        if ($request->ajax()) {
            $data = RateTable::all();
            return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('updated_at',function($row){
                $updated_at = \Carbon\Carbon::parse($row->updated_at)->format('Y-m-d h:m:s');
                return $updated_at;
            })
            ->editColumn('codeDeckId',function($row){
                if($row->codeDeckId == 3){
                    $codeDeckId = "Customer Codedeck";
                }
                if($row->codeDeckId == 2){
                    $codeDeckId = "Customer Codes";
                }
                if($row->codeDeckId == 1){
                    $codeDeckId = "Vendor Codes";
                }

                return $codeDeckId;
            })
            ->editColumn('trunkId',function($row){
                $trunks = Trunk::where('id',$row->trunkId)->first();
                return $trunks->title;
            })
            ->addColumn('action', function($row){

                $btn = '<a href="#" class="delete btn btn-primary btn-sm editTable"  data-id ="'.$row->id.'">Edit</a>&nbsp;&nbsp;
                <a href="javascript:void(0)" class="delete btn btn-danger btn-sm Delete"  data-id ="'.$row->id.'">Delete</a>';

                    return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('rate.rate-table.index');
    }

    public function tableStore(Request $request)
    {
        $request->validate([
            'codeDeckId' => 'required',
            'trunkId' => 'required',
            'currency' => 'required',
            'name' => 'required',
            'RoundChargedAmount' => 'required',
        ]);
        if(RateTable::updateOrCreate(['id' => $request->id], $request->all())){
            return response()->json(['message' =>'Data Saved Successfully']);
        }
    }

    public function get_trunk(Request $request){
        $vendor = VendorTrunk::where('status', 1)->where('vendor_id', $request->id)->get();
        $trunks=array();
        foreach ($vendor  as $key => $value) {
            $trunks[] = Trunk::where('id',$value->trunkid)->first();
        }
        return response()->json(['data' => $trunks ,'success'=>true]);
    }
}
