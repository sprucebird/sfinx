<?php

namespace App\Http\Controllers;

use App\RFID;
use App\Entrie;
use App\dancer;
use App\groups;
use App\Trainings;
use App\payments;
use App\Setting;
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

    public function show_trainings(Request $req)
    {
      $trainings = Trainings::with('group')->with('dancers')->orderBy('created_at', 'desc')->get();
      $inactive = Setting::where('name', 'inactive')->first()->value_int;
      $attend = 0.00;
      $nonActive = [
          "first" => dancer::where('lastVisited', '<', Carbon::today()->subDays($inactive))->orwhere('lastVisited', NULL)->count(),
          "second" => dancer::all()->count()
      ];
      $totalCount = 0;
      $neg = 0;

      foreach($trainings as $t)
      {
        $t->created_at = $t->created_at->format('Y-m-d');
        $t->was = number_format($t->dancers->count() / $t->group->members->count()  * 100, 2, ',', '');
        $t->not = $t->group->members->count() - $t->dancers->count();
        $t->not_in_p = number_format(100 - ($t->dancers->count() / $t->group->members->count()  * 100), 2, ",", "");

        $tmp = $t->dancers->count() / $t->group->members->count();
        if($tmp < 0.3) $neg++;
        else $attend += $tmp;
        // $nonActive += $t->not;
        $totalCount += $t->group->members->count();

        $t->today = ($t->created_at->format("Y-m-d") == Carbon::today()->timezone("Europe/Vilnius")->format("Y-m-d") ? true : false);
      }
      if($attend != 0) $attend /= ($trainings->count() - $neg)/100;
      $attend = number_format($attend, 2);
      $pgtr = collect($trainings);
      // $nonActive = ($nonActive > 0 ? $nonActive / $totalCount * 100 : 0);
      // $nonActive = number_format($nonActive, 2);
      return response()->json(['status' => 'OK', 'trainings' => $pgtr->forPage($req->page, 15), 'attend' => $attend, 'nonActive' => $nonActive, 'inactive' => $inactive]);
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

        // if($Req->RFID == 'none')
        // {
        //   $owner = dancer::where('id', $Req->id)->first();
        //   $entrie = new Entrie;
        //   $entrie->RFID = 000111;
        //   $entrie->Owner = $owner->Owner;
        //   $entrie->save();
        //   return response()->json(['status' => 'OK']);
        // }
        if($checker->fails()){
        	return response()->json(['status' => 'FAILED', 'cause' => 1]);
        }
        $owner = RFID::where('RFID', $Req->RFID)->first();
        if(empty($owner))
        {
        	return response()->json(['status' => 'FAILED', 'cause' => 3]);
        }
        $ownerData = $owner->dancer;
        if(empty($Req->scanOnly)) {
          $owner->dancer->lastVisited = Carbon::today()->timezone("Europe/Vilnius");
          $owner->dancer->save();
        }
          $ownerData->payments = payments::where('member', $ownerData->id)->get();
          $ownerData->fees = Fees::where('owner', $ownerData->id)->get();
          $ownerData->balance = calculateBalance($ownerData->payments, $ownerData->fees);

        $todaysEntrie = Entrie::where('Owner', $owner->Owner)->where('created_at', 'like', '%'.Carbon::today()->format('Y-m-d').'%')->first();


        if(empty($todaysEntrie) && empty($Req->scanOnly)){
        	// return response()->json(['status' => 'FAILED', 'cause' => 2]);
          $entrie = new Entrie;
          $entrie->RFID = $Req->input('RFID');
          $entrie->Owner = $owner->Owner;
          $entrie->save();


          if($ownerData->group != 0) {
              $today = Carbon::now()->timezone('Europe/Vilnius')->format('Y-m-d');
              $train = $ownerData->currentGroup->trainings->filter(function($item) use ($today) {
                return false !== stristr($item->created_at, $today);
              });

              if($train->count() > 0) $train->first()->dancers()->attach($ownerData->id);
              else {
                $tr = new Trainings();
                $tr->groupID = $ownerData->group;
                $tr->start = null;
                $tr->save();
                $tr->dancers()->attach($ownerData->id);
                $train = $tr;
              }
              if($train->first()->start == null && $train->first()->dancers->count() >= (int) floor(0.333 * $ownerData->currentGroup->members->count())) {
                $n = Carbon::now()->timezone('Europe/Vilnius');
                $train->first()->start = $n->format('H:i:s');
                $train->first()->end = $n->addHours(1)->format('H:i:s');
                $train->first()->save();
              }
          }

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

    public function gonerList(Request $req) {
      $tr = Trainings::where("id", $req->id)->first();
      $all = $tr->group->members->pluck("id");
      $t = $tr->dancers->pluck("id");
      $diff = $all->diff($t);
      foreach($diff as $key => $d) {
        $diff[$key] = $tr->group->members->where("id", $d)->first();
        $diff[$key]->groupName = $tr->group->groupName;
        $diff[$key]->rate = number_format(dancer::where("id", $d)->first()->trainings->count() / $tr->group->trainings->count() * 100, 2);
      }

      return response()->json(["goners" => $diff]);
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
        $entries = Entrie::latest()->paginate(15);
        $members = dancer::latest()->get();
        $groups = groups::latest()->get();
        $entoday = 0;
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
                    $entrie->today = (Carbon::parse($entrie->created_at)->format("Y-m-d") == Carbon::today()->format("Y-m-d") ? true : false);
                    $entoday += ($entrie->today ? 1 : 0);
                    foreach ($groups as $group) {
                        if($member->group == $group->id)
                        {
                            $entrie->group = $group->groupName;
                        }
                    }
                }
            }

        }
        return response()->json(['entries' => $entries, 'today' => $entoday]);
    }
}
