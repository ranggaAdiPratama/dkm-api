<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\Handler;
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
      $data = DB::select('
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


    public function pickupList()
    {
        // $id  = auth()->user()->id;
        $id = 3;
        $getData= Order::join('order_details','orders.order_detail_id','order_details.id')
                        ->select(
                            'orders.id',
                            'orders.no_order',
                            'orders.created_at',
                            'order_details.price'
                            )
                        ->where('orders.pickup_status',0)
                        ->where('orders.order_statuses_id','<',3)
                        ->where('orders.driver_id',$id)
                        ->get();
        $data = array();
        
        if (!empty($getData)){
            foreach ($getData as $key=>$val) {
                $arr = array(
                    'id' => $val->id,
                    'no_order' => 'ID#'.'000'.$val->no_order,
                    'tanggal_order' => date_format($val->created_at,"d-M-Y"),
                );

                array_push($data,$arr);
            }
        }

        return response()->json(['data' => $data], 200);
    }

    public function pickupShow($id)
    {
        $getDetail = Order::join('order_details as od','orders.order_detail_id','od.id')
                        ->join('order_statuses as os' ,'orders.order_statuses_id', 'os.id')
                        ->select(
                            'orders.id',
                            'orders.no_order',
                            'os.status',
                            'orders.created_at',
                            'od.name',
                            'od.weight',
                            'od.volume',
                            'od.price',
                            'od.photo'
                        )
                        ->where('orders.id',$id)
                        ->get();
                        
        $detail_barang = array(
            'id' => $getDetail[0]->id,
            'no_order' => $getDetail[0]->no_order,
            'status' => $getDetail[0]->status,
            'tanggal_order' => date_format($getDetail[0]->created_at,'d-M-Y'),
            'name' => $getDetail[0]->name,
            'weight' => $getDetail[0]->weight,
            'volume' => $getDetail[0]->volume,
            'price' => $getDetail[0]->price,
            'photo' => $getDetail[0]->photo
        );
        
        $detail_penerima = Order::join('order_details as od','orders.order_detail_id','od.id')
                                ->join('delivery_addresses as da', 'orders.delivery_address_id','da.id')
                                ->select(
                                    'od.receiver',
                                    'od.phone',
                                    'da.address'
                                )
                                ->where('orders.id',$id)
                                ->get();
        
        $getData = Order:: join('payments as p','orders.payment_id','p.id')
                                    ->join('order_details as od','orders.order_detail_id','od.id')
                                    ->join('payment_methods as pm' ,'p.payment_method_id','pm.id')
                                    ->join('payment_status as ps','p.status','ps.id')
                                    ->select(
                                        'pm.method',
                                        'orders.delivery_fee',
                                        'od.price',
                                        'orders.tax'
                                    )
                                    ->where('orders.id',$id)
                                    ->get();
        if(!empty($getData) && count($getData) > 0){
           $detail_total_order = array(
               'payment_method' => $getData[0]->method,
               'delivery_fee' => $getData[0]->delivery_fee,
               'price' =>$getData[0]->price,
               'tax' =>$getData[0]->tax,
               'total' => $getData[0]->delivery_fee + $getData[0]->price + $getData[0]->tax

           );
        }else{
            return response()->json('Data Not Found', 404);
        }
    
        
        $data = array(
            'detail_barang' => $detail_barang,
            'detail_penerima' => $detail_penerima[0],
            'detail_total_order' => $detail_total_order
        );
        return response()->json($data, 200);

    }

    public function pickupStatus(Request $request)
    {   
      $id = $request->input('id');
      $status = $request->input('status');
      date_default_timezone_set('Asia/Bangkok');
       if($status == 3){
           Order::where('id',$id)
                ->update([
                'pickup_status' => 1,
                'order_statuses_id' =>$status,
                'pickup_at' => date('Y-m-d H:i:s')

                ]);

            return response()->json('Data Successfully updated', 200);
       }elseif($status < 3){
        Order::where('id',$id)
        ->update([
        'order_statuses_id' =>$status,    

        ]);

        return response()->json('Data Successfully updated', 200);
       }
       
       return response()->json('Data fail to update');
    }

    public function pickupHistory()
    {
        $id  = auth()->user()->id;
        $getData= Order::join('order_details','orders.order_detail_id','order_details.id')
                        ->select(
                            'orders.id',
                            'orders.no_order',
                            'orders.updated_at'
                            )
                        ->where('orders.pickup_status',1)
                        ->where('orders.driver_id',$id)
                        ->get();
        $data = array();
        if (!empty($getData)){
            foreach ($getData as $key=>$val) {
                $date = date_create($val->pickup_at);
                $arr = array(
                    'id' => $val->id,
                    'no_order' => 'ID#'.'000'.$val->no_order,
                    'tanggal_pickup' => date_format($date,"d-M-Y"),
                );

                array_push($data,$arr);
            }
            return response()->json($data, 200); 
        }
        return response()->json('History Pickup Tidak ditemukan', 404);
        
    }


    public function deliveryList()
    {
        $id  = auth()->user()->id;
        $getData= Order::join('order_details','orders.order_detail_id','order_details.id')
                        ->select(
                            'orders.id',
                            'orders.no_order',
                            'orders.pickup_at'
                            )
                        ->where('orders.pickup_status',1)
                        ->where('orders.order_statuses_id','<',3)
                        ->where('orders.driver_id',$id)
                        ->get();
        $data = array();
        if (!empty($getData)){
            foreach ($getData as $key=>$val) {
                $date = date_create($val->pickup_at);
                $arr = array(
                    'id' => $val->id,
                    'no_order' => 'ID#'.'000'.$val->no_order,
                    'tanggal_pickup' => date_format($date, 'd-M-Y'),
                );

                array_push($data,$arr);
            }
            return response()->json($data, 200); 
        }
        return response()->json('Data Not Found', 404);
    }

    public function deliveryHistory()
    {
        $id  = auth()->user()->id;
        $getData= Order::join('order_details','orders.order_detail_id','order_details.id')
                        ->select(
                            'orders.id',
                            'orders.no_order',
                            'orders.delivered_at'
                            )
                        ->where('orders.pickup_status',1)
                        ->where('orders.order_statuses_id','=',3)
                        
                        ->where('orders.driver_id',$id)
                        ->get();

        $data = array();
        if (!empty($getData)){
            foreach ($getData as $val) {
                $date = date_create($val->delivered_at);
                $arr = array(
                    'id' => $val->id,
                    'no_order' => 'ID#'.'000'.$val->no_order,
                    'tanggal_deliver' => date_format($date , "d-M-Y H:i:s")
                );

                
                array_push($data,$arr);
            }
            return response()->json($data, 200); 
        }
        return response()->json('Data Not Found', 404);
    }

    public function deliveryShow($id)
    {
        $detail_barang = Order::join('order_details as od','orders.order_detail_id','od.id')
                        ->join('order_statuses as os' ,'orders.order_statuses_id', 'os.id')
                        ->select(
                            'orders.id',
                            'orders.no_order',
                            'os.status',
                            'orders.created_at',
                            'od.name',
                            'od.weight',
                            'od.volume',
                            'od.price',
                            'od.photo'
                        )
                        ->where('orders.id',$id)
                        ->where('orders.pickup_status',1)
                        ->get();
        
        $detail_penerima = Order::join('order_details as od','orders.order_detail_id','od.id')
                                ->join('delivery_addresses as da', 'orders.delivery_address_id','da.id')
                                ->select(
                                    'od.receiver',
                                    'od.phone',
                                    'da.address'
                                )
                                ->where('orders.id',$id)
                                ->where('orders.pickup_status',1)
                                ->get();
        
        $getData = Order:: join('payments as p','orders.payment_id','p.id')
                                    ->join('order_details as od','orders.order_detail_id','od.id')
                                    ->join('payment_methods as pm' ,'p.payment_method_id','pm.id')
                                    ->join('payment_status as ps','p.status','ps.id')
                                    ->select(
                                        'pm.method',
                                        'orders.delivery_fee',
                                        'od.price',
                                        'orders.tax'
                                    )
                                    ->where('orders.id',$id)
                                    ->where('orders.pickup_status',1)
                                    ->get();
        if(!empty($getData) && count($getData) > 0){
           $detail_total_order = array(
               'payment_method' => $getData[0]->method,
               'delivery_fee' => $getData[0]->delivery_fee,
               'price' =>$getData[0]->price,
               'tax' =>$getData[0]->tax,
               'total' => $getData[0]->delivery_fee + $getData[0]->price + $getData[0]->tax

           );
        }else{
            return response()->json('Data Not Found', 404);
        }
    
        
        $data = array(
            'detail_barang' => $detail_barang[0],
            'detail_penerima' => $detail_penerima[0],
            'detail_total_order' => $detail_total_order
        );
        return response()->json($data, 200);

    }

    public function deliveryStatus($id)
    {   
      $order = Order::find($id);
      date_default_timezone_set('Asia/Bangkok');
       if($order){
           Order::where('id',$id)
                ->update([
                'order_statuses_id' => 3,
                'delivered_at' => date('Y-m-d H:i:s')

                ]);

            return response()->json('Data Successfully updated', 200);
       }
       
       return response()->json('Data fail to update');
    }
}
