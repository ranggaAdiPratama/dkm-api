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
            return response()->json("Maaf, Data tidak di temukan");
        }
        
    }

    public function pickupList()
    {
        $id  = auth()->user()->id;
        $getOrder = DB::table('pickup_list_driver')
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
                    'tanggal_order' => date_format($date,"d-M-Y"),
                    'no_order' => $val->no_order
                );
                array_push($data,$arr);
            }
        return response()->json(['data' => $data], 200);
        }
    }
    
    public function orderList($id)
    {
        $id_driver = auth()->user()->id;
        $getOrder = DB::select('
        SELECT DISTINCT
        `orders`.`id` AS `id`,
        `orders`.`no_order` AS `no_order`,
        `order_details`.`delivery_fee` AS `delivery_fee`,
        `orders`.`driver_id_pickup` AS `driver_id_pickup`,
        `orders`.`created_at` AS `created_at`,
        `order_details`.`receiver` AS `receiver`,
        `order_details`.`photo`,
        users.name as sender_name,
        `user_profiles`.`address` AS `sender_address`,
        `orders`.`user_id`,
        `user_profiles`.`phone` AS `sender_phone`,
        `village`.`nama` AS `village`,
        `district`.`nama` AS `district`,
        `payment_methods`.`id` AS `id_method`,
        `user_profiles`.`phone2` AS `sender_phone2`,
        `orders`.`category_id` AS `category_id` ,
        orders.order_statuses_id as status

                                                        
                                FROM
                                    ((((((((
                                                                    `orders`
                                                                    LEFT JOIN `order_details` ON ( `orders`.`id` = `order_details`.`orders_id` ))
                                                                LEFT JOIN `payments` ON ( `orders`.`payment_id` = `payments`.`id` ))
                                                            LEFT JOIN `payment_methods` ON ( `payments`.`payment_method_id` = `payment_methods`.`id` ))
                                                        LEFT JOIN `order_statuses` ON ( `orders`.`order_statuses_id` = `order_statuses`.`id` ))
                                                    LEFT JOIN `users` ON ( `orders`.`user_id` = `users`.`id` ))
                                                JOIN `user_profiles` ON ( `orders`.`pickup_address` = `user_profiles`.`id` ))
                                        LEFT JOIN `district` ON ( user_profiles.district_id = `district`.`id` ))
                                    LEFT JOIN `village` ON ( user_profiles.village_id = `village`.`id` )) 
                                    
                                WHERE
                                    `orders`.`order_statuses_id` < 3 
                                    AND `orders`.`pickup_status` NOT LIKE 1
                                GROUP BY
                                    `orders`.`user_id`
                                ORDER BY
                                    orders.id DESC
        ');
        $data = array();
        // return $getOrder;   
        if (!empty($getOrder)){
            foreach ($getOrder as $val) {   
                $date = date_create($val->created_at);
                $detail_order = DB::table('pickup_detail_list') 
                                ->where('driver_id_pickup', $id_driver)
                                ->where('user_id',$id)
                                ->get();
                // return $detail_order;
                $detailArr = array() ;
                $total_delivery_fee = array();
                $total_price = array();
                $total = array();
             if(!empty($detail_order)){
                foreach ($detail_order as $v) {
                    if($val->photo == null){
                        $photo = "/photo/product/bm8tdGh1bWJuYWlsXzE2MTQwNTIwNjMuanBn";
                    }else{
                        $photo = $v->photo;
                    }
                    $array = array(
                    'id_order' => intval($v->id),    
                    'no_order' => '#'.$v->no_order,
                    'delivery_fee' => intval($v->delivery_fee) ,
                    'name' => $v->name,
                    'price' => intval($v->price),
                    // 'description' => $val->description,
                    // 'weight' => intval($val->weight),
                    // 'volume' => intval($val->volume),
                    'photo' => $photo,
                    'receiver_name' => $v->receiver,
                    // 'receiver_phone' => $val->phone,
                    // 'receiver_phone2' => $val->receiver_phone2,
                    // 'method' => $val->method,
                    'id_method' => intval($v->id_method),
                    // 'status' => $val->status,
                    // 'deliv_address' => $val->address,
                    // 'desc_add' => $val->desc_add,
                    // 'latitude' => $val->latitude,
                    // 'longitude' => $val->longitude,
                    // 'sender_name' => $val->sender_name,
                    // 'sender_phone' => $val->sender_phone,
                    'subtotal' => $v->delivery_fee + $v->price
                    );
                    if($v->id_method == 1 || $v->id_method == 2){
                        array_push($total,$array['subtotal']);
                    }else{
                        array_push($total,array('subtotal' => 0));
                    }
                    if($v->id_method == 1 || $v->id_method == 3){
                        array_push($total_delivery_fee,$array['delivery_fee']);
                    }else{
                        array_push($total_delivery_fee,array('delivery_fee' => 0));
                    }
                    if($v->id_method == 1 || $v->id_method == 2){
                        array_push($total_price,$array['price']);
                    }else{
                        array_push($total_price,array('price' => 0));
                    }
                    array_push($detailArr,$array);
                }
                }
                $arr = array(
                    'user_id' => intval($val->user_id),
                    'sender_name' => $val->sender_name,
                    'tanggal_order' => date_format($date,"d-M-Y"),
                    'sender_phone' => $val->sender_phone,
                    'sender_phone2' => $val->sender_phone2,
                    'sender_address' => $val->sender_address,
                    'district' => $val->district,
                    'village' => $val->village,
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

        return response()->json(['data' => 'Data Empty']);
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
            if($val->photo == null){
                $photo = "/photo/product/bm8tdGh1bWJuYWlsXzE2MTQwNTIwNjMuanBn";
            }else{
                $photo = $val->photo;
            }
            $array = array(
            'id_order' => intval($val->id),    
            'no_order' => '#'.$val->no_order,
            'delivery_fee' => intval($val->delivery_fee) ,
            'name' => $val->name !== null ? $val->name : "empty",
            'price' => intval($val->price),
            'description' => $val->description !== null ? $val->description : "empty",
            'weight' => intval($val->weight),
            'volume' => intval($val->volume),
            'photo' => $photo,
            'receiver_name' => $val->receiver !== null ? $val->receiver : "empty" ,
            'receiver_phone' => $val->phone !== null ? $val->phone : "empty",
            'receiver_phone2' => $val->receiver_phone2 !== null ? $val->phone2 : "empty",
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
            'sender_phone2' => $val->sender_phone2,
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
      //Barang diambil dengan talangan & ongkir di tanggung pengirim
       if($status == 3 && $bailout == 1 && $method == 1){
            $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
            $order = DB::table('orders')->where('id',$id)->select('driver_id_pickup','no_order')->get();
            $wallet_id = DB::select('select id from wallet where user_id = '.intval($order[0]->driver_id_pickup).' AND CAST(created_at as DATE) = CURRENT_DATE AND status = 0 OR user_id ='.intval($order[0]->driver_id_pickup).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
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
       } elseif($status == 3 && $bailout == 1 && $method == 2){
        $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
        return $getAmount;
        $order = DB::table('orders')->where('id',$id)->select('driver_id_pickup','no_order')->get();
        $wallet_id = DB::select('select id from wallet where user_id = '.intval($order[0]->driver_id_pickup).' AND CAST(created_at as DATE) = CURRENT_DATE AND status = 0 OR user_id ='.intval($order[0]->driver_id_pickup).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
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
    }elseif($status == 3 && $bailout == 2 && $method == 1){
        $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
        $order = DB::table('orders')->where('id',$id)->select('driver_id_pickup','no_order')->get();
        $wallet_id = DB::select('select id from wallet where user_id = '.intval($order[0]->driver_id_pickup).' AND CAST(created_at as DATE) = CURRENT_DATE AND status = 0 OR user_id ='.intval($order[0]->driver_id_pickup).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
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
     }elseif($status == 3 && $bailout == 2 && $method == 2){
        $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
        $order = DB::table('orders')->where('id',$id)->select('driver_id_pickup','no_order')->get();
        $wallet_id = DB::select('select id from wallet where user_id = '.intval($order[0]->driver_id_pickup).' AND CAST(created_at as DATE) = CURRENT_DATE AND status = 0 OR user_id ='.intval($order[0]->driver_id_pickup).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
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
     }elseif($status == 3 && $method == 3){
        $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
        $order = DB::table('orders')->where('id',$id)->select('driver_id_pickup','no_order')->get();
        $wallet_id = DB::select('select id from wallet where user_id = '.intval($order[0]->driver_id_pickup).' AND CAST(created_at as DATE) = CURRENT_DATE AND status = 0 OR user_id ='.intval($order[0]->driver_id_pickup).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
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
     // Retur
     }elseif($status == 7 && $method == 1){
        DB::table('return')->insert(['id_orders' => $id]);
        $credit = DB::table('wallet_transaction')
        ->insert([
            'wallet_id' => $wallet_id[0]->id,
            'type' => 'creadit',
            'description' => 'Pengembalian Pembayaran Barang (#'.$order[0]->no_order.')',
            'amount' => $getAmount[0]->delivery_fee
            ]);
        $debit = DB::table('wallet_transaction')
         ->insert([
             'wallet_id' => $wallet_id[0]->id,
             'type' => 'debit',
             'description' => 'Pengembalian Ongkir (#'.$order[0]->no_order.')',
             'amount' => -$getAmount[0]->delivery_fee
             ]);
        Order::where('id',$id)
        ->update([
        'order_statuses_id' =>$status,    

        ]);

        return response()->json('Data Successfully updated', 200);
    
       }elseif($status == 7 && $method == 2){
        DB::table('return')->insert(['id_orders' => $id]);
        $credit = DB::table('wallet_transaction')
        ->insert([
            'wallet_id' => $wallet_id[0]->id,
            'type' => 'credit',
            'description' => 'Pengembalian Pembayaran Barang (#'.$order[0]->no_order.')',
            'amount' => $getAmount[0]->delivery_fee
            ]);
        Order::where('id',$id)
        ->update([
        'order_statuses_id' =>$status,    

        ]);

        return response()->json('Data Successfully updated', 200);
    
       }elseif($status == 7 && $method == 3){
        DB::table('return')->insert(['id_orders' => $id]);
        $debit = DB::table('wallet_transaction')
        ->insert([
            'wallet_id' => $wallet_id[0]->id,
            'type' => 'debit',
            'description' => 'Pengembalian Ongkir (#'.$order[0]->no_order.')',
            'amount' => -$getAmount[0]->delivery_fee
            ]);
        Order::where('id',$id)
        ->update([
        'order_statuses_id' =>$status,    

        ]);

        return response()->json('Data Successfully updated', 200);
    
       }
       elseif($status == 7 && $method == 4){
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
                    'sender_name' => $val->sender_name,
                    'tanggal_order' => date_format($date,"d-M-Y"),
                    'sender_phone' => $val->sender_phone,
                    'sender_phone2' => $val->sender_phone2,
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
        $getOrder = DB::select('
        SELECT
            `users`.`id` AS `user_id`,
            `orders`.`created_at` AS `created_at`,
            `orders`.`driver_id_deliver` AS `driver_id_deliver`,
            `orders`.`no_order` AS `no_order`,
            `orders`.`id` AS `id`,
            `order_details`.`receiver` AS `receiver_name` 
        FROM
            ((
                    `orders`
                    JOIN `users` ON ( `orders`.`user_id` = `users`.`id` ))
            LEFT JOIN `order_details` ON ( `orders`.`id` = `order_details`.`orders_id` )) 
        WHERE
            `orders`.`order_statuses_id` = 4 AND
            driver_id_deliver = '.$id.'
        ORDER BY
            `orders`.`id` DESC  
        ');
        // return $getOrder;
        $data = array();
        if (!empty($getOrder)){
            foreach ($getOrder as $val) {
                $date = date_create($val->created_at);
                $arr = array(
                    'id' => intval($val->id),
                    'user_id' => intval($val->user_id),
                    'receiver_name' => $val->receiver_name,
                    'tanggal_order' => date_format($date,"d-M-Y"),
                    'no_order' => $val->no_order,
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
                array_push($data,$arr);
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
            $order = DB::table('orders')->where('id',$id)->select('driver_id_deliver','no_order')->get();
            $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) = CURRENT_DATE AND status = 0 OR user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
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
            $order = DB::table('orders')->where('id',$id)->select('driver_id_deliver','no_order')->get();
            $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) = CURRENT_DATE AND status = 0 OR user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
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
            $order = DB::table('orders')->where('id',$id)->select('driver_id_deliver','no_order')->get();
            $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) = CURRENT_DATE AND status = 0 OR user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
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
        $pickup_address = DB::table('user_profiles')->where('user_id',$id)->where('status',1)->first('id')->id;
        $district = DB::table('user_profiles')->where('user_id',$id)->where('status',1)->first('district_id')->district_id;
        $village = DB::table('user_profiles')->where('user_id',$id)->where('status',1)->first('village_id')->village_id;
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
            'city_id' =>  $request->input('city_id'),
            'district' =>  $request->input('district'),
            'village' =>  $request->input('village'),
            'latitude' => $request->input('latitude'),
            'longitude' =>  $request->input('longitude')
        ]);

        $w = $request->input('weight');
        $delivery_fee = $request->input('delivery_fee');
        //Check Customer
        $checkCust = DB::table('pre-pickup-assigned-check')->where('user_id',$id)->where('district_id',$district)->get();
        //Assign Pickup Driver
        if(count($checkCust) == 0 && $village === null ){
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
            JOIN
            users
            ON
            drivers.user_id = users.id
            WHERE
            drivers.district_placementt LIKE "%'.$district.'%" 
            AND
            coalesce(count, 0) < 25
            AND
            drivers.driver_category_id = 1
            AND
            online = 1
            ORDER BY
            count ASC
            LIMIT 1
            ');
            
        }
        elseif(count($checkCust) == 0 && $village !== null){
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
                                    JOIN
                                    users
                                    ON
                                    drivers.user_id = users.id
                                WHERE
                                drivers.district_placementt LIKE "%'.$district.'%" 
                                    AND
                                drivers.village_placement LIKE "%'.$village.'%"
                                    AND
                                    coalesce(count, 0) < 25
                                    AND
                                    drivers.driver_category_id = 1
                                    AND
                                    online = 1
                                ORDER BY
                                    count ASC
                                LIMIT 1');
        }
        else
        {
            $getDriver = $checkCust[0]->driver_id_pickup;
        }

        if (!empty($getDriver) && is_array($getDriver)){
            $driver =  $getDriver[0]->user_id;
            $this->sendPickupNotif($driver);
            }elseif (!empty($getDriver) && !is_array($getDriver)){
            $driver =  $getDriver;
            $this->sendPickupNotif($driver);
            }else{
                $driver = 2;                
            }
        
        $order = new Order;
        $order->user_id = $id ;
        $order->no_order = $code;
        $order->order_statuses_id = 1 ;
        $order->driver_id_pickup = $driver;
        $order->delivery_address_id = $deliv_address;
        $order->pickup_address = $pickup_address;
        $order->payment_id = $payment_id;
        $order->created_at = date('Y-m-d H:i:s',strtotime("+7 hours"));
        $order->category_id = 1;
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

        $g = DB::table('user_profiles')->leftJoin('special_region','special_region.village_id','user_profiles.village_id')->first()->pickup_fee;
        // $get = DB::table('special_region')->where('village_id',$g)->first()->pickup_fee;
        if($g !== null){
            $d = DB::table('delivery_fee_list')->first()->price;
        $add =intval($g) - intval($d);
        }
        $data = DB::table('user_list')->where('role','customer')->orWhere('role','admin')->get();
        $special_city = DB::table('city')->join('service_area','city.id','service_area.city_id')->orderBy('nama')->get();
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
                'del_fee_list' => $del_fee_list,
                'special_city' => $special_city
            ], 200);
        return response()->json('Data Tidak Ditemukan');
      }
    }

    public function region()
    {
        $special_city = DB::table('city')->join('service_area','city.id','service_area.city_id')->orderBy('nama')->get();
        $district = DB::table('districts')->get();
        $village = DB::table('villages')->get();
        if(!empty($special_city)){
            return response()->json([
                'districts' =>$district,
                'village' =>$village,
                'special_city' => $special_city
            ], 200);
        }
        return response()->json('Data Tidak Ditemukan');
    }

    public function orderListCustomer()
    {
        $id  = auth()->user()->id;
        $data = DB::table('list_orders_customer')->where('user_id',$id)->where('category_id','1')->get();

        return response()->json($data);
    }

    public function finishReturn($id)
    {
        $bailout = intval(DB::table('orders')->join('payments','payments.id','orders.payment_id')->where('orders.id',$id)->get('bailout_id')[0]->bailout_id);
        $method = intval(DB::table('orders')->join('payments','payments.id','orders.payment_id')->where('orders.id',$id)->get('payment_method_id')[0]->payment_method_id);
        DB::table('return')->where('id_orders',$id)->update(['status'=> 1]);
        if($method == 1){
        $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
        $order = DB::table('orders')->where('id',$id)->select('driver_id_deliver','no_order')->get();
        $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) = CURRENT_DATE AND status = 0');
        $credit = DB::table('wallet_transaction')
                         ->insert([
                             'wallet_id' => $wallet_id[0]->id,
                             'type' => 'credit',
                             'description' => 'Harga Barang Retur(#'.$order[0]->no_order.')',
                             'amount' => $getAmount[0]->price 
                             ]);
        $debit = DB::table('wallet_transaction')
        ->insert([
            'wallet_id' => $wallet_id[0]->id,
            'type' => 'debit',
            'description' => 'Ongkir Retur (#'.$order[0]->no_order.')',
            'amount' => - $getAmount[0]->delivery_fee 
            ]);
        }
        elseif($method == 2){
            $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
            $order = DB::table('orders')->where('id',$id)->select('driver_id_deliver','no_order')->get();
            $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) = CURRENT_DATE AND status = 0');
            $credit = DB::table('wallet_transaction')
                             ->insert([
                                 'wallet_id' => $wallet_id[0]->id,
                                 'type' => 'credit',
                                 'description' => 'Harga Barang Retur(#'.$order[0]->no_order.')',
                                 'amount' => $getAmount[0]->price 
                                 ]);
            }
            elseif($method == 3){
                $getAmount = DB::table('order_details')->where('orders_id',$id)->select('price','delivery_fee')->get();
                $order = DB::table('orders')->where('id',$id)->select('driver_id_deliver','no_order')->get();
                $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) = CURRENT_DATE AND status = 0');
                $debit = DB::table('wallet_transaction')
                ->insert([
                    'wallet_id' => $wallet_id[0]->id,
                    'type' => 'debit',
                    'description' => 'Ongkir Retur (#'.$order[0]->no_order.')',
                    'amount' => - $getAmount[0]->delivery_fee 
                    ]);
                }
        return response()->json('Data Updated Successfully');
    }

    public function cancelStatus($id)
    {   
      date_default_timezone_set('Asia/Bangkok');
       $update = Order::where('id',$id)
        ->update([
        'order_statuses_id' => 6, 
        'pickup_status' => 1,   

        ]);
        if($update){
            return response()->json('Data Successfully updated', 200);
        }
    }

    public function customerTracker($status)
    {   
        $id  = auth()->user()->id;
        if($status == 0 ){
        $getData = DB::table('list_orders_customer')
        ->where('user_id',$id)
        ->where('order_statuses_id','<', 3)
        ->get();
        }elseif($status == 1 ){
            $getData = DB::select('select * from list_orders_customer where user_id = '.$id.' AND order_statuses_id = 4 OR user_id = '.$id.' AND order_statuses_id = 3');
            }else{
        $getData = DB::table('list_orders_customer')
        ->where('user_id',$id)
        ->where('order_statuses_id',$status)
        ->get();
        }
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

    public function trackerDetailCustomer($id_order)
    {
        $id  = auth()->user()->id;
        $admin = DB::table('users')->join('user_profiles','users.id','user_profiles.user_id')->where('role_id',2)->where('online',1)->first();
        if($admin) {
            $admin_phone = $admin->phone;
        }else{
            $admin_phone = null;
        }
        $getData = DB::table('list_orders_customer')
        ->where('id',$id_order)
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
                    'subtotal' => $val->delivery_fee + $val->price,
                    'admin_phone' => $admin_phone ,
                    'deliver_driver' => $val->driver_deliver
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

    public function updateAddress(Request $request)
    {
         //validate incoming request 
         $id = $request->input('id');
         $address_id = DB::table('orders')->where('id',$id)->get('delivery_address_id')[0]->delivery_address_id;
        DB::table('delivery_addresses')->where('id',$address_id)->update([
                            'city_id' => $request->input('city_id'),
                            'district' => $request->input('district_id'),
                            'village' => $request->input('village_id'),
                            'address' => $request->input('address')
                        ]);
        
            return response()->json('data updated successfully',200);
     
     
    }

    public function sendPickupNotif($id_driver)
    {   
        $user = DB::table('users')->where('id',$id_driver)->get();
        $dev_id = array();
        foreach($user as $val){
            array_push($dev_id,$val->device_id);
        }
        // return $dev_id;
            $url = 'https://fcm.googleapis.com/fcm/send';
            $dataArr = array('click_action' => 'FLUTTER_NOTIFICATION_CLICK','status'=>"done");
            $notification = array('title' =>'Kate punya orderan baru nich, yuk cek list orderan', 'text' => 'halo broo', 'sound' => 'default', 'badge' => '1',);
            
            $arrayToSend = array('registration_ids' =>$dev_id , 'notification' => $notification, 'data' => $dataArr, 'priority'=>'high');
            $fields = json_encode ($arrayToSend);
            $headers = array (
                'Authorization: key=' . "AAAAeKmGD5s:APA91bFifm5Mk9yerGV5pluH8kZHiVCGTP8zOjnbr9yX5STqztvq9jH-Q4CCXiUekl3cxVfD8A9LKxumGl73K9Hq9kcvqDowrXgxCBAoGjmxaiSyttMqoGCDwL8RxH4foyd9MSqQt4Eo",
                'Content-Type: application/json'
            );
       
            $ch = curl_init ();
            curl_setopt ( $ch, CURLOPT_URL, $url );
            curl_setopt ( $ch, CURLOPT_POST, true );
            curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
       
            $result = curl_exec ( $ch );
            //var_dump($result);
            curl_close ( $ch );
        
        return $result;
    }


    public function testCreateOrderBulk($q)
    {
      $data = DB::table('list_orders')->take($q)->get();
      if($data){
          foreach ($data as $val ){
              
          }
      }
    }

    public function getAddress()
    {
        $id = auth()->user()->id;
        $data = DB:: select('
          SELECT
          user_profiles.id, 
          CONVERT(users.name USING utf8) AS name,
          user_profiles.user_id, 
          city.nama as city, 
          district.nama as district, 
          village.nama as village,
          user_profiles.is_user_profiles,
        CONVERT(user_profiles.address USING utf8) AS address,
          CONVERT(user_profiles.phone USING utf8) AS phone,
          CONVERT(user_profiles.status USING utf8) AS status
      FROM
          user_profiles
          
          JOIN users ON users.id = user_profiles.user_id
					JOIN district ON user_profiles.district_id = district.id
			    JOIN village ON user_profiles.village_id = village.id
					JOIN city ON city.id = user_profiles.city_id
        where user_id = '.$id.'
        ORDER BY status DESC
        ');

        if(count($data) > 0){
            return response()->json(['data' => $data]);
        }

        return response()->json('Data Not Found');
    }

    public function getAddressActive()
    {
        $id = auth()->user()->id;
        $data = DB:: select('
          SELECT
          user_profiles.id, 
          CONVERT(users.name USING utf8) AS name,
          user_profiles.user_id, 
          city.nama as city, 
          district.nama as district, 
          village.nama as village,
          user_profiles.is_user_profiles,
        CONVERT(user_profiles.address USING utf8) AS address,
          CONVERT(user_profiles.phone USING utf8) AS phone,
          CONVERT(user_profiles.status USING utf8) AS status
      FROM
          user_profiles
          
          JOIN users ON users.id = user_profiles.user_id
					JOIN district ON user_profiles.district_id = district.id
			    JOIN village ON user_profiles.village_id = village.id
					JOIN city ON city.id = user_profiles.city_id
        where user_id = '.$id.' AND
        status = 1
        ');

        if(count($data) > 0){
            return response()->json(['data' => $data[0]]);
        }

        return response()->json('Data Not Found');
    }

    public function addPickupAddress(Request $request)
    {
        $this->validate($request, [
            'city_id' => 'required',
            'district' => 'required',
            'village' => 'required',
            'address' => 'required',
        ]);
        $id = auth()->user()->id;
        $create = DB::table('user_profiles')->insert([
            'user_id' => $id,
            'city_id' =>  $request->input('city_id'),
            'village_id' =>  $request->input('village'),
            'district_id' =>  $request->input('district'),
            'address' =>   $request->input('address'),
            'phone' =>  $request->input('phone'),
            'status' =>  0
        ]);

        if($create){
            return response()->json('data created successfully');
        }
            return response()->json('data create failed');
    }

    public function updatePickupAddress(Request $request)
    {
        $id = $request->input('id');//id pickup address
        $update = DB::table('user_profiles')->where('id',$id)->update([
            'city_id' =>  $request->input('city_id'),
            'village_id' =>  $request->input('village'),
            'district_id' =>  $request->input('district'),
            'address' =>   $request->input('address'),
            'phone' =>  $request->input('phone')
        ]); 
        if($update){
            return response()->json('data updated successfully');
        }
            return response()->json('data update failed');
    }

    public function deletePickupAddress($id)
    {
        $delete = DB::table('user_profiles')->where('id',$id)->delete();
        if($delete){
            return response()->json('data delete successfully');
        }

        return response()->json('data delete failed');
    }

    public function getPickupAddressById($id)
    {
        $data = DB:: select('
        SELECT
        CONVERT(users.name USING utf8) AS name,
          pickup_addresses.user_id, 
          pickup_addresses.city_id, 
          pickup_addresses.district, 
          pickup_addresses.village, 
          CONVERT(pickup_addresses.address USING utf8) AS address,
          CONVERT(pickup_addresses.phone USING utf8) AS phone,
          CONVERT(pickup_addresses.status USING utf8) AS status
      FROM
          pickup_addresses
      
      JOIN users ON users.id = pickup_addresses.user_id
      where pickup_addresses.id = '.$id.'
        ');

        if(count($data) > 0){
            return response()->json(['data' => $data]);
        }

        return response()->json('Data Not Found');

    }

    public function getAddressById($id)
    {
        $data = DB:: select('
        SELECT
          CONVERT(users.name USING utf8) AS name,
          user_profiles.user_id, 
          user_profiles.city_id, 
          user_profiles.district_id, 
          user_profiles.village_id,
        CONVERT(user_profiles.address USING utf8) AS address,
          CONVERT(user_profiles.phone USING utf8) AS phone,
          CONVERT(user_profiles.status USING utf8) AS status
      FROM
          user_profiles
          
          JOIN users ON users.id = user_profiles.user_id
          where user_id = '.$id.'
        ');

        if(count($data) > 0){
            return response()->json(['data' => $data]);
        }

        return response()->json('Data Not Found');

    }


    public function changePickupAddress(Request $request)
    {
        $user_id = auth()->user()->id;
        $id = $request->input('id');
        $up = DB::table('user_profiles')->where('user_id',$user_id)->update(['status' => 0]);
        $update = DB::table('user_profiles')->where('id',$id)->update(['status' => 1]);
        return response()->json('data updated successfully');
    }

}
