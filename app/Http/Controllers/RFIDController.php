<?php

namespace App\Http\Controllers;

use App\RFID;
use App\Entrie;
use App\dancer;
use App\groups;
use App\Trainings;
use App\payments;
use App\Fees;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RFIDController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rfids = RFID::latest()->get();
        return view('rfid.index', compact('rfids'));
    }

     /**
     * Scan RFID device via WEB enveroment
     *
     * @return \Illuminate\Http\Response
     */
    public function scan(Request $Req)
    {
        $checker = Validator::make($Req->all(), [
        	'RFID' => 'required',
        ]);
        if($checker->fails()){
        	return response()->json(['status' => 'FAILED', 'cause' => 1]);
        }
        $owner = RFID::where('RFID', $Req->RFID)->first();
        if(empty($owner))
        {
        	return response()->json(['status' => 'FAILED', 'cause' => 3]);
        }
        $ownerData = $owner->dancer;

          $ownerData->payments = payments::where('member', $ownerData->id)->get();
          $ownerData->fees = Fees::where('owner', $ownerData->id)->get();
          $ownerData->balance = calculateBalance($ownerData->payments, $ownerData->fees);

        $todaysEntrie = Entrie::where('Owner', $owner->Owner)->where('created_at', Carbon::today())->first();


        if(empty($todaysEntrie)){
        	// return response()->json(['status' => 'FAILED', 'cause' => 2]);
          $entrie = new Entrie;
          $entrie->RFID = $Req->input('RFID');
          $entrie->Owner = $owner->Owner;
          $entrie->save();

          //create new training
          // $tempTraining = new Training;
          // $tempTraining->groupID = $owner->dancer->groupID;

        }



        return response()->json(['status' => 'OK', 'owner' => $ownerData]);
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
        $validator = Validator::make($request->all(), [
            'RFID' => 'required',
            'Owner' => 'required',
        ]);
        if ($validator->fails())
        {
            return response(['status' => 'FAILED', 'cause' => 1]);
        }
        $RFID = RFID::create($request->all());
        return response(['status' => 'OK']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RFID  $rFID
     * @return \Illuminate\Http\Response
     */
    public function show(RFID $rFID)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RFID  $rFID
     * @return \Illuminate\Http\Response
     */
    public function edit(RFID $rFID)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RFID  $rFID
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RFID $rFID)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RFID  $rFID
     * @return \Illuminate\Http\Response
     */
    public function destroy(RFID $rFID)
    {
        //
    }

    /**
     * Return all entries
     *
     * @param  \App\RFID  $rFID
     * @return \Illuminate\Http\Response
     */
    public function entries()
    {
        $entries = Entrie::latest()->get();
        $members = dancer::latest()->get();
        $groups = groups::latest()->get();
        foreach ($entries as $entrie)
        {
            foreach ($members as $member)
            {

                if($entrie->Owner == $member->id)
                {
                    $entrie->firstName = $member->firstName;
                    $entrie->lastName = $member->lastName;
                    $payments = payments::where('member', $member->id)->get();
                    $fees = Fees::where('owner', $member->id)->get();
                    $entrie->balance = calculateBalance($payments, $fees);
                    $entrie->status = 'OK';
                    foreach ($groups as $group) {
                        if($member->group == $group->id)
                        {
                            $entrie->group = $group->groupName;
                        }
                    }
                }
            }

        }
        return response()->json(['entries' => $entries]);
    }
}
