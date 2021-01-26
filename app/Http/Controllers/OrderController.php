<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order\Order;
use DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $data['order'] = DB::select('
                        SELECT 
                        orders.id, 
                        orders.user_id, 
                        orders.order_detail_id, 
                        orders.tax, 
                        orders.delivery_fee, 
                        orders.hint, 
                        order_statuses.`status`, 
                        orders.active, 
                        orders.driver_id, 
                        orders.delivery_address_id, 
                        orders.payment_id, 
                        orders.created_at
                        FROM
                        orders
                        LEFT JOIN
                        order_statuses
                        ON 
                        orders.order_statuses_id = order_statuses.id ;
                        ');

        return response()->json($data);
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
        $data['order_status'] = Order::find($id)->orderStatus;
        $data['order'] = Order::find($id);

        return response()->json($data);
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
