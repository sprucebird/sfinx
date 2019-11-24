<?php

namespace App\Http\Controllers;

use App\Signups;
use App\dancer;
use App\groups;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SignupsController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth')->except('create', 'store');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $signups = Signups::latest()->get();
        $groups = groups::latest()->get();
        return view('signups.index', compact('signups', 'groups'));
    }

    /**
     * Display a listing of the resource in JSON formating
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAPI()
    {
        $signups = Signups::latest()->paginate(15);
        return response()->json($signups);
    }

    /**
     * Filtering signup resources by current selected city
     *
     * @return \Illuminate\Http\Response
     */
    public function showCurrentCity($cityID)
    {
        switch ($cityID) {
            case 2:
                $signups = Signups::where('city', 'Vilnius')->get();
                break;

            case 1:
                $signups = Signups::where('city', 'Klaipeda')->get();
                break;
        }

        return response()->json($signups);
    }

    /**
     * Show the form for creating a new dancer's signup request.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('public.signup', ['operation' => 0]);
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
            'firstName' => 'required|max:40',
            'lastName' => 'required|max:40',
            'birthDate' => 'required|date',
            'city' => 'required|max:9',
            'primaryPhone' => 'required',
        ]);
        $signups = signups::where('firstName', $request->input('firstName'))->where('lastName', $request->input('lastName'))->get()->count();
        if($signups != 0 || $signups != null)
        {
            return view('public.signup', ['operation' => 3]);
        }
        if ($validator->fails())
        {
            return view('public.signup', ['operation' => 2]);
        }
        $signup = Signups::create($request->all());
        return view('public.signup', ['operation' => 1]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Signups  $signups
     * @return \Illuminate\Http\Response
     */
    public function show($id, Signups $signups)
    {
        return response()->json(Signups::findOrFail($id));
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Signups  $signups
     * @return \Illuminate\Http\Response
     */
    public function edit(Signups $signups)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Signups  $signups
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Signups $signups)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Signups  $signups
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
        $sig = Signups::findOrFail($req->id);
        $sig->delete();
        return response(200);
    }



}
