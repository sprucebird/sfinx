<?php

namespace App\Http\Controllers;

use App\groups;
use App\dancer;
use App\payments;
use App\Fees;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupsController extends Controller
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
        $groups = groups::withCount('members')->latest()->get();
        return view('groups.index', compact('groups'));
    }

    /**
     * Display a listing of the resource in JSON formatting.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAPI()
    {
        $groups = groups::withCount('members')->latest()->get();
        // $members = dancer::all();
        // $fees = Fees::all();
        // $payments = payments::all();
        // foreach ($groups as $group) {
        //     foreach ($members as $member) {
        //         if($member->group === $group->id){
        //             foreach ($fees as $fee) {
        //                 if($fee->Owner == $member->id){
        //                     $group->feeSUM += $fee->price;
        //                 }
        //             }
        //             foreach ($payments as $payment) {
        //                 if($member->id == $payment->Owner){
        //                     $group->paymentSUM += $payment->price;
        //                 }
        //             }
        //         }
        //     }
        //     $group->paymentRate = $group->paymentSUM / $group->feeSUM;
        // }
        return response()->json($groups);
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
        $validator = Validator::make($request->all(), [
            'groupName' => 'required|max:50',
        ]);
        if ($validator->fails()){
            return response()->json(['status' => 'FAILED']);
        }
        $group = groups::create([
            'groupName' => $request->input('groupName'),
            'description' => $request->input('description'),
            'leader' => $request->input('leader'),
            'level' => $request->input('level'),
        ]);
        return response(['status' => 'OK']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\groups  $groups
     * @return \Illuminate\Http\Response
     */
    public function show($id, groups $groups)
    {
      return response()->json(
        [
          'group' => groups::findOrFail($id),
          'members' => dancer::where('group', $id)->get(),
        ]
      );
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\groups  $groups
     * @return \Illuminate\Http\Response
     */
    public function edit($groupID)
    {
        $groups = groups::where('id', $groupID)->get();
        return view('groups.edit', compact('groups'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\groups  $groups
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $groups = groups::find($id);
        $groups->groupName = $request->input('groupName');
        $groups->leader = $request->input('leader');
        $groups->description = $request->input('description');
        $groups->level = $request->input('level');
        $groups->save();
        return response()->json(
          ['status' => 'OK']
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\groups  $groups
     * @return \Illuminate\Http\Response
     */
    public function destroy(groups $groups)
    {
        //
    }
    /**
     * Show members of given group
     *
     * @param  \App\Signups  $signups
     * @return \Illuminate\Http\Response
     */
    public function members($id)
    {
        $members = dancer::where('group', $id)->latest()->get();
        $groupName = groups::where('id', $id)->select('groupName')->first();
        return view('groups.members', compact('members', 'groupName'));
    }
}
