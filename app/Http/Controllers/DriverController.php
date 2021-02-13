<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Wallet;
use DB;

class DriverController extends Controller
{
 
    /**
     * Display a listing of the resource.
     *  
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getDriver = DB::select(
                    'SELECT
                    users.id, 
                    users.`name`, 
                    users.email, 
                    driver_category.category, 
                    drivers.total_orders, 
                    wallet.begin_balance, 
                    sum(wallet_transaction.debit) as debit, 
                    sum(wallet_transaction.credit) as credit
                    FROM
                    users
                    INNER JOIN
                    drivers
                    ON 
                        users.id = drivers.user_id
                    INNER JOIN
                    driver_category
                    ON 
                        drivers.driver_category_id = driver_category.id
                    LEFT JOIN
                    wallet
                    ON 
                        users.id = wallet.user_id
                    INNER JOIN
                    db_dkm.wallet_transaction
                    ON 
                        wallet.id = wallet_transaction.wallet_id
                    where users.role_id = 3
                    
                    GROUP BY 
                    users.id,
                    users.`name`, 
                    users.email, 
                    drivers.total_orders,
                    driver_category.category, 
                    wallet.begin_balance 
                    ');
        $driver =[];
       if(!empty($getDriver)){
           foreach ($getDriver as $val) {
               $arr = array(
                   'id' => $val->id,
                   'name' => $val->name,
                   'email' => $val->email,
                   'total_order' => $val->total_orders,
                   'category' => $val->category,
                   'saldo' => $val->begin_balance + $val->debit + $val->credit
               );
               array_push($driver,$arr);
           }
       }

        return response()->json([
            
            'driver' => $driver ,
        
            ], 200);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function driverWallet()
    {
        $getData = DB::table('driver_wallet')->get();
        $data = array();
        if(!empty($getData)){
            foreach($getData as $val){
                $arr = array(
                    'driver_id' => $val->id,
                    'driver_name' => $val->name,
                    'begin_balance' =>$val->begin_balance,
                    'amount' => $val->amount,
                    'ending_balance'=> $val->amount + $val->begin_balance
                );
                array_push($data,$arr);
            }
            return response()->json(['data' => $data]);
        }

        return response()->json('Data Not Found', 204);
    }

    public function driverWalletDetail($id)
    {
        //Cek Saldo Awal
        $begin_balance_check = DB::select('SELECT begin_balance FROM wallet where CAST(created_at AS DATE) = CURRENT_DATE');
        // dd($begin_balance_check);
        $begin_balance = $begin_balance_check;
        $getData = DB::table('driver_wallet_detail')
                    ->where('id',$id)
                    ->get();
        $data = array();
        $data_amount = array();
        if(!empty($getData)){
            $ending_balance = [];
            foreach($getData as $val){
                $amount = intval($val->amount);
                array_push($data_amount,$amount);
                $arr = array(
                    // 'driver_id' => $val->id,
                    // 'begin_balance' =>s,
                    'amount' => intval($val->amount),
                    'description' => $val->description,
                );
                $end = $begin_balance[0]->begin_balance + $val->amount;
                array_replace(array($ending_balance),array($end));
                array_push($data,$arr);
            }

            // return(array_sum($data_amount));
            return response()->json([
                'begin_balance' => $begin_balance[0]->begin_balance,
                'data' => $data,
                'ending_balance' => $begin_balance[0]->begin_balance + array_sum($data_amount)
                ]);
        }
        return response()->json('Data Not Found', 204);
    }


}
