<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DeliveryListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = DB::table('delivery_fee_list')->get();

            return response()->json($data, 200);

        } 
        catch (\Exception $e) {
            return response()->json("Data tidak di temukan");
        }
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

    public function countDeliveryFee(Request $request)
    {
        $weight = $request->input('weight');
        $deliv_fee = DB::table('delivery_fee_list')->get();
        // dd($deliv_fee);
        foreach($deliv_fee as $val){
            if($weight > $val->from_weight && $weight <= $val->to_weight){
                return response()->json(['delivery_fee' => intval($val->price)], 200);
            }
        }
    }
}
