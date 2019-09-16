<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Signups;
use App\dancer;
use App\groups;
use App\update;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }


    public function newVersion(Request $req)
    {
      $update = new update;
      $update->version = $req->version;
      $update->type = $req->type;
      $update->part = $req->part;
      $update->description = $req->description;
      $update->problemsSolved = $req->problemsSolved;
      $update->improved = $req->improved;
      $update->developer = $req->developer;
      $update->save();
      return back();
    }
    public function versionInfo()
    {
      $updates = update::all();
      return view('system.index', compact('updates'));
    }

    public function signupGenerator($amount){
        factory(Signups::class, 10)->create();
        return response('OK');
    }

    public function searchAll(Request $req) {
      $q = $req->message;
      $signups = Signups::where('firstName', 'LIKE', '%'.$q.'%')->orwhere('lastName', 'LIKE', '%'.$q.'%')->orwhere('city', 'LIKE', '%'.$q.'%')->get();
      $members = dancer::where('firstName', 'LIKE', '%'.$q.'%')->orwhere('lastName', 'LIKE', '%'.$q.'%')->orwhere('city', 'LIKE', '%'.$q.'%')->orwhere('id', 'LIKE', '%'.$q.'%')->get();
      $groups = groups::where('groupName', 'LIKE', '%'.$q.'%')->orwhere('id', 'LIKE', '%'.$q.'%')->get();
      $msg = "";
      foreach($signups as $signup) {
        $msg .= "<h1>".$signup->firstName."</h1> ".$signup->lastName."<br/>";
        break;
      }

      foreach($members as $member) {
        $msg .= "<h1>".$member->firstName."</h1> ".$member->lastName."<br/>";
        break;
      }
      foreach($groups as $group) {
        $msg .= "<h1>".$group->groupName."</h1><br/>";
        break;
      }
      if(count($members)==0 && count($signups)==0 && count($groups) == 0) return response()->json(['status' => 'error', 'message' => 'null']);
      return response()->json(['status' => 'success', 'message' => $msg]);
    }
    public function search(Request $req) {
      $q = $req->input('searchQ');
      $signups = Signups::where('firstName', 'LIKE', '%'.$q.'%')->orwhere('lastName', 'LIKE', '%'.$q.'%')->orwhere('city', 'LIKE', '%'.$q.'%')->get();
      $members = dancer::where('firstName', 'LIKE', '%'.$q.'%')->orwhere('lastName', 'LIKE', '%'.$q.'%')->orwhere('city', 'LIKE', '%'.$q.'%')->orwhere('id', 'LIKE', '%'.$q.'%')->get();
      $groups = groups::where('groupName', 'LIKE', '%'.$q.'%')->orwhere('id', 'LIKE', '%'.$q.'%')->get();
      $results = array_merge($groups->toArray(), $members->toArray(), $signups->toArray());
      $msg = "";
      if(count($members)==0 && count($signups)==0 && count($groups) == 0) return response()->json(['status' => 'error', 'message' => 'null']);
      return response()->json($results);
    }
}
