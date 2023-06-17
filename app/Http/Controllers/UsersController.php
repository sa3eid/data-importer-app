<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\UserRepository;

class UsersController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {

        $statusCode = $request->query('statusCode');
        $currency = $request->query('currency');
        $amountRange = $request->query('amountRange');
        $dateRange = $request->query('dateRange');

        // Split the amountRange into min and max values
        $amountRangeArray = explode(',', $amountRange);

        if(count($amountRangeArray) != 2 && isset($amountRange)){
            return response()->json([
                'error' => 'min and max range amounts must be specified'
            ], 422);
        }
        $minAmount = $amountRangeArray[0] ?? null;
        $maxAmount = $amountRangeArray[1] ?? null;

        if(isset($minAmount) && isset($maxAmount)){
            if($maxAmount <= $minAmount){
                return response()->json([
                    'error' => 'max amount must be greater than min amount'
                ], 422);
            }
        }


        // Split the dateRange into min and max values
        $dateRangeArray = explode(',', $dateRange);

        if(count($dateRangeArray) != 2 && isset($dateRange)){
            return response()->json([
                'error' => 'min and max dates must be specified'
            ], 422);
        }
        $minDate = $dateRangeArray[0] ?? null;
        $maxDate = $dateRangeArray[1] ?? null;

        if(isset($minDate) && isset($maxDate)){
            if(strtotime($maxDate) <= strtotime($minDate)){
                return response()->json([
                    'error' => 'max date must be greater than min date'
                ], 422);
            }
        }  

        $users = $this->userRepository->getUsersWithTransactions($statusCode, $currency, $minAmount, $maxAmount, $minDate, $maxDate);
        return response()->json($users);
    }
}
