<?php

namespace App\Http\Controllers;

use App\dancer;
use Illuminate\Support\Facades\Validator;
use App\Signups;
use App\payments;
use App\Fees;
use App\Entrie;
use App\groups;
use App\RFID;
use Illuminate\Http\Request;

class DancerController extends Controller
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
        $groups = groups::latest()->get();
        $members = dancer::latest()->get();
        foreach ($members as $m) {
          $m->payments = payments::where('member', $m->id);
          $m->fees = Fees::where('owner', $m->id);
          $m->balance = calculateBalance($m->payments, $m->fees);
        }
        return view('members.index', compact('members', 'groups'));
    }

    /**
     * Display a listing of the resource in JSON formatting.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAPI()
    {
        $groups = groups::latest()->get();
        $members = dancer::latest()->get();
        foreach ($members as $member) {
          $member->entries = Entrie::where('Owner', $member->id)->count();
          $member->payments = payments::where('member', $member->id)->get();
          $member->fees = Fees::where('owner', $member->id)->get();
          $member->balance = calculateBalance($member->payments, $member->fees);
            foreach ($groups as $group) {
                if($member->group == $group->id){
                    $member->groupName = $group->groupName;
                }
            }
        }
        return response()->json($members);
    }

        /**
     * Filtering members resources by current selected city
     *
     * @return \Illuminate\Http\Response
     */
    public function showCurrentCity($cityID)
    {
        $groups = groups::all();
        switch ($cityID) {
            case 2:
                $members = dancer::where('city', 'Vilnius')->get();
                foreach ($members as $member) {
                    foreach ($groups as $group) {
                        if($member->group == $group->id){
                            $member->groupName = $group->groupName;
                        }
                    }
                }
                break;

            case 1:
                $members = dancer::where('city', 'Klaipeda')->get();
                foreach ($members as $member) {
                    foreach ($groups as $group) {
                        if($member->group == $group->id){
                            $member->groupName = $group->groupName;
                        }
                    }
                }
                break;
            case 0:
                $members = dancer::all();
                foreach ($members as $member) {
                    foreach ($groups as $group) {
                        if($member->group == $group->id){
                            $member->groupName = $group->groupName;
                        }
                    }
                }
                break;
        }

        return response()->json($members);
    }

    public function filter(Request $req) {
        if($req->group == 0 && $req->city == "0") $dancers = dancer::all();
        else if($req->group == 0) $dancers = dancer::where('city', $req->city)->get();
        else if($req->city == "0") $dancers = dancer::where('group', $req->group)->get();
        else $dancers = dancer::where('group', $req->group)->where('city', $req->city)->get();

        foreach ($dancers as $dancer)
          $dancer->groupName = $dancer->currentGroup->groupName;

        return response()->json($dancers);
    }

    /* Filtering members resources by current selected group
     *
     * @return \Illuminate\Http\Response
     */
    public function showCurrentGroup($groupID)
    {
        $groups = groups::all();
        $members = dancer::where('group', $groupID)->get();
        foreach ($members as $member) {
            foreach ($groups as $group) {
                if($member->group == $group->id){
                    $member->groupName = $group->groupName;
                }
            }
        }
        return response()->json($members);
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
    public function signup2store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'signupID' => 'required',
            'groupID' => 'required',
        ]);
        if ($validator->fails())
        {
            return response()->json(['status' => 'FAILED', 'cause' => '1']);
        }
        $signup = Signups::where('id', $request->input('signupID'))->get();
        if(empty($signup)){return response()->json(['status' => 'FAILED', 'cause' => '2']);}
        foreach ($signup as $sign) {
            $templateDancer = dancer::where('lastName', $sign->lastName)->where('firstName', $sign->firstName)->first();
            if(isset($templateDancer)){
                return response(['status' => 'FAILED', 'cause' => '3']);
            }
            if(empty($templateDancer)){
                $dancer = dancer::create([
                'firstName' => $sign->firstName,
                'lastName' => $sign->lastName,
                'primaryPhone' => $sign->primaryPhone,
                'birthDate' => $sign->birthDate,
                'city' => $sign->city,
                'group' => $request->input('groupID'),
                ]);
                $delete = deleteSignup($sign->id);
                return response(['status' => 'OK']);
            }
        }


        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAPI(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'birthDate' => 'required',
            'primaryPhone' => 'required',
            'city' => 'required',
        ]);
        if ($validator->fails())
        {
            return response()->json(['status' => 'FAILED', 'cause' => '1']);
        }
            $templateDancer = dancer::where('lastName', $request->input('lastName'))->where('firstName', $request->input('firstName'))->first();
            if(isset($templateDancer)){
                return response(['status' => 'FAILED', 'cause' => '3']);
            }
            if(empty($templateDancer)){
                $dancer = dancer::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'primaryPhone' => $request->input('primaryPhone'),
                'birthDate' => $request->input('birthDate'),
                'city' => $request->input('city'),
                'group' => 0,
                ]);
                if(empty($request->rfid_id))
                {
                  $request->rfid_id = rand(1000, 9999);
                }
                $rf = new RFID(['RFID' => $request->rfid_id]);
                try {
                  $dancer->rfid()->save($rf);
                } catch (\Exception $e) {
                  return response(['error' => $e]);
                }

                return response(['status' => 'OK']);

                //FIX UNIQUE RFIDS!!!!!!!!!
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\dancer  $dancer
     * @return \Illuminate\Http\Response
     */
    public function showAPI($dancerID)
    {
      $errors = [];
      //Check if user has warnings

      // if(MoreThanOneRFID($dancerID) == 1){
      //   $errors[0] = 1;
      // }else{
      //   $errors[0] = 0;
      // }
      $groups = groups::all();
      $dancer = dancer::where('id', $dancerID)->first();
      $dancer->rfid_id = $dancer->rfid->RFID;
      $payments = payments::where('member', $dancerID)->get();
      $fees = fees::where('owner', $dancerID)->get();
      $balance = calculateBalance($payments, $fees);
      // $payments = payments::where('member', $dancerID)->get();
      // $fees = fees::where('owner', $dancerID)->get();
      // $balance = calculateBalance($payments, $fees);
      return response()->json(['member' => $dancer, 'payments' => $payments, 'balance' => $balance]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\dancer  $dancer
     * @return \Illuminate\Http\Response
     */
    public function edit($dancerID)
    {
        $errors = [];
        //Check if user has warnings
        if(MoreThanOneRFID($dancerID) == 1){
            $errors[0] = 1;
        }else{
            $errors[0] = 0;
        }
        $groups = groups::all();
        $dancer = dancer::where('id', $dancerID)->get();
        $payments = payments::where('member', $dancerID)->get();
        $fees = fees::where('owner', $dancerID)->get();
        $balance = calculateBalance($payments, $fees);

        return view('members.edit', compact('dancer', 'groups', 'balance', 'payments', 'errors'));
    }

    /**
     * Change group of selected member
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\dancer  $dancer
     * @return \Illuminate\Http\Response
     */
    public function changeGroup($id, Request $request, dancer $dancer)
    {
        $member = dancer::find($id);
        $member->group = $request->input('groupID');
        $member->save();
        return response()->json(['status' => 'OK']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\dancer  $dancer
     * @return \Illuminate\Http\Response
     */
    // public function update($dancerID, Request $request, dancer $dancer)
    // {
    //     $member = dancer::find($dancerID);
    //     $member->firstName = $request->input('firstName');
    //     $member->lastName = $request->input('lastName');
    //     $member->birthDate = $request->input('birthDate');
    //     $member->primaryPhone = $request->input('primaryPhone');
    //     $member->group = $request->input('group');
    //     $member->save();
    //     return back();
    // }

    public function updateAPI(Request $req) {

      //ATTTENTION!!!!!!!!!!!!!!!!!!!!!
      // VALIDATOR REQUIRED!!!!!!!!!
      $member = dancer::find($req->id);
      $member->firstName = $req->firstName;
      $member->lastName = $req->lastName;
      $member->primaryPhone = $req->primaryPhone;
      $member->secondaryPhone = $req->secondaryPhone;
      $member->email = $req->email;
      $member->instagram = $req->instagram;
      $member->facebook = $req->facebook;
      $member->fee = $req->fee;
      $member->birthDate = $req->birthDate;
      $member->description = $req->description;
      $member->city = $req->city;
      $member->save();
      //
      if($member->rfid == null) {
        $rf = new RFID(['RFID' => $req->rfid_id]);
        $member->rfid()->save($rf);
      }
      else {
        $member->rfid->RFID = $req->rfid_id;
        $member->push();
      }
      return response()->json(["status" => "OK"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\dancer  $dancer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
        try{
            $dancer = dancer::where('id', $req->id)->first();
            // $dancer->rfid->delete();
            $dancer->delete();
        }catch(exception $e){
          return $dancer;
            return response(['status' => 'FAILED', 'error' => $e]);
        }
        return response(['status' => 'OK']);
    }
}
