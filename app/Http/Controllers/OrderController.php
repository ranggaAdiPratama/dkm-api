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
                    'method' => $val->method,
                    'id_method' => intval($val->id_method),
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
            'id_status' => intval($val->order_statuses_id),
            'deliv_address' => $val->address,
            // 'desc_add' => $val->desc_add,
            'latitude' => $val->latitude,
            'longitude' => $val->longitude,
            'sender_name' => $val->sender_name,
            'sender_phone' => $val->sender_phone,
            'sender_district' => $val->district,
            'sender_village' => $val->village,
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
      $id = $request->input('id');
      $status = $request->input('status');
      $method = $request->input('payment_method');
      $bailout = $request->input('bailout');
      date_default_timezone_set('Asia/Bangkok');
       if($status == 3 && $bailout == 1 && $method == 1){
           $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
           $user_id = DB::table('orders')->where('id',$id)->select('driver_id_pickup')->get();
           $wallet_id = DB::table('wallet')->where('user_id',intval($user_id[0]->driver_id_pickup))->get('id');
           $debit = DB::table('wallet_transaction')
                        ->insert([
                            'wallet_id' => $wallet_id[0]->id,
                            'type' => 'debit',
                            'description' => 'Talangan Barang',
                            'amount' => -$getAmount[0]->price 
                            ]);
            $credit = DB::table('wallet_transaction')
            ->insert([
                'wallet_id' => $wallet_id[0]->id,
                'type' => 'credit',
                'description' => 'Ongkir',
                'amount' => $getAmount[0]->delivery_fee
                ]);
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
       } elseif($status == 3 && $bailout == 1 && $method == 2){
        $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
        $user_id = DB::table('orders')->where('id',$id)->select('driver_id_pickup')->get();
        $wallet_id = DB::table('wallet')->where('user_id',intval($user_id[0]->driver_id_pickup))->get('id');
        $debit = DB::table('wallet_transaction')
                     ->insert([
                         'wallet_id' => $wallet_id[0]->id,
                         'type' => 'debit',
                         'description' => 'Talangan Barang',
                         'amount' => -$getAmount[0]->price 
                         ]);
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
    }elseif($status == 3 && $bailout == 2 && $method == 1){
        $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
        $user_id = DB::table('orders')->where('id',$id)->select('driver_id_pickup')->get();
        $wallet_id = DB::table('wallet')->where('user_id',intval($user_id[0]->driver_id_pickup))->get('id');
        $credit = DB::table('wallet_transaction')
        ->insert([
            'wallet_id' => $wallet_id[0]->id,
            'type' => 'credit',
            'description' => 'Ongkir',
            'amount' => $getAmount[0]->delivery_fee
            ]);
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
     }elseif($status == 3 && $bailout == 2 && $method == 2){
        $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
        $user_id = DB::table('orders')->where('id',$id)->select('driver_id_pickup')->get();
        $wallet_id = DB::table('wallet')->where('user_id',intval($user_id[0]->driver_id_pickup))->get('id');
        $credit = DB::table('wallet_transaction');
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
     }elseif($status == 3 && $method == 3){
        $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
        $user_id = DB::table('orders')->where('id',$id)->select('driver_id_pickup')->get();
        $wallet_id = DB::table('wallet')->where('user_id',intval($user_id[0]->driver_id_pickup))->get('id');
     
         $credit = DB::table('wallet_transaction')
         ->insert([
             'wallet_id' => $wallet_id[0]->id,
             'type' => 'credit',
             'description' => 'Ongkir',
             'amount' => $getAmount[0]->delivery_fee
             ]);
         Order::join('payments','payments.id','orders.payment_id')
             ->where('orders.id',$id)
             ->update([
             'pickup_status' => 1,
             'payment_method_id' => $method,
             'order_statuses_id' =>$status,
             'pickup_at' => date('Y-m-d H:i:s')

             ]);

         return response()->json('Data Successfully updated', 200);
    }elseif($status == 3 && $method == 4){
  
         Order::join('payments','payments.id','orders.payment_id')
             ->where('orders.id',$id)
             ->update([
             'pickup_status' => 1,
             'payment_method_id' => $method,
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
        $get = DB::table('pickup_history_list')
                            ->where('driver_id_pickup',$id)
                            ->get();
        // return $getOrder;
        $data = array();
        
        if (!empty($get)){
            foreach ($get as $val) {
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


    public function pickupHistoryOrderList($id)
    {
        $id_driver = auth()->user()->id;
        $getOrder = DB::table('pickup_history_list')
                            ->where('driver_id_pickup',$id_driver)
                            ->get();
       
     
        $data = array();
        
        if (!empty($getOrder)){
            foreach ($getOrder as $val) {
                $date = date_create($val->created_at);
                $detail_order = DB::table('pickup_history_detail_list') 
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
                    'method' => $val->method,
                    'id_method' => intval($val->id_method),
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

    public function deliveryHistoryOrderList($id)
    {
        
        $id_driver = auth()->user()->id;
        $getOrder = DB::table('delivery_history_list')
                            ->where('driver_id_deliver',$id_driver)
                            ->get();
       
        // $getData = DB::table('pickup_detail_list')
        //                 ->where('driver_id_pickup', $id)
        //                 ->get();
        $data = array();
        
        if (!empty($getOrder)){
            foreach ($getOrder as $val) {
                $date = date_create($val->created_at);
                $detail_order = DB::table('delivery_history_detail_list') 
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
                    'no_order' => '#'.$val->no_order,
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
                    'id_method' => intval($val->id_method),
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
                    'tanggal_order' => date_format($date,"d-M-Y"),
                    'receiver_name' => $val->receiver_name,
                    'receiver_phone' => $val->receiver_phone,
                    'receiver_address' => $val->address,
                    'district' => $val->district,
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
                'no_order' => '#'.$val->no_order,
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
                'id_status' => intval($val->order_statuses_id),
                'deliv_address' => $val->address,
                'receiver_district' => $val->receiver_district,
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
        $bailout = DB::table('orders')->where('id',$id)->get('bailout_id');
        $method = DB::table('orders')->join('payments','payments.id','orders.payment_id')->where('orders.id',$id)->get('payment_method_id');
        // $method = $request->input('payment_method');
        // $bailout = $request->input('bailout');
        date_default_timezone_set('Asia/Bangkok');
         if($status == 5 && $bailout == '1' && $method == 1 ){
            $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
            $user_id = DB::table('orders')->where('id',$id)->select('driver_id_pickup')->get();
            $wallet_id = DB::table('wallet')->where('user_id',intval($user_id[0]->driver_id_pickup))->get('id');
            $debit = DB::table('wallet_transaction')
                         ->insert([
                             'wallet_id' => $wallet_id[0]->id,
                             'type' => 'debit',
                             'description' => 'Talangan Barang',
                             'amount' => -$getAmount[0]->price 
                             ]);
             $credit = DB::table('wallet_transaction')
             ->insert([
                 'wallet_id' => $wallet_id[0]->id,
                 'type' => 'credit',
                 'description' => 'Ongkir',
                 'amount' => $getAmount[0]->delivery_fee
                 ]);
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
         }elseif($status !== 4){
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
                    'no_order' => '#'.$val->no_order,
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
                    'id_method' => intval($val->id_method),
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
                    'tanggal_order' => date_format($date,"d-M-Y"),
                    'receiver_name' => $val->receiver_name,
                    'receiver_phone' => $val->receiver_phone,
                    'receiver_address' => $val->address,
                    'district' => $val->district,
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

public function returnList()
{
    $id  = auth()->user()->id;
    $getOrder = DB::table('return_list')
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
                'sender_name' => $val->sender_name,
                'tanggal_order' => date_format($date,"d-M-Y")
            );
            array_push($data,$arr);
        }
    return response()->json(['data' => $data], 200);
    }

}

public function ReturnOrderList($id)
{
    $id_driver = auth()->user()->id;
    $getOrder = DB::table('return_list')
                        ->where('driver_id_deliver',$id_driver)
                        ->get();
   
    // $getData = DB::table('pickup_detail_list')
    //                 ->where('driver_id_pickup', $id)
    //                 ->get();
    $data = array();
    
    if (!empty($getOrder)){
        foreach ($getOrder as $val) {
            $date = date_create($val->created_at);
            $detail_order = DB::table('return_detail_list') 
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
                'no_order' => '#'.$val->no_order,
                'delivery_fee' => intval($val->delivery_fee) ,
                'name' => $val->name,
                'price' => intval($val->price),
                'description' => $val->description,
                'weight' => intval($val->weight),
                'volume' => intval($val->volume),
                'photo' => $val->photo,
                // 'receiver_name' => $val->receiver_name,
                // 'receiver_phone' => $val->receiver_phone,
                'method' => $val->method,
                'status' => $val->status,
                'deliv_address' => $val->address,
                // 'desc_add' => $val->desc_add,
                'latitude' => $val->latitude,
                'longitude' => $val->longitude,
                'sender_name' => $val->sender_name,
                'sender_phone' => $val->sender_phone,
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
                'tanggal_order' => date_format($date,"d-M-Y"),
                // 'receiver_name' => $val->receiver_name,
                // 'receiver_phone' => $val->receiver_phone,
                'sender_name' => $val->sender_name,
                'sender_phone' => $val->sender_phone,
                'sender_address' => $val->sender_address,
                'sender_district' => $val->district,
                'sender_village' => $val->village,
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
public function ReturnOrderDetail($id)
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
            $detail_order = DB::table('return_detail_list') 
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
                // 'receiver_name' => $val->receiver_name,
                // 'receiver_phone' => $val->receiver_phone,
                'id_method' => intval($val->id_method),
                'method' => $val->method,
                'status' => $val->status,
                'deliv_address' => $val->address,
                // 'desc_add' => $val->desc_add,
                'sender_district' => $val->district,
                'sender_village' => $val->village,
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

    public function createOrder(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required',
            'weight' => 'required',
            'volume' =>'required',
            'price' => 'required',
            'payment_method' => 'required',
            'description_address' => 'required',
            'receiver_name' => 'required',
            'receiver_phone' => 'required',
            'district' => 'required',
            'village' => 'required'

        ]);
        if($request->input('user_id') == null){
            $id = auth()->user()->id;  
        }else{
            $id = $request->input('user_id');
        }
        $numb = rand(0,999999);
        $date = str_shuffle(date('dY'));
        $code = substr($numb + $date, 0, 6);
        $district = $request->input('district');
        $price = $request->input('price');

        //insert photo
        if ($request->hasFile('photo')) 
        { 
            $fileExtension = $request->file('photo')->getClientOriginalName(); 
            $file = pathinfo($fileExtension, PATHINFO_FILENAME); 
            $extension = $request->file('photo')->getClientOriginalExtension();
            $fileStore = $file . '_' . time() . '.' . $extension; 
            $img = 'http://192.168.18.60:8000/photo/product/'. base64_encode($fileStore);
            $path = $request->file('photo')->storeAs('photo/product',$fileStore); 
        } else{
            $img = 'photo/product/bm8tdGh1bWJuYWlsXzE2MTQwNTIwNjMuanBn';
        }

        //Create Payment
        $payment_id = DB::table('payments')->insertGetId([
            'user_id' => $id,
            'status' => 1,
            'price' => $price,
            'payment_method_id' => $request->input('payment_method')
        ]);

        //Create Delivery Address
        $deliv_address = DB::table('delivery_addresses')->insertGetId([
            'address' =>   $request->input('receiver_address'),
            'description' =>  $request->input('description_address'),
            'district' =>  $request->input('district'),
            'latitude' => $request->input('latitude'),
            'longitude' =>  $request->input('longitude')
        ]);

        $w = $request->input('weight');
        $delivery_fee = $request->input('delivery_fee');
        //Check Customer
        $checkCust = DB::table('pre-pickup-assigned-check')->where('user_id',$id)->get();

        //Assign Pickup Driver
        if(count($checkCust) == 0 ){
            $getDriver = DB::select('SELECT
                                user_id
                                FROM
                                drivers
                                WHERE
                                drivers.district_placement = '.$district.'
                                AND
                                drivers.village_placement LIKE "%'.$request->input('village').'%"
                                ORDER BY
                                drivers.total_orders ASC
                                LIMIT 1');
        }
        else
        {
            $getDriver = $checkCust;
        }

      
        $driver =  $getDriver[0]->user_id;

        $order = new Order;
        $order->user_id = $id ;
        $order->no_order = $code;
        $order->order_statuses_id = 1 ;
        $order->driver_id_pickup = $driver;
        $order->delivery_address_id = $deliv_address;
        $order->payment_id = $payment_id;
        $order->pickup_status = 0;
        $order->save();
        $id_order = $order->id;

        $detail = DB::table('order_details')->insertGetId([
            'orders_id' => $id_order,
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'description' => $request->input('description_order'),
            'weight' => $request->input('weight'),
            'volume' => $request->input('volume'),
            'receiver' => $request->input('receiver_name'),
            'phone' => $request->input('receiver_phone'),
            'phone2' => $request->input('receiver_phone2'),
            'description' => $request->input('description_address'),
            'delivery_fee' => $delivery_fee,
            'photo' => $img

        ]);
            $getCount = DB::table('count_driver_order')->where('id',$driver)->first();
        if(!empty($detail)){
            DB::table('drivers')->where('user_id',$getCount->id)->update(['total_orders' => $getCount->count]); 
            return response()->json("Order berhasil di buat", 200);
        }

        return response()->json("Order gagal di buat");

    }

    public function customer()
    {
        $data = DB::table('user_list')->where('role','customer')->get();
        $district = DB::table('districts')->get();
        $village = DB::table('villages')->get();
        $payment_methods = DB::table('payment_methods')->get();
        $del_fee_list = DB::table('delivery_fee_list')->get();
        if(!empty($data)){
            return response()->json([
                'data' => $data,
                'districts' =>$district,
                'village' =>$village,
                'methods' => $payment_methods,
                'del_fee_list' => $del_fee_list
            ], 200);
        }
        return response()->json('Data Tidak Ditemukan');
    }   
}
