<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\Handler;
use App\Models\Order\Order;
use DB;

class OrderExpController extends Controller
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
    public function PickupList()
    {
        $id  = auth()->user()->id;
        $getOrder = DB::table('delivery_list')
                            ->where('driver_id_deliver',$id)
                            ->where('category_id', 2)
                            ->where('pickup_status',0)
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


    public function deliveryList()
    {
        $id  = auth()->user()->id;
        $getOrder = DB::table('delivery_list')
                            ->where('driver_id_deliver',$id)
                            ->where('category_id', 2)
                            // ->where('pickup_status',1)
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
                            ->where('category_id', 2)
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

    public function deliveryHistoryOrderList()
    {
        
        $id_driver = auth()->user()->id;
        $getOrder = DB::table('delivery_history_list')
                            ->where('driver_id_deliver',$id_driver)
                            ->where('category_id', 2)
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
                                // ->where('user_id',$id)
                                ->where('category_id', 2)
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
                    'receiver_phone2' => $val->receiver_phone2,
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
                    'receiver_phone2' => $val->receiver_phone2,
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
                array_push($data,$detailArr);
            }

            if(!empty($data)){
                return response()->json(['data' => $data[0]], 200);
            }
            
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
                'receiver_phone2' => $val->receiver_phone2,
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
                'sender_phone2' => $val->sender_phone2,
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

    public function pickupShow($id)
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
                'receiver_phone2' => $val->receiver_phone2,
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
                'sender_phone2' => $val->sender_phone2,
                'sender_address' => $val->sender_address,
                'sender_district' => $val->district,
                'sender_village' => $val->village,
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
        $method = intval(DB::table('orders')->join('payments','payments.id','orders.payment_id')->where('orders.id',$id)->get('payment_method_id')[0]->payment_method_id);
        // $method = $request->input('payment_method');
        // $bailout = $request->input('bailout');
        date_default_timezone_set('Asia/Bangkok');
        //Barang diantar dengan tagian tanpa ongkir
         if($status == 5 && $method == 1 ){
            $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
            $order = DB::table('orders')->join('delivery_addresses','orders.delivery_address_id','delivery_addresses.id')
            ->where('orders.id',$id)->select('driver_id_deliver','no_order','district')->get();
            $id_driver = intval($order[0]->driver_id_deliver);
            $update_district_placement = DB::table('drivers')->where('user_id',$id_driver)->update(['district_placement' => $order[0]->district,'available' => 0]);
            $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) = CURRENT_DATE');
            $credit = DB::table('wallet_transaction')
                         ->insert([
                             'wallet_id' => $wallet_id[0]->id,
                             'type' => 'credit',
                             'description' => 'Tagihan harga barang (#'.$order[0]->no_order.')',
                             'amount' => $getAmount[0]->price 
                             ]);
             Order::join('payments','payments.id','orders.payment_id')
                  ->where('orders.id',$id)
                  ->update([
                  'order_statuses_id' =>$status,
                  'delivered_at' => date('Y-m-d H:i:s')
                  ]);
            
  
              return response()->json('Data Successfully updated', 200);
        //Barang dintar dengan tagihan dan ongkir
         }elseif($status == 5 && $method == 2 ){
            $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
            $order = DB::table('orders')->join('delivery_addresses','orders.delivery_address_id','delivery_addresses.id')
            ->where('orders.id',$id)->select('driver_id_deliver','no_order','district')->get();
            $id_driver = intval($order[0]->driver_id_deliver);
            $update_district_placement = DB::table('drivers')->where('user_id',$id_driver)->update(['district_placement' => $order[0]->district,'available' => 0]);
            $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) = CURRENT_DATE');
            $credit = DB::table('wallet_transaction')
                         ->insert([
                             'wallet_id' => $wallet_id[0]->id,
                             'type' => 'credit',
                             'description' => 'Tagihan harga barang (#'.$order[0]->no_order.')',
                             'amount' => $getAmount[0]->price 
                             ]);
             $credit = DB::table('wallet_transaction')
             ->insert([
                 'wallet_id' => $wallet_id[0]->id,
                 'type' => 'credit',
                 'description' => 'Ongkir (#'.$order[0]->no_order.')',
                 'amount' => $getAmount[0]->delivery_fee
                 ]);
             Order::join('payments','payments.id','orders.payment_id')
                  ->where('orders.id',$id)
                  ->update([
                  'order_statuses_id' =>$status,
                  'delivered_at' => date('Y-m-d H:i:s')
                  ]);
            
  
              return response()->json('Data Successfully updated', 200);
        //Barang diantar tanpa tagihan dan tanpa ongkir
         }elseif($status == 5 && $method == 3 ){
             Order::join('payments','payments.id','orders.payment_id')
                  ->where('orders.id',$id)
                  ->update([
                  'order_statuses_id' =>$status,
                  'delivered_at' => date('Y-m-d H:i:s')
                  ]);
            
  
              return response()->json('Data Successfully updated', 200);
        //Barang diantar dengan ongkir tanpa tagihan
         }elseif($status == 5 && $method == 4 ){
            $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
            $order = DB::table('orders')->join('delivery_addresses','orders.delivery_address_id','delivery_addresses.id')
            ->where('orders.id',$id)->select('driver_id_deliver','no_order','district')->get();
            $id_driver = intval($order[0]->driver_id_deliver);
            $update_district_placement = DB::table('drivers')->where('user_id',$id_driver)->update(['district_placement' => $order[0]->district,'available' => 0]);
            $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) = CURRENT_DATE');
             $credit = DB::table('wallet_transaction')
             ->insert([
                 'wallet_id' => $wallet_id[0]->id,
                 'type' => 'credit',
                 'description' => 'Ongkir (#'.$order[0]->no_order.')',
                 'amount' => $getAmount[0]->delivery_fee
                 ]);
            Order::join('payments','payments.id','orders.payment_id')
                 ->where('orders.id',$id)
                 ->update([
                 'order_statuses_id' =>$status,
                 'delivered_at' => date('Y-m-d H:i:s')
                 ]);
           
 
             return response()->json('Data Successfully updated', 200);
        }elseif($status == 7){
            DB::table('return')->insert(['id_orders' => $id]);
            Order::where('id',$id)
            ->update([
            'order_statuses_id' =>$status,    
    
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

      public function DeliveryOrderList()
    {
        $id_driver = auth()->user()->id;
        $getOrder = DB::table('delivery_list')
                            ->where('driver_id_deliver',$id_driver)
                            // ->where('pickup_status',1)
                            ->get();
       
        // $getData = DB::table('pickup_detail_list')
        //                 ->where('driver_id_pickup', $id)
        //                 ->get();
        $data = array();
        // dd($getOrder);
        if (!empty($getOrder)){
            foreach ($getOrder as $val) {
                $date = date_create($val->created_at);
                $detail_order = DB::table('delivery_detail_list') 
                                ->where('driver_id_deliver', $id_driver)
                                ->get();
                // return $detail_order;
                $detailArr = array() ;
                $total_delivery_fee = array();
                $total_price = array();
                $total = array();
            if(!empty($detail_order)){
                foreach ($detail_order as $v) {
                    $array = array(
                    'id_order' => intval($v->id),    
                    'no_order' => '#'.$v->no_order,
                    'delivery_fee' => intval($v->delivery_fee) ,
                    'name' => $v->name,
                    'price' => intval($v->price),
                    'description' => $v->description,
                    'weight' => intval($v->weight),
                    'volume' => intval($v->volume),
                    'photo' => $v->photo,
                    'receiver_name' => $v->receiver_name,
                    'receiver_phone' => $v->receiver_phone,
                    'receiver_phone2' => $v->receiver_phone2,
                    'method' => $v->method,
                    'id_method' => intval($v->payment_method_id),
                    'status' => $v->status,
                    'deliv_address' => $v->address,
                    // 'desc_add' => $val->desc_add,
                    'latitude' => $v->latitude,
                    'longitude' => $v->longitude,
                    'sender_name' => $v->sender_name,
                    'sender_phone' => $v->sender_phone,
                    'sender_address' => $v->sender_address,
                    // 'sender_district' => $val->district,
                    // 'sender_village' => $val->village,
                    'subtotal' => $v->delivery_fee + $v->price
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
                    'receiver_phone2' => $val->receiver_phone2,
                    'receiver_address' => $val->address,
                    'sender_name' => $val->sender_name,
                    'sender_phone' => $val->sender_phone,
                    'sender_phone2' => $val->sender_phone2,
                    'sender_address' => $val->sender_address,
                    'sender_district' => $val->sender_district,
                    'sender_village' => $val->sender_village,
                    'district' => $val->district,
                    // 'latitude' => $val->latitude,
                    // 'longitude' => $val->longitude,
                    'status' => $val->status,
                    'list_orders' => $detailArr,
                    'total_deliv_fee' => array_sum($total_delivery_fee),
                    'total_price' => array_sum($total_price),
                    'grand_total' => array_sum($total)
                      
                    
                );
                array_push($data,$detailArr);
                
            }
            if(!empty($data)){
                return response()->json(['data' => $data[0]], 200);
            }else{
                return response()->json(['data' => $data], 201);
            }
            
        }
        

    }
    public function PickupOrderList()
    {
        $id_driver = auth()->user()->id;
        $getOrder = DB::table('delivery_list')
                            ->where('driver_id_deliver',$id_driver)
                            ->where('pickup_status',0)
                            ->get();
       
        // $getData = DB::table('pickup_detail_list')
        //                 ->where('driver_id_pickup', $id)
        //                 ->get();
        $data = array();
        // dd($getOrder);
        if (!empty($getOrder)){
            foreach ($getOrder as $val) {
                $date = date_create($val->created_at);
                $detail_order = DB::table('delivery_detail_list') 
                                ->where('driver_id_deliver', $id_driver)
                                ->get();
                $detailArr = array() ;
                $total_delivery_fee = array();
                $total_price = array();
                $total = array();
            if(!empty($detail_order)){
                foreach ($detail_order as $v) {
                    $array = array(
                    'id_order' => intval($v->id),    
                    'no_order' => '#'.$v->no_order,
                    'delivery_fee' => intval($v->delivery_fee) ,
                    'name' => $v->name,
                    'price' => intval($v->price),
                    'description' => $v->description,
                    'weight' => intval($v->weight),
                    'volume' => intval($v->volume),
                    'photo' => $v->photo,
                    'receiver_name' => $v->receiver_name,
                    'receiver_phone' => $v->receiver_phone,
                    'receiver_phone2' => $v->receiver_phone2,
                    'method' => $v->method,
                    'id_method' => intval($val->id_method),
                    'status' => $v->status,
                    'deliv_address' => $v->address,
                    // 'desc_add' => $val->desc_add,
                    'latitude' => $v->latitude,
                    'longitude' => $v->longitude,
                    'sender_name' => $v->sender_name,
                    'sender_phone' => $v->sender_phone,
                    'sender_address' => $v->sender_address,
                    // 'sender_district' => $val->district,
                    // 'sender_village' => $val->village,
                    'subtotal' => $v->delivery_fee + $v->price
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
                    'receiver_phone2' => $val->receiver_phone2,
                    'receiver_address' => $val->address,
                    'sender_name' => $val->sender_name,
                    'sender_phone' => $val->sender_phone,
                    'sender_phone2' => $val->sender_phone2,
                    'sender_address' => $val->sender_address,
                    'sender_district' => $val->district_sender,
                    'sender_village' => $val->village_sender,
                    'district' => $val->district,
                    'latitude' => $val->latitude,
                    'longitude' => $val->longitude,
                    'status' => $val->status,
                    'list_orders' => $detailArr,
                    'total_deliv_fee' => array_sum($total_delivery_fee),
                    'total_price' => array_sum($total_price),
                    'grand_total' => array_sum($total)
                      
                    
                );
                array_push($data,$detailArr);
            }
            if(!empty($data)){
            return response()->json(['data' => $data[0]], 200);
           }else{
            return response()->json(['data' => $data], 200);
           }
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
                'sender_phone2' => $val->sender_phone2,
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
                'sender_phone2' => $val->sender_phone2,
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
                'sender_phone2' => $val->sender_phone2,
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
            'weight' => 'required',
            'price' => 'required',
            'payment_method' => 'required',
            'receiver_address' => 'required',
            'receiver_name' => 'required',
            'receiver_phone' => 'required'

        ]);
        
        if($request->input('user_id') == null){
            $id = auth()->user()->id;  
        }else{
            $id = $request->input('user_id');
        }
        $numb = rand(0,999999);
        $date = str_shuffle(date('dY'));
        $code = substr($numb + $date, 0, 6);
        // $district = DB::table('user_profiles')->where('user_id',$id)->first('district_id')->district_id;
        $pickup_address = DB::table('user_profiles')->where('user_id',$id)->where('status',1)->first('id')->id;
        $district = DB::table('user_profiles')->where('user_id',$id)->where('status',1)->first('district_id')->district_id;
        $price = $request->input('price');

        //insert photo
        if ($request->hasFile('photo')) 
        { 
            $fileExtension = $request->file('photo')->getClientOriginalName(); 
            $file = pathinfo($fileExtension, PATHINFO_FILENAME); 
            $extension = $request->file('photo')->getClientOriginalExtension();
            $fileStore = $file . '_' . time() . '.' . $extension; 
            $img = 'photo/product/'. base64_encode($fileStore);
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
            'village' =>  $request->input('village'),
            'latitude' => $request->input('latitude'),
            'longitude' =>  $request->input('longitude')
        ]);

        $w = $request->input('weight');
        $village = $request->input('village');
        $delivery_fee = $request->input('delivery_fee');
        //Assign Pickup Driver
        if($village === "null" ){
            $getDriver = DB::select('
            SELECT
            user_id, 
            coalesce(count, 0) as count
            FROM
            drivers
            LEFT JOIN
            count_driver_order
            ON 
            drivers.user_id = count_driver_order.id
            WHERE
            drivers.district_placement = '.$district.' 
            AND
            coalesce(count, 0) < 25
            AND
            drivers.driver_category_id = 2
            ORDER BY
            count ASC
            LIMIT 1
            ');
            
        }
        elseif($village !== "null"){
            $getDriver = DB::select('
                                SELECT
                                user_id, 
                                coalesce(count, 0) as count
                                FROM
                                drivers
                                LEFT JOIN
                                count_driver_order
                                ON 
                                drivers.user_id = count_driver_order.id
                                WHERE
                                drivers.district_placement = '.$district.' 
                                AND
                                drivers.village_placement LIKE "%'.$request->input('village').'%"
                                AND
                                drivers.driver_category_id = 2
                                AND
                                coalesce(count, 0) < 25
                                ORDER BY
                                count ASC
                                LIMIT 1');
        }
        
        if($getDriver){
        $driver =  $getDriver[0]->user_id;
        }
        else{
            $driver = 2;
        }
        $available = DB::table('drivers')->where('user_id',$driver)->update(['available'=> 1]);

        $order = new Order;
        $order->user_id = $id ;
        $order->no_order = $code;
        $order->order_statuses_id = 4 ;
        $order->driver_id_pickup = $driver;
        $order->driver_id_deliver = $driver;
        $order->pickup_address = $pickup_address;
        $order->delivery_address_id = $deliv_address;
        $order->category_id = 2;
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

  

    public function finishReturn($id)
    {
        $bailout = intval(DB::table('orders')->join('payments','payments.id','orders.payment_id')->where('orders.id',$id)->get('bailout_id')[0]->bailout_id);
        DB::table('return')->where('id_orders',$id)->update(['status'=> 1]);
        if($bailout == 1 ){
        $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
        $order = DB::table('orders')->where('id',$id)->select('driver_id_deliver','no_order')->get();
        $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) = CURRENT_DATE');
        $credit = DB::table('wallet_transaction')
                         ->insert([
                             'wallet_id' => $wallet_id[0]->id,
                             'type' => 'credit',
                             'description' => 'Retur Paket (#'.$order[0]->no_order.')',
                             'amount' => $getAmount[0]->price 
                             ]);
        }
        return response()->json('Data Updated Successfully');
    }

    public function cancelStatus(Request $request)
    {   
      $id = $request->input('id');
      $status = $request->input('status');
      date_default_timezone_set('Asia/Bangkok');
       $update = Order::where('id',$id)
        ->update([
        'order_statuses_id' =>$status,    

        ]);
        if($update){
            return response()->json('Data Successfully updated', 200);
        }
    }


    public function pickupStatus(Request $request)
    {   
      $id = $request->input('id');
      $status = $request->input('status');
      $method = $request->input('payment_method');
      $bailout = $request->input('bailout');
      date_default_timezone_set('Asia/Bangkok');
      //Barang diambil dengan talangan & ongkir di tanggung pengirim
       if($status == 4 && $bailout == 1 && $method == 1){
            $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
            $order = DB::table('orders')->where('id',$id)->select('driver_id_pickup','no_order')->get();
            $wallet_id = DB::select('select id from wallet where user_id = '.intval($order[0]->driver_id_pickup).' AND CAST(created_at as DATE) = CURRENT_DATE');
            $debit = DB::table('wallet_transaction')
                        ->insert([
                            'wallet_id' => $wallet_id[0]->id,
                            'type' => 'debit',
                            'description' => 'Talangan Barang (#'.$order[0]->no_order.')',
                            'amount' => -$getAmount[0]->price 
                            ]);
            $credit = DB::table('wallet_transaction')
            ->insert([
                'wallet_id' => $wallet_id[0]->id,
                'type' => 'credit',
                'description' => 'Ongkir (#'.$order[0]->no_order.')',
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
        //Barang diambil dengan talangan & ongkir di tanggung penerima
       } elseif($status == 4 && $bailout == 1 && $method == 2){
        $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
        $order = DB::table('orders')->where('id',$id)->select('driver_id_pickup','no_order')->get();
        $wallet_id = DB::select('select id from wallet where user_id = '.intval($order[0]->driver_id_pickup).' AND CAST(created_at as DATE) = CURRENT_DATE');
        $debit = DB::table('wallet_transaction')
                     ->insert([
                         'wallet_id' => $wallet_id[0]->id,
                         'type' => 'debit',
                         'description' => 'Talangan Barang (#'.$order[0]->no_order.')',
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
    //Barang diambil tanpa talangan & ongkir di tanggung pengirim
    }elseif($status == 4 && $bailout == 2 && $method == 1){
        $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
        $order = DB::table('orders')->where('id',$id)->select('driver_id_pickup','no_order')->get();
        $wallet_id = DB::select('select id from wallet where user_id = '.intval($order[0]->driver_id_pickup).' AND CAST(created_at as DATE) = CURRENT_DATE');
        $credit = DB::table('wallet_transaction')
        ->insert([
            'wallet_id' => $wallet_id[0]->id,
            'type' => 'credit',
            'description' => 'Ongkir (#'.$order[0]->no_order.')',
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
     //Barang diambil tanpa talangan & ongkir di tanggung penerima
     }elseif($status == 4 && $bailout == 2 && $method == 2){
        $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
        $order = DB::table('orders')->where('id',$id)->select('driver_id_pickup','no_order')->get();
        $wallet_id = DB::select('select id from wallet where user_id = '.intval($order[0]->driver_id_pickup).' AND CAST(created_at as DATE) = CURRENT_DATE');
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
     //Barang diambil ongkir di tanggung pengirim
     }elseif($status == 4 && $method == 3){
        $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
        $order = DB::table('orders')->where('id',$id)->select('driver_id_pickup','no_order')->get();
        $wallet_id = DB::select('select id from wallet where user_id = '.intval($order[0]->driver_id_pickup).' AND CAST(created_at as DATE) = CURRENT_DATE');
         $credit = DB::table('wallet_transaction')
         ->insert([
             'wallet_id' => $wallet_id[0]->id,
             'type' => 'credit',
             'description' => 'Ongkir (#'.$order[0]->no_order.')',
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
    //Barang diambil ongkir di tanggung penerima
    }elseif($status == 4 && $method == 4){
         Order::join('payments','payments.id','orders.payment_id')
             ->where('orders.id',$id)
             ->update([
             'pickup_status' => 1,
             'payment_method_id' => $method,
             'order_statuses_id' =>$status,
             'pickup_at' => date('Y-m-d H:i:s')

             ]);

         return response()->json('Data Successfully updated', 200);
     // Retur
     }elseif($status == 7){
        DB::table('return')->insert(['id_orders' => $id]);
        Order::where('id',$id)
        ->update([
        'order_statuses_id' =>$status,    

        ]);

        return response()->json('Data Successfully updated', 200);
    
       }elseif($status !== 8){
        Order::where('id',$id)
        ->update([
        'order_statuses_id' =>$status,    

        ]);

        return response()->json('Data Successfully updated', 200);
       }
       
       return response()->json('Data fail to update');
    }

    public function driverChange($id)
    {
        $district = DB::table('orders')->join('delivery_addresses','orders.delivery_address_id','delivery_addresses.id')->where('orders.id',$id)->first('district')->district;
        $lastDriver = DB::table('orders')->where('orders.id',$id)->first('driver_id_deliver')->driver_id_deliver;
        $getDriver = DB::select('
                        SELECT
                        user_id, 
                        coalesce(count, 0) as count
                        FROM
                        drivers
                        LEFT JOIN
                        count_driver_order
                        ON 
                        drivers.user_id = count_driver_order.id
                        WHERE
                        drivers.district_placement = '.$district.' 
                        AND
                        coalesce(count, 0) < 25
                        AND
                        drivers.driver_category_id = 2
                        AND
                        drivers.user_id != '.$lastDriver.'
                        ORDER BY
                        count ASC
                        LIMIT 1
                        ');
        
        if(!empty($getDriver)){
            $updateOrder = DB::table('orders')->where('id',$id)->update(['driver_id_deliver' => $getDriver[0]->user_id]);
        }else {
            $updateOrder = DB::table('orders')->where('id',$id)->update(['driver_id_deliver' => 2]);
        }
        
       

        if($updateOrder){
            return response()->json('data updated successfully',200);
        }
        
    }

    //customer

    public function customer()
    {
        $data = DB::table('user_list')
                ->where('role','customer')
                ->get();
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

    public function orderListCustomer()
    {
        $id  = auth()->user()->id;
        $data = DB::table('list_orders_customer')
        ->where('user_id',$id)
        ->where('orders.category_id',1)
        ->get();

        return response()->json($data);
    }
    public function orderListCustomerExp()
    {
        $id  = auth()->user()->id;
        $data = DB::table('list_orders_customer')
        ->where('user_id',$id)
        // ->where('order_statuses_id','>=',5)
        ->where('category_id',2)
        ->get();

        return response()->json(['data' => $data]);
    }

    public function historyDetailCustomer($id)
    {
        $getData = DB::table('list_orders_customer')
        ->where('id',$id)
        ->get();
        $data = array();
        if(!empty($getData)){
            foreach($getData as $val){
                $date = date_create($val->created_at);
                $array = array(
                    'id_order' => intval($val->id),    
                    'no_order' => '#'.$val->no_order,
                    'delivery_fee' => intval($val->delivery_fee) ,
                    'product_name' => $val->product_name,
                    'price' => intval($val->price),
                    'tangga_order' => date_format($date,'d-M-Y') ,
                    'product_description' => $val->product_description,
                    'weight' => intval($val->weight),
                    'volume' => intval($val->volume),
                    'photo' => $val->photo,
                    'receiver_name' => $val->receiver_name,
                    'receiver_phone' => $val->receiver_phone,
                    'receiver_phone2' => $val->receiver_phone2,
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
                    'sender_phone2' => $val->sender_phone2,
                    'sender_address' => $val->sender_address,
                    'driver_name' => $val->driver_name,
                    'driver_phone' => $val->driver_phone,
                    'driver_photo' => $val->driver_photo,
                    'category_id' => intval($val->category_id),
                    'driver_delivery' => $val->driver_deliver,
                    'subtotal' => $val->delivery_fee + $val->price
                    );
                array_push($data,$array);
            }
        }
        
        if(!empty($data)){
        return response()->json(['data' => $data[0]]);
        }else{
            return response()->json(['data' => $data]);
        }
    }

    public function historyOrderListCustomer()
    {
        $id  = auth()->user()->id;
        $getData = DB::table('list_orders_customer')
        ->where('user_id',$id)
        ->where('order_statuses_id','>=',5)
        ->get();

        $data = array();

        if(!empty($getData)){
            foreach($getData as $val){
                $date = date_create($val->created_at);
                $array = array(
                    'id_order' => intval($val->id),
                    'no_order' => '#'.$val->no_order,
                    'tangga_order' => date_format($date,'d-M-Y') ,
                    'sender_address' => $val->sender_address ,
                    'receiver_address' => $val->address,
                    'product_name' => $val->product_name,
                    'delivery_fee' => intval($val->delivery_fee),
                    'price' => intval($val->price),
                    'category_id' => intval($val->category_id),
                    'receiver_name' => $val->receiver_name,
                    'status' => $val->status,
                    'payment_method' => intval($val->id_method),
                    'total' => intval($val->price) + intval($val->delivery_fee)

                );
                array_push($data,$array);
            }
        }
        

        return response()->json(['data' => $data]);
    }
}
