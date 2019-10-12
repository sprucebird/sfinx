<?php

namespace App\Http\Controllers;

use App\Statistics;
use App\payments;
use App\Signups;
use App\paymentStatistics;
use App\fees;
use App\dancer;
use Illuminate\Http\Request;
use DB;


class StatisticsController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    /**
     * Calculate signup requests change count per given range of time
     *
     * @param  \App\UserSessions  $userSessions
     * @return \Illuminate\Http\Response
     */
    public function signupChangeCount($range)
    {
      $currentDate = date('y-m-d');
      $date = date('Y-m-d',strtotime('-30 days',strtotime($currentDate)));
      $signupsCount = null;
      switch ($range) {
        case 1: //time range: month
          $signupsCount = Signups::where( DB::raw('MONTH(created_at)'), '=', date('n') )->count();
          break;

        default:
          // code...
          break;
      }
      return response(['count' => $signupsCount, 'date' => $date]);
    }

    /**
     * Calculate signup requests change in % per given range of time
     *
     * @param  \App\UserSessions  $userSessions
     * @return \Illuminate\Http\Response
     */
    public function signupChangeProcentage($range)
    {
      $currentDate = date('y-m-d');
      $date = date('n');
      $signupsCount = null;
      switch ($range) {
        case 1: //time range: month
          $signupsCount = Signups::where( DB::raw('MONTH(created_at)'), '=', date('n') )->count();
          break;

        default:
          // code...
          break;
      }
      return response(['count' => $signupsCount, 'date' => $date]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $balance = 0;
        $payments = payments::all();
        $fees = fees::all();
        $balance = calculateBalance($payments, $fees);
        return view('stats.index', compact('balance'));
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
     * Return balances from different time periods
     *
     * @return \Illuminate\Http\Response
     */
    public function balanceHistory($range)
    {
        if($range == "studio")
        {
            $JSONedBalances = [];
            $months = [];
            $balances = paymentStatistics::where('range', 'ALL')->get();
            foreach ($balances as $balance) {
                array_push($months, convertMonthsToStrings($balance->month));
                array_push($JSONedBalances, $balance->balance);
            }
            $currentDate = date('Y-m');
            //dd($currentDate);
            $currentPayments = payments::where('created_at', 'LIKE', '%'.$currentDate.'%')->get();
            $currentFees = fees::where('created_at', 'LIKE', '%'.$currentDate.'%')->get();
            $currentBalance = calculateBalance($currentPayments, $currentFees);
            array_push($months, 'Dabar');
            array_push($JSONedBalances, $currentBalance);
            return response()->json(['months' => $months, 'balances' => $JSONedBalances]);
        }
        //
    }

    public function paymentsHistory()
    {
            $PaymentsSumMonth = 0;
            $paymentsSum = [];
            for($i = 1; $i<=12; $i++)
            {
              $date = '0' + $i;
              $monthPayments = payments::whereRaw('MONTH(created_at) = ?', [$date])->get();
              foreach ($monthPayments as $sp) {
                $PaymentsSumMonth+=$sp->price;
              }
                array_push($paymentsSum, $PaymentsSumMonth);
            }

            return response()->json($paymentsSum);
    }

    public function economyHistory($range)
    {
            $JSONed = [];
            $months = [];
            $Fees = fees::whereDate('created_at', DB::raw('CURDATE()'))->get();
            $Payments = payments::all();
            $todaysPayments = payments::whereDate('created_at', DB::raw('CURDATE()'))->get();
            $FeeSum = 0;
            $PaymentsSum = 0;
            foreach ($todaysPayments as $payment) {
                $PaymentsSum+= $payment->price;
            }
            foreach ($Fees as $fee) {
                $FeeSum+=$fee->price;
            }
            for ($i=1; $i < 13; $i++) {
                array_push($months, $i);
                array_push($JSONed, $PaymentsSum / $FeeSum);
            }
            return response()->json(['months' => $months, 'k' => $JSONed]);
    }

    /**
     * Return members count
     *
     * @return \Illuminate\Http\Response
     */
    public function membersCount()
    {
        return response()->json(['count' => dancer::count()]);
    }


    /**
     * Income history
     *
     * @return \Illuminate\Http\Response
     */
    public function income_history($range)
    {
        //1 - income in current month (30 days from request)
        //2 - ?
        $income = 0;
        $date = null;
        $currentDate = date('y-m-d');
        switch ($range) {
            case 1:
                $date = date('Y-m-d',strtotime('-0 days',strtotime($currentDate)));
                $payments = payments::where( DB::raw('MONTH(created_at)'), '=', date('n') )->get();
                $payments = payments::where(DB::raw('DATE(created_at)'), '=', $date)->get();
                foreach ($payments as $data) {
                    $income+= $data->price;
                }
                break;
            case 2:
                # code...
                break;
            case 3:
                # code...
                break;
            case 4:
                # code...
                break;
            case 5:
                # code...
                break;
            default:
                # code...
                break;
        }
        return response()->json(['income' => $income, 'date' => $date]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Statistics  $statistics
     * @return \Illuminate\Http\Response
     */
    public function show(Statistics $statistics)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Statistics  $statistics
     * @return \Illuminate\Http\Response
     */
    public function edit(Statistics $statistics)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Statistics  $statistics
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Statistics $statistics)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Statistics  $statistics
     * @return \Illuminate\Http\Response
     */
    public function destroy(Statistics $statistics)
    {
        //
    }
}
