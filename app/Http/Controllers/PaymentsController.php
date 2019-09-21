<?php

namespace App\Http\Controllers;

use App\payments;
use App\dancer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentsController extends Controller
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
        $payments = payments::latest()->get();
        $members = dancer::latest()->get();
        foreach ($members as $member) {
            foreach ($payments as $payment) {
                if($member->id == $payment->member)
                {
                    $payment->firstName = $member->firstName;
                    $payment->lastName = $member->lastName;
                    // $payment->leader = $member->currentGroup->leader;
                }
            }
        }
        return response()->json($payments);
        //return view('payments.index', compact('payments'));
        //
    }

    /**
     * Display a listing of the deptors
     *
     * @return \Illuminate\Http\Response
     */
    public function deptors()
    {
        $payments = payments::all();
        $fees = fees::all();
        $members = dancer::all();
        foreach ($members as $member) {
            foreach ($payments as $payment) {
                if($member->id == $payment->member)
                {
                    $member->income += $payment->price;
                }
            }
        }
        foreach ($members as $member) {
            foreach ($fees as $fee) {
                if($member->id == $fee->member)
                {
                    $member->outcome += $fee->price;
                }
            }
        }

        foreach ($members as $member) {
           if($member->income - $member->outcome < 0)
           {
            $member->isDeptor = true;
           }else{
            $member->isDeptor = false;
           }
        }
        return response()->json($members);
        //return view('payments.index', compact('payments'));
        //
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
            'price' => 'required',
        ]);
        if ($validator->fails())
        {
            return response()->json(['status' => 'FAILED', 'cause' => '1']);
        }
            $payment = payments::create([
                'member' => $request->input('member'),
                'price' => $request->input('price'),
            ]);

            return response(['status' => 'OK']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\payments  $payments
     * @return \Illuminate\Http\Response
     */
    public function show(payments $payments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\payments  $payments
     * @return \Illuminate\Http\Response
     */
    public function edit(payments $payments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\payments  $payments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, payments $payments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\payments  $payments
     * @return \Illuminate\Http\Response
     */
    public function destroy(payments $payments)
    {
        //
    }

    /**
     * Attach and adapt the discount for current member.
     *
     * @param  \App\payments  $payments
     * @return \Illuminate\Http\Response
     */
    public function changePaymentSettings($dancerID, Request $request)
    {
        $currentDancer = dancer::where('id', $dancerID)->first();
        $newFee = $request->input('fee');
        $VIP = $request->input('VIP');
        $currentDancer->fee = $newFee;
        $currentDancer->VIP = $VIP;
        $currentDancer->save();
        return back();
    }


}
