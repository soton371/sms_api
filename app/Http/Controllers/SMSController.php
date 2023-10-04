<?php

namespace App\Http\Controllers;

use App\Models\PendingSMS;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SMSController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pending_sms($device_id)
    {
        $pending_sms = PendingSMS::query()->where('status','pending')->where('device_id',$device_id)->get();

        return response()->json([
            'data'          => $pending_sms,
            'status'        => 1,
            'message'       => "Success"
        ]);
    }


    public function deliver_sms($device_id)
    {
        $deliver_sms = PendingSMS::query()->where('status','deliver')->where('device_id',$device_id)->get();

        return response()->json([
            'data'          => $deliver_sms,
            'status'        => 1,
            'message'       => "Success"
        ]);
    }

    public function error_sms($device_id)
    {
        $error_sms = PendingSMS::query()->where('status','error')->where('device_id',$device_id)->get();

        return response()->json([
            'data'          => $error_sms,
            'status'        => 1,
            'message'       => "Success"
        ]);
    }



    public function calender($device_id)
    {
        $counder = DB::table('pending_s_m_s')
                    ->select('date', 
                    DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_count'),
                    DB::raw('SUM(CASE WHEN status = "deliver" THEN 1 ELSE 0 END) as delivery_count'),
                    DB::raw('SUM(CASE WHEN status = "error" THEN 1 ELSE 0 END) as failed_count'))
                    ->where('device_id',$device_id)
                    ->groupBy('date')
                    ->get();

        return response()->json([
            'counter'         => $counder,
            'status'          => 1,
            'message'         => "Success"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Parse the formatted datetime
        $datetime = Carbon::createFromFormat('d-m-Y H:i', $request->scheduled_at);

        // Ensure the datetime is in the future
        if ($datetime <= Carbon::now()) {
            return response()->json([
                'status'        => 0,
                'message'       => "Please choose a future date and time for scheduling."
            ]);
        }

        $dateTimeString = $request->scheduled_at;
        $dateTime = DateTime::createFromFormat('d-m-Y H:i', $dateTimeString);
        $date = $dateTime->format('d-m-Y');

        $sms = PendingSMS::create([
            'device_id'         => $request->device_id,
            'name'              => $request->name,
            'message'           => $request->message,
            'scheduled_at'      => $request->scheduled_at,
            'phones'            => $request->phones,
            'date'              => $date,
            'status'            => "pending",
        ]);

        return response()->json([
            'data'          => $sms,
            'status'        => 1,
            'message'       => "Success"
        ]);

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
        $dateTimeString = $request->scheduled_at;
        $dateTime = DateTime::createFromFormat('d-m-Y H:i', $dateTimeString);
        $date = $dateTime->format('d-m-Y');
        $data = PendingSMS::query()->find($id);
        // Parse the formatted datetime
        $datetime = Carbon::createFromFormat('d-m-Y H:i', $request->scheduled_at);
        
        // Ensure the datetime is in the future
        if ($datetime <= Carbon::now()) {
            return response()->json([
                'status'        => 0,
                'message'       => "Please choose a future date and time for scheduling."
            ]);
        }

        $update = PendingSMS::updateOrCreate([
            'id'    => $id
        ], [
            'device_id'         => $data->device_id,
            'name'              => $request->name,
            'message'           => $request->message,
            'scheduled_at'      => $request->scheduled_at,
            'phones'            => $request->phones,
            'date'              => $date,
            'status'            => "pending",
        ]);


        return response()->json([
            'data'          => $update,
            'status'        => 1,
            'message'       => "Success"
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = PendingSMS::query()->find($id);
        $delete->delete();

        return response()->json([
            'status'        => 1,
            'message'       => "Success"
        ]);
    }






}
