<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\Handler;
use App\Models\Order\Order;
use DB;

class OrderController extends Controller
{
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data['data_order'] = DB::table('order_detail')->where('id',$id)->get();
            $data['data_product'] = DB::select('
                                    SELECT
                                    order_details.`name`, 
                                    order_details.price, 
                                    order_details.weight, 
                                    order_details.volume, 
                                    order_details.photo, 
                                    order_details.receiver, 
                                    order_details.phone, 
                                    order_details.delivery_fee
                                    FROM
                                    orders
                                    INNER JOIN
                                    order_details
                                    ON 
                                        orders.id = order_details.orders_id
                                WHERE
                                    orders.id = '.$id.'
            ');

            return response()->json($data);

        } catch (\Throwable $th) {
            return response()->json("Maaf, Data tidak di temukan", 402);
        }
        
    }

    public function pickupList()
    {
        $id  = auth()->user()->id;
        $getOrder = DB::table('pickup_list')
                            ->where('driver_id_pickup',$id)
                            ->get();
        // return $getOrder;
        $data = array();
        
        if (!empty($getOrder)){
            foreach ($getOrder as $val) {
                $date = date_create($val->created_at);
                $arr = array(
                    'user_id' => intval($val->user_id),
                    'sender_name' => $val->sender_name,
                    'tanggal_order' => date_format($date,"d-M-Y")
                );
                array_push($data,$arr);
            }
        return response()->json(['data' => $data], 200);
        }
    }
    
    public function orderList($id)
    {
        $id_driver = auth()->user()->id;
        $getOrder = DB::table('pickup_list')
                            ->where('driver_id_pickup',$id_driver)
                            ->get();
       
        // $getData = DB::table('pickup_detail_list')
        //                 ->where('driver_id_pickup', $id)
        //                 ->get();
        $data = array();
        
        if (!empty($getOrder)){
            foreach ($getOrder as $val) {
                $date = date_create($val->created_at);
                $detail_order = DB::table('pickup_detail_list') 
                                ->where('driver_id_pickup', $id_driver)
                                ->where('user_id',$id)
                                ->get();
                $detailArr = array() ;
                $total_delivery_fee = array();
                $total_price = array();
                $total = array();
            if(!empty($detail_order)){
                foreach ($detail_order as $val) {
                    $array = array(
                    'id_order' => intval($val->id),    
                    'no_order' => 'ID#'.'000'.$val->no_order,
                    'delivery_fee' => intval($val->delivery_fee) ,
                    'name' => $val->name,
                    'price' => intval($val->price),
                    'description' => $val->description,
                    'weight' => intval($val->weight),
                    'volume' => intval($val->volume),
                    'photo' => $val->photo,
                    'receiver_name' => $val->receiver,
                    'receiver_phone' => $val->phone,
                    'method' => $val->method,
                    'status' => $val->status,
                    'deliv_address' => $val->address,
                    // 'desc_add' => $val->desc_add,
                    'latitude' => $val->latitude,
                    'longitude' => $val->longitude,
                    // 'sender_name' => $val->sender_name,
                    // 'sender_phone' => $val->sender_phone,
                    'subtotal' => $val->delivery_fee + $val->price
                    );
                    array_push($total_delivery_fee,$array['delivery_fee']);
                    array_push($total_price,$array['price']);
                    array_push($total,$array['subtotal']);
                    array_push($detailArr,$array);
                }
            }
                $arr = array(
                    'user_id' => intval($val->user_id),
                    'sender_name' => $val->sender_name,
                    'tanggal_order' => date_format($date,"d-M-Y"),
                    'sender_phone' => $val->sender_phone,
                    'sender_address' => $val->sender_address,
                    'district' => $val->district,
                    'village' => $val->village,
                    'latitude' => $val->latitude,
                    'longitude' => $val->longitude,
                    'status' => $val->status,
                    'list_orders' => $detailArr,
                    'total_deliv_fee' => array_sum($total_delivery_fee),
                    'total_price' => array_sum($total_price),
                    'grand_total' => array_sum($total)
                      
                    
                );
                array_push($data,$arr);
            }
        }

        return response()->json(['data' => $data[0]], 200);
        }

    public function orderListDetail($id)
    {
        $getBailout = DB::table('bailout')->get();
        $bailout = [];
        foreach($getBailout as $val){
            $a = array(
                'id' => intval($val->id),
                'cod_method' => $val->cod_method
            ); 
            array_push($bailout,$a);
        }
        $detail_order = DB::table('pickup_detail_list') 
        ->where('id',$id)
        ->get();

        $detailArr = array() ;
        $total_delivery_fee = array();
        $total_price = array();
        $total = array();
        foreach ($detail_order as $val) {
            $array = array(
            'id_order' => intval($val->id),    
            'no_order' => '#'.$val->no_order,
            'delivery_fee' => intval($val->delivery_fee) ,
            'name' => $val->name,
            'price' => intval($val->price),
            'description' => $val->description,
            'weight' => intval($val->weight),
            'volume' => intval($val->volume),
            'photo' => $val->photo,
            'receiver_name' => $val->receiver,
            'receiver_phone' => $val->phone,
            'id_method' => intval($val->id_method),
            'method' => $val->method,
            'status' => $val->status,
            'deliv_address' => $val->address,
            // 'desc_add' => $val->desc_add,
            'latitude' => $val->latitude,
            'longitude' => $val->longitude,
            'sender_name' => $val->sender_name,
            'sender_phone' => $val->sender_phone,
            'sender_address' => $val->sender_address,
            'subtotal' => $val->delivery_fee + $val->price
            );
   
            array_push($detailArr,$array);
        }
        return response()->json([
            'detail' => $detailArr[0],
            'bailout' => $bailout,
           
        ], 200);
    }

    public function pickupStatus(Request $request)
    {   
      $id = $request->input('id');;
      $status = $request->input('status');
      $method = $request->input('payment_method');
      $bailout = $request->input('bailout');
      date_default_timezone_set('Asia/Bangkok');
       if($status == 3){
           Order::join('payments','payments.id','orders.payment_id')
                ->where('orders.id',$id)
                ->update([
                'pickup_status' => 1,
                'payment_method_id' => $method,
                'bailout_id' => $bailout,
                'order_statuses_id' =>$status,
                'pickup_at' => date('Y-m-d H:i:s')

                ]);

            return response()->json('Data Successfully updated', 200);
       }elseif($status !== 3){
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

        $getOrder = DB::table('pickup_history_list')
                            ->where('driver_id_pickup',$id)
                            ->get();
       
        $data = array();
        
        if (!empty($getOrder)){
            foreach ($getOrder as $val) {
                $date = date_create($val->created_at);
                $detail_order = DB::table('pickup_detail_history_list') 
                                ->where('driver_id_pickup', $id)
                                ->where('user_id',$val->user_id)
                                ->get();
                $detailArr = array() ;
                $total_delivery_fee = array();
                $total_price = array();
                $total = array();
            if(!empty($detail_order)){
                foreach ($detail_order as $val) {
                    $array = array(
                    'no_order' => 'ID#'.'000'.$val->no_order,
                    'delivery_fee' => intval($val->delivery_fee) ,
                    'name' => $val->name,
                    'price' => intval($val->price),
                    'description' => $val->description,
                    'weight' => intval($val->weight),
                    'volume' => intval($val->volume),
                    'photo' => $val->photo,
                    'receiver_name' => $val->receiver,
                    'receiver_phone' => $val->phone,
                    'method' => $val->method,
                    'status' => $val->status,
                    'deliv_address' => $val->address,
                    // 'desc_add' => $val->desc_add,
                    'latitude' => $val->latitude,
                    'longitude' => $val->longitude,
                    // 'sender_name' => $val->sender_name,
                    // 'sender_phone' => $val->sender_phone,
                    'subtotal' => $val->delivery_fee + $val->price
                    );
                    array_push($total_delivery_fee,$array['delivery_fee']);
                    array_push($total_price,$array['price']);
                    array_push($total,$array['subtotal']);
                    array_push($detailArr,$array);
                }
            }
                $arr = array(
                    'sender_name' => $val->sender_name,
                    'tanggal_order' => date_format($date,"d-M-Y"),
                    'sender_phone' => $val->sender_phone,
                    'sender_address' => $val->sender_address,
                    'district' => $val->district,
                    'village' => $val->village,
                    'latitude' => $val->latitude,
                    'longitude' => $val->longitude,
                    // 'status' => $val->status,x
                    'list_orders' => $detailArr,
                    'total_deliv_fee' => array_sum($total_delivery_fee),
                    'total_price' => array_sum($total_price),
                    'grand_total' => array_sum($total)
                      
                    
                );
                array_push($data,$arr);
            }
        }

        return response()->json(['data' => $data], 200);
        
    }


    public function deliveryList()
    {
        $id  = auth()->user()->id;
        $getOrder = DB::table('delivery_list')
                            ->where('driver_id_deliver',$id)
                            ->get();
        // return $getOrder;
        $data = array();
        
        if (!empty($getOrder)){
            foreach ($getOrder as $val) {
                $date = date_create($val->created_at);
                $arr = array(
                    'user_id' => intval($val->user_id),
                    'receiver_name' => $val->receiver_name,
                    'tanggal_order' => date_format($date,"d-M-Y")
                );
                array_push($data,$arr);
            }
        return response()->json(['data' => $data], 200);
        }
    }

    public function deliveryHistory()
    {
        $id  = auth()->user()->id;

        $getOrder = DB::table('delivery_history_list')
                            ->where('driver_id_pickup',$id)
                            ->get();
       

        $data = array();
        
        if (!empty($getOrder)){
            foreach ($getOrder as $val) {
                $date = date_create($val->created_at);
                $detail_order = DB::table('delivery_detail_history_list') 
                                ->where('driver_id_deliver', $id)
                                ->where('user_id',$val->user_id)
                                ->get();
                $detailArr = array() ;
                $total_delivery_fee = array();
                $total_price = array();
                $total = array();
            if(!empty($detail_order)){
                foreach ($detail_order as $val) {
                    $array = array(
                    'no_order' => 'ID#'.'000'.$val->no_order,
                    'delivery_fee' => intval($val->delivery_fee) ,
                    'name' => $val->name,
                    'price' => intval($val->price),
                    'description' => $val->description,
                    'weight' => intval($val->weight),
                    'volume' => intval($val->volume),
                    'photo' => $val->photo,
                    'receiver_name' => $val->receiver,
                    'receiver_phone' => $val->phone,
                    'method' => $val->method,
                    'status' => $val->status,
                    'deliv_address' => $val->address,
                    // 'desc_add' => $val->desc_add,
                    'latitude' => $val->latitude,
                    'longitude' => $val->longitude,
                    // 'sender_name' => $val->sender_name,
                    // 'sender_phone' => $val->sender_phone,
                    'subtotal' => $val->delivery_fee + $val->price
                    );
                    array_push($total_delivery_fee,$array['delivery_fee']);
                    array_push($total_price,$array['price']);
                    array_push($total,$array['subtotal']);
                    array_push($detailArr,$array);
                }
            }
                $arr = array(
                    'sender_name' => $val->sender_name,
                    'tanggal_order' => date_format($date,"d-M-Y"),
                    'sender_phone' => $val->sender_phone,
                    'sender_address' => $val->sender_address,
                    'district' => $val->district,
                    'village' => $val->village,
                    'latitude' => $val->latitude,
                    'longitude' => $val->longitude,
                    // 'status' => $val->status,x
                    'list_orders' => $detailArr,
                    'total_deliv_fee' => array_sum($total_delivery_fee),
                    'total_price' => array_sum($total_price),
                    'grand_total' => array_sum($total)
                      
                    
                );
                array_push($data,$arr);
            }
        }

        return response()->json(['data' => $data], 200);
    }

    public function deliveryShow($id)
    {
        
            $getBailout = DB::table('bailout')->get();
            $bailout = [];
            foreach($getBailout as $val){
                $a = array(
                    'id' => intval($val->id),
                    'cod_method' => $val->cod_method
                ); 
                array_push($bailout,$a);
            }
            $detail_order = DB::table('delivery_detail_list') 
            ->where('id',$id)
            ->get();
    
            $detailArr = array() ;
            $total_delivery_fee = array();
            $total_price = array();
            $total = array();
            foreach ($detail_order as $val) {
                $array = array(
                'id_order' => intval($val->id),    
                'no_order' => 'ID#'.'000'.$val->no_order,
                'delivery_fee' => intval($val->delivery_fee) ,
                'name' => $val->name,
                'price' => intval($val->price),
                'description' => $val->description,
                'weight' => intval($val->weight),
                'volume' => intval($val->volume),
                'photo' => $val->photo,
                'receiver_name' => $val->receiver_name,
                'receiver_phone' => $val->receiver_phone,
                'id_method' => intval($val->id_method),
                'method' => $val->method,
                'status' => $val->status,
                'deliv_address' => $val->address,
                // 'desc_add' => $val->desc_add,
                'latitude' => $val->latitude,
                'longitude' => $val->longitude,
                'sender_name' => $val->sender_name,
                'sender_phone' => $val->sender_phone,
                'sender_address' => $val->sender_address,
                'subtotal' => $val->delivery_fee + $val->price
                );
       
                array_push($detailArr,$array);
            }
            return response()->json([
                'detail' => $detailArr[0],
                'bailout' => $bailout,
               
            ], 200);

    }

    public function deliveryStatus(Request $request)
    {   
        $id = $request->input('id');;
        $status = $request->input('status');
        // $method = $request->input('payment_method');
        // $bailout = $request->input('bailout');
        date_default_timezone_set('Asia/Bangkok');
         if($status == 5){
             Order::join('payments','payments.id','orders.payment_id')
                  ->where('orders.id',$id)
                  ->update([
                //   'pickup_status' => 1,
                //   'payment_method_id' => $method,
                //   'bailout_id' => $bailout,
                  'order_statuses_id' =>$status,
                  'delivered_at' => date('Y-m-d H:i:s')
                  ]);
  
              return response()->json('Data Successfully updated', 200);
         }elseif($status !== 3){
          Order::where('id',$id)
          ->update([
          'order_statuses_id' =>$status,    
  
          ]);
  
          return response()->json('Data Successfully updated', 200);
         }
         
         return response()->json('Data fail to update');
    }

    public function orderHistory()
    {
        try {
            $data = DB::table('history_orders')->get();

            return response()->json(['data' =>$data], 200);
            
        } catch (\Exception $e) {
            return response()->json('Belum Ada History', 200);
        }
    }

      public function DeliveryOrderList($id)
    {
        $id_driver = auth()->user()->id;
        $getOrder = DB::table('delivery_list')
                            ->where('driver_id_deliver',$id_driver)
                            ->get();
       
        // $getData = DB::table('pickup_detail_list')
        //                 ->where('driver_id_pickup', $id)
        //                 ->get();
        $data = array();
        
        if (!empty($getOrder)){
            foreach ($getOrder as $val) {
                $date = date_create($val->created_at);
                $detail_order = DB::table('delivery_detail_list') 
                                ->where('driver_id_deliver', $id_driver)
                                ->where('user_id',$id)
                                ->get();
                $detailArr = array() ;
                $total_delivery_fee = array();
                $total_price = array();
                $total = array();
            if(!empty($detail_order)){
                foreach ($detail_order as $val) {
                    $array = array(
                    'id_order' => intval($val->id),    
                    'no_order' => 'ID#'.'000'.$val->no_order,
                    'delivery_fee' => intval($val->delivery_fee) ,
                    'name' => $val->name,
                    'price' => intval($val->price),
                    'description' => $val->description,
                    'weight' => intval($val->weight),
                    'volume' => intval($val->volume),
                    'photo' => $val->photo,
                    'receiver_name' => $val->receiver_name,
                    'receiver_phone' => $val->receiver_phone,
                    'method' => $val->method,
                    'status' => $val->status,
                    'deliv_address' => $val->address,
                    // 'desc_add' => $val->desc_add,
                    'latitude' => $val->latitude,
                    'longitude' => $val->longitude,
                    // 'sender_name' => $val->sender_name,
                    // 'sender_phone' => $val->sender_phone,
                    'subtotal' => $val->delivery_fee + $val->price
                    );
                    array_push($total_delivery_fee,$array['delivery_fee']);
                    array_push($total_price,$array['price']);
                    array_push($total,$array['subtotal']);
                    array_push($detailArr,$array);
                }
            }
                $arr = array(
                    'user_id' => intval($val->user_id),
                    'sender_name' => $val->sender_name,
                    'tanggal_order' => date_format($date,"d-M-Y"),
                    'sender_phone' => $val->sender_phone,
                    'sender_address' => $val->sender_address,
                    'district' => $val->district,
                    'village' => $val->village,
                    'latitude' => $val->latitude,
                    'longitude' => $val->longitude,
                    'status' => $val->status,
                    'list_orders' => $detailArr,
                    'total_deliv_fee' => array_sum($total_delivery_fee),
                    'total_price' => array_sum($total_price),
                    'grand_total' => array_sum($total)
                      
                    
                );
                array_push($data,$arr);
            }
            return response()->json(['data' => $data[0]], 200);
        }
        

    }

    

   
}
