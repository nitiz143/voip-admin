<?php

namespace App\Http\Controllers;

use App\Models\CronJob;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use DB;

class CronJobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
        $data = CronJob::query('');
            return Datatables::of($data)


            ->addColumn('action', function($row){
                $actionBtn = '<a href="" class="delete btn btn-primary btn-sm Edit"  data-id ="'.$row->id.'">Edit</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="delete btn btn-danger btn-sm Delete"  data-id ="'.$row->id.'">Delete</a>';

                return $actionBtn;
            })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('cron_job');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!empty($request->typology_id)){
            $rules = array(
                'job_title' => 'required',
                'cron_type' => 'required',
                'success_email' => 'required',
                'error_email' => 'required',
                'job_time' => 'required',
                'job_intervel' => 'required',
                'job_day' => 'required',
                'status' => 'required',
                );
            }else{
                $rules = array(
                    'job_title' => 'required',
                    'cron_type' => 'required',
                    'success_email' => 'required',
                    'error_email' => 'required',
                    'job_time' => 'required',
                    'job_intervel' => 'required',
                    'job_day' => 'required',
                    'status' => 'required',
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
        if ($validator->fails())
        {

            return response()->json(['error'=>$validator->errors()]);
        }
        $data['job_day'] = json_encode($request->job_day);
        $data['job_title'] = $request->job_title;
        $data['cron_type'] = $request->cron_type;
        $data['success_email'] = $request->success_email;
        $data['error_email'] = $request->error_email;
        $data['gateway'] = $request->gateway;
        $data['Alert']= $request->Alert;
        $data['download_limit']= $request->download_limit;
        $data['threshold'] =$request->threshold;
        $data['job_time'] = $request->job_time;
        $data['job_intervel'] = $request->job_intervel;
        $data['start_time'] = date('Y-m-d H:i:s', strtotime($request->start_time));
        $data['status'] = $request->status;
        CronJob::updateOrCreate(['id' => $request->id],$data);

        return response()->json(['message'=>'updated_successfully','success'=>true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CronJob  $cronJob
     * @return \Illuminate\Http\Response
     */
    public function show(CronJob $cronJob)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CronJob  $cronJob
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if($request->ajax()){
            $agent = CronJob::find($request->id);
            return $agent;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CronJob  $cronJob
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CronJob $cronJob)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CronJob  $cronJob
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //dd($request->id);
        if($request->ajax()){
            CronJob::find($request->id)->delete();
            return response()->json(['msg'=>__('deleted_successfully'),'success'=>true]);
        }
    }
}
