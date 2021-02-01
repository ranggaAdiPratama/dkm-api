<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Wallet;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $driver = Driver::rightJoin('users' ,'drivers.user_id','users.id')
                        ->leftJoin('driver_category as dc' ,'drivers.driver_category_id','dc.id')
                        ->select(
                            'users.id',
                            'users.name',
                            'users.email',
                            'dc.category',
                            'drivers.total_orders',
                            'drivers.available'
                            )
                        ->where('users.role_id',3)
                        ->get();
        $saldo = Wallet::join('wallet_transaction as wt','wallet.id','wt.wallet_id');
                         

        

        return response()->json([
            
            'driver' => $driver ,
            'saldo'  => $saldo ,
            'debit' =>$debit ,
            'credit' => $credit
        
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
}
