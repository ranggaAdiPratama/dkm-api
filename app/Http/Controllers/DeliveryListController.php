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

    public function specialDeliveryFee($id)
    {
        $get = DB::table('special_region')->where('village_id',$id)->first()->delivery_fee;
        $d = DB::table('delivery_fee_list')->first()->price;
        $add =intval($get) - intval($d);
        return response()->json(intval($add));
    }

    public function specialDeliveryCount(Request $request)
    {
        $weight = intval($request->input('weight'));
        $get = DB::table('special_region')->where('village_id',$request->input('village_id'))->first()->delivery_fee;
        $add = (intval($get)*$weight);

        return response()->json(['delivery_fee' => intval($add)]);
    }

    public function specialPickupFee()
    {
        $id =auth()->user()->id;
        $g = DB::table('user_profiles')->leftJoin('special_region','special_region.village_id','user_profiles.village_id')->where('user_id',$id)->first()->pickup_fee;
        // $get = DB::table('special_region')->where('village_id',$g)->first()->pickup_fee;
        if($g !== null){
            $d = DB::table('delivery_fee_list')->first()->price;
        $add =intval($g) - intval($d);
        return response()->json(intval($add));
        }
        return response()->json(0);
    }
}
