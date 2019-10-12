<?php

namespace App\Console;
use App\Fees;
use App\payments;
use App\paymentStatistics;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function (){
            $dancers = \App\dancer::all();
            foreach ($dancers as $dancer) {
                $fee = Fees::create([
                    'price' => $dancer->fee,
                    'owner' => $dancer->id,
                ]);
            }
        });

        $schedule->call(function (){
            $balance = 0;
            $payments = payments::all();
            $paymentSum = 0;
            foreach ($payments as $payment) {
                $paymentSum+=$payment->price;
            }
            $fees = Fees::all();
            $feeSum = 0;
            foreach ($fees as $fee) {
                $feeSum+=$fee->price;
            }
            $fees = Fees::all();
            $balance = calculateBalance($payments, $fees);
            $paymentStatistics = new paymentStatistics;
            $paymentStatistics->range = 'ALL';
            $paymentStatistics->balance = $balance;
            $paymentStatistics->payments = $paymentSum;
            $paymentStatistics->fees = $feeSum;
            $paymentStatistics->month = date('m');
            $paymentStatistics->year = date('y');
            $paymentStatistics->save();
        });
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
