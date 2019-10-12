<?php

use App\dancer;
use App\Signups;
use App\RFID;
use App\payments;
use App\Fees;

function deleteSignup($signupID)
{
	// 1 - operation success
	//2 - operation failed
	try
	{
		$signupRequest = Signups::where('id', $signupID)->delete();
		return 1;
	}
	catch(Exception $e)
	{
		return 0;
	}
}


function deleteMember($ID)
{
	// 1 - operation success
	//2 - operation failed
	try
	{
		$memberRequest = dancer::where('id', $ID)->delete();
		return 1;
	}
	catch(Exception $e)
	{
		return 0;
	}
}

	/**
     * Calculate balance when Payments and Fees given
     *
     * 1 - ERROR (user has one than one RFID's assigned), 0 - CLEAR (user has one RFID assigned)
     *
     * @return \Illuminate\Http\Response
     */
	function calculateBalance($memberPayments, $memberFees)
	{
			$balance = 0;
			$paymentSum = 0;
			$feesSum = 0;
			foreach ($memberPayments as $singlePayment) {
				# code...
				$paymentSum+=$singlePayment->price;
			}
			foreach ($memberFees as $Fee) {
				$feesSum+=$Fee->price;
			}
			$balance = $paymentSum - $feesSum;
			return $balance;
	}

	function calculateBalance_id($user_id)
	{
		$balance = 0;
		$paymentSum = 0;
		$feesSum = 0;
		$memberPayments = payments::where('member', $user_id)->get();
		$memberFees = Fees::where('owner', $user_id)->get();
		foreach ($memberPayments as $singlePayment) {
			$paymentSum+=$singlePayment->price;
		}
		foreach ($memberFees as $Fee) {
			$feesSum+=$Fee->price;
		}
		$balance = $paymentSum - $feesSum;
		return $balance;

	}

	/**
     * Track if more than one RFID is assigned to member
     *
     * 1 - ERROR (user has one than one RFID's assigned), 0 - CLEAR (user has one RFID assigned)
     *
     * @return \Illuminate\Http\Response
     */
    function MoreThanOneRFID($ID)
    {
    	$RFID = RFID::where('Owner', $ID)->get();
    	if (count($RFID) > 1){
    		return 1;
    	}else{
    		return 0;
    	}
    }

    /**
     * Convert months in integers to strings
     *
     * 1 - ERROR (user has one than one RFID's assigned), 0 - CLEAR (user has one RFID assigned)
     *
     * @return \Illuminate\Http\Response
     */
    function convertMonthsToStrings($month)
    {
    	switch ($month) {
    		case 1:
    			return 'Sausis';
    			break;
    	 	case 2:
    			return 'Vasaris';
    			break;
    		case 3:
    			return 'Kovas';
    			break;
    		case 4:
    			return 'Balandis';
    			break;
    		case 5:
    			return 'Geguze';
    			break;
    		case 6:
    			return 'Birzelis';
    			break;
    		case 7:
    			return 'Liepa';
    			break;
    		case 8:
    			return 'Rugpjutis';
    			break;
    		case 9:
    			return 'Rugsejis';
    			break;
    		case 10:
    			return 'Spalis';
    			break;
    		case 11:
    			return 'Lapkritis';
    			break;
    		case 12:
    			return 'Gruodis';
    			break;

    		default:
    			# code...
    			break;
    	}
    }
