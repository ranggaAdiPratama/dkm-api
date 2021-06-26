<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\Handler;
use App\Models\Order\Order;
use App\Models\Order\Driver;
use DB;

class AdminOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('role',['except' => ['show','sendNotif','appVersion']]);
    }

   //Order
    public function index()
    {
      $getData = DB::table('list_orders_pickingup')
      ->where('category_id',1)
      ->get();
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $date = date_create($val->order_date);
                $arr = array(
                    'id' => $val->id,
                    'no_order' => '#'.$val->no_order,
                    'client' => $val->name,
                    'delivery_fee' => $val->delivery_fee,
                    'order_status' => $val->order_status,
                    'payment_status' => $val->payment_status,
                    'driver_name' => $val->driver_name,
                    'payment_method' => $val->method,
                    'payment_method_id' => $val->payment_method_id,
                    'date' => date_format($date, 'Y/m/d'),
                    'receiver_address' => $val->receiver_address,
                    'receiver_district' => $val->receiver_district,
                    'receiver_village' => $val->receiver_village,
                    'deliver_driver_name' => $val->deliver_driver_name,
                );
                array_push($data,$arr);
            }
        }else{
            return response()->json('Belum ada order', 404);
        }
        return response()->json(['data' => $data]);
    }

    public function allReguler()
    {
        $getData = DB::select('
        SELECT
	`orders`.`id` AS `id`,
	`orders`.`no_order` AS `no_order`,
	`order_statuses`.`status` AS `order_status`,
	`orders`.`created_at` AS `order_date`,
	`orders`.`category_id` AS `category_id`,
	`return`.`status` AS `return_status`,
	`driver_queue`.`d_driver` AS `delivery_driver`,
	`driver_queue`.`p_driver` AS `driver_name`,
	`cust_receiver_add`.`receiver_district` AS `receiver_district`,
	`cust_receiver_add`.`receiver_village` AS `receiver_village`,
	`cust_receiver_add`.`receiver_address` AS `receiver_address`,
    `cust_receiver_add`.`receiver_city` AS `receiver_city`,
	`order_details`.`receiver` AS `receiver_name`,
	`order_details`.`phone` AS `receiver_phone`,
	`users`.`name` AS `client`,
	`user_profiles`.`phone` AS `sender_phone`,
	`user_profiles`.`address` AS `sender_address`,
	`order_details`.`price` AS `price`,
	`order_details`.`delivery_fee` AS `delivery_fee`,
	`order_details`.`name` AS `name`,
	`payment`.`payment_status` AS `payment_status`,
	`payment`.`payment_method` AS `method`,
	`payment`.`payment_method_id` AS `payment_method_id` 
FROM
	((((((((((
									`orders`
									    LEFT JOIN `order_statuses` ON ( `orders`.`order_statuses_id` = `order_statuses`.`id` ))
                                    LEFT JOIN `users` ON ( `orders`.`user_id` = `users`.`id` ))
								  LEFT JOIN `user_profiles` ON ( `orders`.`pickup_address` = `user_profiles`.`id` ))
								LEFT JOIN `delivery_addresses` ON ( `orders`.`delivery_address_id` = `delivery_addresses`.`id` ))
							LEFT JOIN `return` ON ( `orders`.`id` = `return`.`id_orders` ))
						LEFT JOIN `driver_queue` ON ( `orders`.`id` = `driver_queue`.`id` ))
					LEFT JOIN `cust_receiver_add` ON ( `orders`.`id` = `cust_receiver_add`.`id` ))
				LEFT JOIN `order_details` ON ( `orders`.`id` = `order_details`.`orders_id` ))
			LEFT JOIN `user_list` ON ( `orders`.`user_id` = `user_list`.`id` ))
	LEFT JOIN `payment` ON ( `orders`.`payment_id` = `payment`.`payment_id` )) 
WHERE
	cast( `orders`.`created_at` AS date ) = curdate() AND
    orders.category_id = 1
GROUP BY orders.id
ORDER BY
	`orders`.`id` DESC
        ');
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $date = date_create($val->order_date); 
                $arr = array(
                    'id' => $val->id,
                    'no_order' => '#'.intval($val->no_order),
                    'client' => $val->client,
                    'date' => date_format($date,'Y-m-d') ,
                    'delivery_fee' => intval($val->delivery_fee),
                    'order_status' => $val->order_status,
                    'payment_status' => $val->payment_status,
                    'payment_method' => $val->method,
                    'payment_method_id' => $val->payment_method_id,
                    'sender_address' => $val->sender_address,
                    'sender_phone' => $val->sender_phone,
                    'receiver_name' => $val->receiver_name,
                    'receiver_phone' => $val->receiver_phone,
                    'receiver_address' => $val->receiver_address,
                    'receiver_district' => $val->receiver_district,
                    'receiver_village' => $val->receiver_village,
                    'price' => intval($val->price),
                    'total' => $val->price + $val->delivery_fee,
                    'driver_name' => $val->driver_name,
                    'delivery_driver' => $val->delivery_driver,
                    'return_status' => intval($val->return_status)
                   
                );
                array_push($data,$arr);
            }
        }else{
            return response()->json('Belum ada order', 404);
        }
        return response()->json(['data' => $data]);
    }

    public function allRegulerByDate(Request $request)
    {
        $date = $request->input('date');
        $getData = DB::select('
        SELECT
	`orders`.`id` AS `id`,
	`orders`.`no_order` AS `no_order`,
	`order_statuses`.`status` AS `order_status`,
	`order_details`.`delivery_fee` AS `delivery_fee`,
	`users`.`name` AS `name`,
	`orders`.`created_at` AS `order_date`,
	`payment_methods`.`method` AS `method`,
	`payment_status`.`status` AS `payment_status`,
	`order_details`.`price` AS `price`,
	`order_details`.`receiver` AS `receiver_name`,
	`order_details`.`phone` AS `receiver_phone`,
	`order_details`.`weight` AS `weight`,
	`user_profiles`.`phone` AS `sender_phone`,
	`user_profiles`.`address` AS `sender_address`,
	`delivery_addresses`.`address` AS `receiver_address`,
	`district`.`nama` AS `receiver_district`,
	`u`.`name` AS `driver_name`,
	`order_details`.`name` AS `product_name`,
	`orders`.`category_id` AS `category_id`,
	`ud`.`name` AS `delivery_driver`,
	`village`.`nama` AS `receiver_village`,
	`payments`.`payment_method_id` AS `payment_method_id`,
	`return`.`status` AS `return_status` 
FROM
	(((((((((((((
														`orders`
														JOIN `order_details` ON ( `orders`.`id` = `order_details`.`orders_id` ))
													JOIN `order_statuses` ON ( `orders`.`order_statuses_id` = `order_statuses`.`id` ))
												LEFT JOIN `users` ON ( `orders`.`user_id` = `users`.`id` ))
											LEFT JOIN `payments` ON ( `orders`.`payment_id` = `payments`.`id` ))
										JOIN `payment_status` ON ( `payments`.`status` = `payment_status`.`id` ))
									JOIN `payment_methods` ON ( `payments`.`payment_method_id` = `payment_methods`.`id` ))
								LEFT JOIN `user_profiles` ON ( `users`.`id` = `user_profiles`.`user_id` ))
							JOIN `delivery_addresses` ON ( `orders`.`delivery_address_id` = `delivery_addresses`.`id` ))
						LEFT JOIN `district` ON ( `delivery_addresses`.`district` = `district`.`id` ))
					LEFT `users` `u` ON ( `orders`.`driver_id_pickup` = `u`.`id` ))
				LEFT JOIN `users` `ud` ON ( `orders`.`driver_id_deliver` = `ud`.`id` ))
			LEFT JOIN `village` ON ( `delivery_addresses`.`village` = `village`.`id` ))
	LEFT JOIN `return` ON ( `orders`.`id` = `return`.`id_orders` )) 
WHERE 
cast( `orders`.`created_at` AS date ) = "'.$date.'"
ORDER BY	
`orders`.`id` DESC
        ');
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $date = date_create($val->order_date); 
                $arr = array(
                    'id' => $val->id,
                    'no_order' => '#'.intval($val->no_order),
                    'client' => $val->name,
                    'date' => date_format($date,'Y-m-d') ,
                    'delivery_fee' => intval($val->delivery_fee),
                    'order_status' => $val->order_status,
                    'payment_status' => $val->payment_status,
                    'payment_method' => $val->method,
                    'payment_method_id' => $val->payment_method_id,
                    'sender_address' => $val->sender_address,
                    'sender_phone' => $val->sender_phone,
                    'receiver_name' => $val->receiver_name,
                    'receiver_phone' => $val->receiver_phone,
                    'receiver_address' => $val->receiver_address,
                    'receiver_district' => $val->receiver_district,
                    'receiver_village' => $val->receiver_village,
                    'price' => intval($val->price),
                    'total' => $val->price + $val->delivery_fee,
                    'driver_name' => $val->driver_name,
                    'delivery_driver' => $val->delivery_driver,
                    'return_status' => intval($val->return_status)
                   
                );
                array_push($data,$arr);
            }
        }else{
            return response()->json('Belum ada order');
        }
        return response()->json(['data' => $data]);
    }

    public function finishPickupList()
    {
        $getData = DB::table('list_orders_pickedup')->where('category_id',1)
        ->get();
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $date = date_create($val->order_date); 
                $arr = array(
                    'id' => $val->id,
                    'no_order' => '#'.intval($val->no_order),
                    'client' => $val->name,
                    'date' => date_format($date,'Y/m/d') ,
                    'delivery_fee' => intval($val->delivery_fee),
                    'order_status' => $val->order_status,
                    'payment_status' => $val->payment_status,
                    'payment_method' => $val->method,
                    'payment_method_id' => $val->payment_method_id,
                    'sender_address' => $val->sender_address,
                    'sender_phone' => $val->sender_phone,
                    'receiver_name' => $val->receiver_name,
                    'receiver_phone' => $val->receiver_phone,
                    'receiver_address' => $val->receiver_address,
                    'price' => intval($val->price),
                    'total' => $val->price + $val->delivery_fee,
                    'driver_name' => $val->driver_name
                   
                );
                array_push($data,$arr);
            }
        }else{
            return response()->json('Belum ada order', 404);
        }
        return response()->json(['data' => $data]);
    }

    public function readyToDeliveryList()
    {
        $getData = DB::table('list_orders_ready_to_deliver')->where('category_id',1)
        ->get();
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $date = date_create($val->order_date);
                $arr = array(
                    'id' => $val->id,
                    'no_order' => '#'.$val->no_order,
                    'client' => $val->name,
                    'delivery_fee' => $val->delivery_fee,
                    'order_status' => $val->order_status,
                    'payment_status' => $val->payment_status,
                    'payment_method' => $val->method,
                    'payment_method_id' => $val->method_id,
                    'method_id' => $val->method_id,
                    'driver_name' => $val->driver_name,
                    'date' => date_format($date,'Y/m/d') ,
                );
                array_push($data,$arr);
            }
        }else{
            return response()->json('Belum ada order', 404);
        }

        return response()->json(['data' => $data]);
    }

    public function deliveredList()
    {
        $getData = DB::table('list_orders_delivered')->where('category_id',1)
        ->get();
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $date = date_create($val->order_date);
                $arr = array(
                    'id' => $val->id,
                    'no_order' => '#'.$val->no_order,
                    'client' => $val->name,
                    'delivery_fee' => $val->delivery_fee,
                    'order_status' => $val->order_status,
                    'payment_status' => $val->payment_status,
                    'payment_method' => $val->method,
                    'payment_method_id' => $val->payment_method_id,
                    'pickup_driver' => $val->pickup_driver,
                    'deliver_driver' => $val->deliver_driver,
                    'redeliver_driver' => $val->redeliver_driver,
                    'date' => date_format($date, 'Y/m/d')
                );
                array_push($data,$arr);
            }
        }else{
            return response()->json('Belum ada order', 404);
        }

        return response()->json(['data' => $data]);
    }
    public function deliveredHistoryList()
    {
        $getData = DB::table('list_orders_delivered_history')->where('category_id',1)
        ->get();
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $date = date_create($val->order_date);
                $arr = array(
                    'id' => $val->id,
                    'no_order' => '#'.$val->no_order,
                    'client' => $val->name,
                    'delivery_fee' => $val->delivery_fee,
                    'order_status' => $val->order_status,
                    'payment_status' => $val->payment_status,
                    'payment_method' => $val->method,
                    'pickup_driver' => $val->pickup_driver,
                    'deliver_driver' => $val->deliver_driver,
                    'date' => date_format($date,'Y/m/d')
                );
                array_push($data,$arr);
            }
        }else{
            return response()->json('Belum ada order', 404);
        }

        return response()->json(['data' => $data]);
    }

    public function reDeliveryList()
    {
        $getData = DB::table('list_orders_redelivery')->where('category_id',1)
        ->get();
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $date = date_create($val->order_date);
                $arr = array(
                    'id' => $val->id,
                    'no_order' => '#'.$val->no_order,
                    'client' => $val->name,
                    'delivery_fee' => $val->delivery_fee,
                    'order_status' => $val->order_status,
                    'payment_status' => $val->payment_status,
                    'payment_method' => $val->method,
                    'payment_method_id' => $val->payment_method_id,
                    'deliver_driver' => $val->deliver_driver,
                    'redeliver_driver' => $val->redeliver_driver,
                    'redeliver_driver_id' => $val->redeliver_driver_id,
                    'date' => date_format($date, 'Y/m/d')
                    
                );
                array_push($data,$arr);
            }
        }else{
            return response()->json('Belum ada order', 404);
        }

        return response()->json(['data' => $data]);
    }

    public function canceledList()
    {
        $getData = DB::table('list_orders_cancel')->where('category_id',1)
        ->get();
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $date = date_create($val->order_date);
                $arr = array(
                    'id' => $val->id,
                    'no_order' => '#'.$val->no_order,
                    'client' => $val->name,
                    'delivery_fee' => $val->delivery_fee,
                    'order_status' => $val->order_status,
                    'payment_status' => $val->payment_status,
                    'payment_method' => $val->method,
                    'date' => date_format($date, 'Y/m/d')
                );
                array_push($data,$arr);
            }
        }else{
            return response()->json('Belum ada order', 404);
        }

        return response()->json(['data' => $data]);
    }

    public function canceledHistoryList()
    {
        $getData = DB::table('list_orders_cancel_history')->where('category_id',1)
        ->get();
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $date = date_create($val->order_date);
                $arr = array(
                    'id' => $val->id,
                    'no_order' => '#'.$val->no_order,
                    'client' => $val->name,
                    'delivery_fee' => $val->delivery_fee,
                    'order_status' => $val->order_status,
                    'payment_status' => $val->payment_status,
                    'payment_method' => $val->method,
                    'date' => date_format($date, 'Y/m/d')
                );
                array_push($data,$arr);
            }
        }else{
            return response()->json('Belum ada order', 404);
        }

        return response()->json(['data' => $data]);
    }

    public function returnList()
    {
        $getData = DB::table('list_orders_return')->where('category_id',1)
        ->get();
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $arr = array(
                    'id' => $val->id,
                    'no_order' => '#'.$val->no_order,
                    'client' => $val->name,
                    'delivery_fee' => $val->delivery_fee,
                    'order_status' => $val->order_status,
                    'payment_status' => $val->payment_status,
                    'payment_method' => $val->method,
                    'pickup_driver' => $val->pickup_driver,
                    'deliver_driver' => $val->deliver_driver,
                    'return_driver' => $val->return_driver,
                    'status' => $val->status
                );
                array_push($data,$arr);
            }
        }else{
            return response()->json('Belum ada order', 404);
        }

        return response()->json(['data' => $data]);
    }

    public function createOrder(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required',
            'weight' => 'required',
            'volume' =>'required',
            'price' => 'required'
            // 'photo' => 'required|image',
        ]);
        if($request->input('user_id') == null){
            $id = auth()->user()->id;  
        }else{
            $id = $request->input('user_id');
        }
        $numb = rand(0,999999);
        $date = str_shuffle(date('dY'));
        $code = substr($numb + $date, 0, 6);
        $district = DB::table('user_profiles')->where('user_id',$id)->first('district_id')->district_id;
        $village = DB::table('user_profiles')->where('user_id',$id)->first('village_id')->village_id;
        $price = $request->input('price');
        $category_id = $request->input('category_id');

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
            'city_id' =>  $request->input('city'),
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
        if($category_id == 1){
            $order_status = 1; 
        if (count($checkCust) == 0 && $request->input('village') === "null"){
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
                    Join users 
                    ON users.id = drivers.user_id
                    WHERE
                    drivers.district_placementt  LIKE "%'.$district.'%" 
                        AND
                        drivers.driver_category_id = 1
                        AND
                        coalesce(count, 0) < 25
                        AND
                        users.online = 1
                    ORDER BY
                        count ASC
                    LIMIT 1
                    ');
                    
        }
        elseif(count($checkCust) == 0 && $request->input('village') !== "null"){
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
                                Join users 
                                ON users.id = drivers.user_id
                                WHERE
                                drivers.district_placementt LIKE "%'.$district.'%"
                                AND
                                drivers.village_placement LIKE "%'.$village.'%"
                                AND
                                drivers.driver_category_id = 1
                                AND
                                coalesce(count, 0) < 25
                                AND
                                users.online = 1
                                ORDER BY
                                    count ASC
                                LIMIT 1');
        }
        else
        {
            $getDriver = $checkCust[0]->driver_id_pickup;
        }

       }else{
           $order_status = 4;
        if($request->input('village') === "null"){
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
                Join users 
                ON users.id = drivers.user_id
            WHERE
                drivers.district_placement LIKE "%'.$district.'%" 
                AND
                drivers.driver_category_id = 2
                AND
                coalesce(count, 0) < 25
                AND
                users.online = 1
            ORDER BY
                count ASC
            LIMIT 1
            ');
            
        }
        elseif($request->input('village') !== "null"){
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
                        Join users 
                        ON users.id = drivers.user_id
                        WHERE
                        drivers.district_placement LIKE "%'.$district.'%"
                        AND
                        drivers.village_placement LIKE "%'.$village.'%"
                        AND
                        drivers.driver_category_id = 2
                        AND
                        coalesce(count, 0) < 25
                        AND
                        users.online = 1
                        ORDER BY
                            count ASC
                        LIMIT 1');
                        $available = DB::table('drivers')->where('user_id',$driver)->update(['available'=> 1]);                    
                }
                    }

        if (!empty($getDriver) && is_array($getDriver)){
        $driver =  $getDriver[0]->user_id;  
        $this->sendPickupNotif(3);
        }else{
            $driver = 2;                
        }
            $order = new Order;
            $order->user_id = $id ;
            $order->no_order = $code;
            $order->order_statuses_id = $order_status ;
            $order->driver_id_pickup = $driver;
            $order->created_at = date('Y-m-d H:i:s',strtotime("+7 hours"));
            $order->driver_id_deliver = $request->input('delivery_driver');
            if($category_id == 2){
                $order->driver_id_deliver = $driver;
            }
            $order->delivery_address_id = $deliv_address;
            $order->payment_id = $payment_id;
            $order->pickup_status = 0;
            $order->category_id = $category_id;
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
                'description' => $request->input('description_address'),
                'delivery_fee' => $delivery_fee,
                'photo' => $img

            ]);
                $getCount = DB::table('count_driver_order')->where('id',$driver)->first();
            if(!empty($detail)){
                DB::table('drivers')
                ->where('user_id',$getCount->id)
                ->update(['total_orders' => $getCount->count]); 
                return response()->json("Order berhasil di buat", 200);
            }

            return response()->json("Order gagal di buat");

    }

    public function userListCustomer()
    {
        $data = DB::table('user_list')->where('role','customer')->get();
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
                'city' =>$special_city
            ], 200);
        }
        return response()->json('Data Tidak Ditemukan');
    }

    public function status(Request $request)
    {   
        date_default_timezone_set('Asia/Bangkok');
        $req = $request->all();
        foreach ($req as $key => $val) {
            if($val['status'] == 2){
                Order::join('payments','payments.id','orders.payment_id')
                     ->where('orders.id',intval($val['id']))
                     ->update([
                     'pickup_status' => 0 ,
                     'order_statuses_id' =>$val['status'],
                     'pickup_at' => date('Y-m-d H:i:s')
                     ]);
            //Barang diambil dengan talangan & ongkir di tanggung pengirim            
            }elseif($val['status'] == 1){
                Order::where('id',intval($val['id']))
             ->update([
                'order_statuses_id' =>$val['status']
             ]);
            }
            elseif($val['status'] == 3 && $val['bailout'] == 1 && $val['method'] == 1 ){
            $getAmount = DB::table('order_details')->where('orders_id',$val['id'])->select('price','delivery_fee')->get();
            $user_id = DB::table('orders')->where('id',$val['id'])->select('driver_id_pickup','no_order')->get();
            $wallet_id = DB::select('select id from wallet where user_id = '.intval($user_id[0]->driver_id_pickup).' AND CAST(created_at as DATE) = CURRENT_DATE  AND status = 0 OR user_id ='.intval($user_id[0]->driver_id_pickup).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
            $debit = DB::table('wallet_transaction')
                        ->insert([
                            'wallet_id' => $wallet_id[0]->id,
                            'type' => 'debit',
                            'description' => 'Talangan Barang (#'.$user_id[0]->no_order.')',
                            'amount' => -$getAmount[0]->price 
                            ]);
            $credit = DB::table('wallet_transaction')
            ->insert([
                'wallet_id' => $wallet_id[0]->id,
                'type' => 'credit',
                'description' => 'Ongkir (#'.$user_id[0]->no_order.')',
                'amount' => $getAmount[0]->delivery_fee
                ]);
            Order::where('id',intval($val['id']))
             ->update([
                'pickup_status' => 1 ,
                'order_statuses_id' =>$val['status'],
                'bailout_id' => $val['bailout'],
                'pickup_at' => date('Y-m-d H:i:s')  
             ]);
             //Barang diambil tanpa talangan & ongkir di tanggung pengirim
            }elseif($val['status'] == 3 && $val['bailout'] == 2 && $val['method'] == 1 ){
                $getAmount = DB::table('order_details')->where('orders_id',$val['id'])->select('price','delivery_fee')->get();
                $user_id = DB::table('orders')->where('id',$val['id'])->select('driver_id_pickup','no_order')->get();
                $wallet_id = DB::select('select id from wallet where user_id = '.intval($user_id[0]->driver_id_pickup).' AND CAST(created_at as DATE) = CURRENT_DATE AND status = 0 OR user_id ='.intval($user_id[0]->driver_id_pickup).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
                $credit = DB::table('wallet_transaction')
                ->insert([
                    'wallet_id' => $wallet_id[0]->id,
                    'type' => 'credit',
                    'description' => 'Ongkir (#'.$user_id[0]->no_order.')',
                    'amount' => $getAmount[0]->delivery_fee
                    ]);
                Order::where('id',intval($val['id']))
                 ->update([
                    'pickup_status' => 1 ,
                    'order_statuses_id' =>$val['status'],
                    'bailout_id' => $val['bailout'],
                    'pickup_at' => date('Y-m-d H:i:s')  
                 ]);
                 //Barang diambil dengan talangan & ongkir di tanggung penerima
                }elseif($val['status'] == 3 && $val['bailout'] == 1 && $val['method'] == 2 ){
                $getAmount = DB::table('order_details')->where('orders_id',$val['id'])->select('price','delivery_fee')->get();
                $user_id = DB::table('orders')->where('id',$val['id'])->select('driver_id_pickup','no_order')->get();
                $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($user_id[0]->driver_id_pickup).' AND CAST(created_at as date) = CURRENT_DATE AND status = 0 OR user_id ='.intval($user_id[0]->driver_id_pickup).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
                $debit = DB::table('wallet_transaction')
                            ->insert([
                                'wallet_id' => $wallet_id[0]->id,
                                'type' => 'debit',
                                'description' => 'Talangan Barang (#'.$user_id[0]->no_order.')',
                                'amount' => -$getAmount[0]->price 
                                ]);
                Order::where('id',intval($val['id']))
                 ->update([
                    'pickup_status' => 1 ,
                    'order_statuses_id' =>$val['status'],
                    'bailout_id' => $val['bailout'],
                    'pickup_at' => date('Y-m-d H:i:s')  
                 ]);
                 //Barang diambil tanpa talangan & ongkir di tanggung penerima
                }elseif($val['status'] == 3 && $val['bailout'] == 2 && $val['method'] == 2 ){
                    Order::where('id',intval($val['id']))
                     ->update([
                        'pickup_status' => 1 ,
                        'order_statuses_id' =>$val['status'],
                        'bailout_id' => $val['bailout'],
                        'pickup_at' => date('Y-m-d H:i:s')  
                     ]);
                //Driver Assign
                }elseif($val['status'] == 4){
                    if ($val['driver'] == ""){  
                Order::where('id',intval($val['id']))
                ->update([
                   'order_statuses_id' =>$val['status'],
                   'pickup_at' => date('Y-m-d H:i:s')  
                ]);
                }else{
                    Order::where('id',intval($val['id']))
                ->update([
                   'order_statuses_id' =>$val['status'],
                   'driver_id_deliver' =>$val['driver'],
                   'pickup_at' => date('Y-m-d H:i:s')  
                ]);
                }
                //Barang diambil ongkir di tanggung pengirim
            }elseif($val['status'] == 3 && $val['bailout'] == 2 && $val['method'] == 3 ){
                $getAmount = DB::table('order_details')->where('orders_id',$val['id'])->select('price','delivery_fee')->get();
                $user_id = DB::table('orders')->where('id',$val['id'])->select('driver_id_pickup','no_order')->get();
                $wallet_id = DB::select('select id from wallet where user_id = '.intval($user_id[0]->driver_id_pickup).' AND CAST(created_at as DATE) = CURRENT_DATE AND status = 0 OR user_id ='.intval($user_id[0]->driver_id_pickup).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
                $credit = DB::table('wallet_transaction')
                ->insert([
                    'wallet_id' => $wallet_id[0]->id,
                    'type' => 'credit',
                    'description' => 'Ongkir (#'.$user_id[0]->no_order.')',
                    'amount' => $getAmount[0]->delivery_fee
                    ]);
                Order::where('id',intval($val['id']))
                 ->update([
                    'pickup_status' => 1 ,
                    'order_statuses_id' =>$val['status'],
                    'bailout_id' => $val['bailout'],
                    'pickup_at' => date('Y-m-d H:i:s')  
                 ]);
                 //Barang diambil ongkir di tanggung penerima
                }elseif($val['status'] == 3 && $val['bailout'] == 2 && $val['method'] == 4 ){
                    Order::where('id',intval($val['id']))
                     ->update([
                        'pickup_status' => 1 ,
                        'order_statuses_id' =>$val['status'],
                        'bailout_id' => $val['bailout'],
                        'pickup_at' => date('Y-m-d H:i:s')  
                     ]);
                //Barang diantar dengan tagihan
                }elseif($val['status'] == 5 && $val['method'] == 1 ){
                    $getAmount = DB::table('order_details')->where('orders_id',$val['id'])->select('price','delivery_fee')->get();
                    $order = DB::table('orders')->join('delivery_addresses','delivery_addresses.id','orders.delivery_address_id')->where('orders.id',$val['id'])->select('driver_id_deliver','no_order','delivery_addresses.district')->get();
                    $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) = CURRENT_DATE AND status = 0 OR user_id ='.intval($user_id[0]->driver_id_deliver).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
                    $credit = DB::table('wallet_transaction')
                                ->insert([
                                    'wallet_id' => $wallet_id[0]->id,
                                    'type' => 'credit',
                                    'description' => 'Pembayaran Barang (#'.$order[0]->no_order.')',
                                    'amount' => $getAmount[0]->price 
                                    ]);
                Order::where('id',intval($val['id']))
                ->update([
                   'order_statuses_id' =>$val['status'],
                   'delivered_at' => date('Y-m-d H:i:s')  
                ]);
            }
            //Barang diantar dengan tagihan dan ongkir
            elseif($val['status'] == 5 && $val['method'] == 2 ){
                $getAmount = DB::table('order_details')->where('orders_id',$val['id'])->select('price','delivery_fee')->get();
                $order = DB::table('orders')->join('delivery_addresses','delivery_addresses.id','orders.delivery_address_id')->where('orders.id',$val['id'])->select('driver_id_deliver','no_order','delivery_addresses.district')->get();
                $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) = CURRENT_DATE AND status = 0 OR user_id ='.intval($user_id[0]->driver_id_deliver).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
                $credit = DB::table('wallet_transaction')
                            ->insert([
                                'wallet_id' => $wallet_id[0]->id,
                                'type' => 'credit',
                                'description' => 'Pembayaran Barang (#'.$order[0]->no_order.')',
                                'amount' => $getAmount[0]->price 
                                ]);         
                $credit2 = DB::table('wallet_transaction')
                ->insert([
                    'wallet_id' => $wallet_id[0]->id,
                    'type' => 'credit',
                    'description' => 'Ongkir (#'.$order[0]->no_order.')',
                    'amount' => $getAmount[0]->delivery_fee
                    ]);
            Order::where('id',intval($val['id']))
            ->update([
               'order_statuses_id' =>$val['status'],
               'delivered_at' => date('Y-m-d H:i:s')  
            ]);
            //Barang diantar tanpa tagihan dan ongkir
            }elseif($val['status'] == 5 && $val['method'] == 3 ){
            $order = DB::table('orders')->join('delivery_addresses','delivery_addresses.id','orders.delivery_address_id')->where('orders.id',$val['id'])->select('driver_id_deliver','no_order','delivery_addresses.district')->get();
            Order::where('id',intval($val['id']))
            ->update([
            'order_statuses_id' =>$val['status'],
            'delivered_at' => date('Y-m-d H:i:s')  
            ]);
            //Barang diantar dengan ongkir tanpa tagihan
            }elseif($val['status'] == 5 && $val['method'] == 4 ){
            $getAmount = DB::table('order_details')->where('orders_id',$val['id'])->select('price','delivery_fee')->get();
            $order = DB::table('orders')->join('delivery_addresses','delivery_addresses.id','orders.delivery_address_id')->where('orders.id',$val['id'])->select('driver_id_deliver','no_order','delivery_addresses.district')->get();
            $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) = CURRENT_DATE AND status = 0 OR user_id ='.intval($user_id[0]->driver_id_deliver).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
            $credit2 = DB::table('wallet_transaction')
                ->insert([
                    'wallet_id' => $wallet_id[0]->id,
                    'type' => 'credit',
                    'description' => 'Ongkir (#'.$order[0]->no_order.')',
                    'amount' => $getAmount[0]->delivery_fee
                    ]);
            Order::where('id',intval($val['id']))
            ->update([
            'order_statuses_id' =>$val['status'],
            'delivered_at' => date('Y-m-d H:i:s')  
            ]);
            //Cancel Order
            }elseif($val['status'] == 6 ){
                Order::where('id',intval($val['id']))
                ->update([
                   'order_statuses_id' =>$val['status'],  
                ]);
               }
            //Retur 
            elseif($val['status'] == 7  && $val['method'] == 1){
                DB::table('return')->insert(['id_orders' => intval($val['id'])]);
                $credit = DB::table('wallet_transaction')
                ->insert([
                    'wallet_id' => $wallet_id[0]->id,
                    'type' => 'credit',
                    'description' => 'Pengembalian Pembayaran Barang (#'.$order[0]->no_order.')',
                    'amount' => $getAmount[0]->price 
                    ]);         
                $debit = DB::table('wallet_transaction')
                ->insert([
                    'wallet_id' => $wallet_id[0]->id,
                    'type' => 'debit',
                    'description' => 'pengembalian Ongkir (#'.$order[0]->no_order.')',
                    'amount' => -$getAmount[0]->delivery_fee
                    ]);
                Order::where('id',intval($val['id']))
                ->update([
                   'order_statuses_id' =>$val['status'],  
                ]);
               }
               elseif($val['status'] == 7  && $val['method'] == 2){
                DB::table('return')->insert(['id_orders' => intval($val['id'])]);
                $credit = DB::table('wallet_transaction')
                ->insert([
                    'wallet_id' => $wallet_id[0]->id,
                    'type' => 'credit',
                    'description' => 'Pengembalian Pembayaran Barang (#'.$order[0]->no_order.')',
                    'amount' => $getAmount[0]->price 
                    ]);         
                Order::where('id',intval($val['id']))
                ->update([
                   'order_statuses_id' =>$val['status'],  
                ]);
               }

               elseif($val['status'] == 7  && $val['method'] == 3){
                DB::table('return')->insert(['id_orders' => intval($val['id'])]);
                $debit = DB::table('wallet_transaction')
                ->insert([
                    'wallet_id' => $wallet_id[0]->id,
                    'type' => 'debit',
                    'description' => 'pengembalian Ongkir (#'.$order[0]->no_order.')',
                    'amount' => - $getAmount[0]->delivery_fee
                    ]);
                Order::where('id',intval($val['id']))
                ->update([
                   'order_statuses_id' =>$val['status'],  
                ]);
               }
               elseif($val['status'] == 7  && $val['method'] == 4){
                DB::table('return')->insert(['id_orders' => intval($val['id'])]);
                Order::where('id',intval($val['id']))
                ->update([
                   'order_statuses_id' =>$val['status'],  
                ]);
               }
            //Pengiriman Ulang
            elseif($val['status'] == 8){
            Order::where('id',intval($val['id']))
            ->update([
                'order_statuses_id' =>$val['status'],  
            ]);
            }
        }
        return response()->json('data updated successfully');
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

    public function detailOrder($no_order)
    {
        $data = Order::select(
            'orders.id',
            'orders.no_order',
            'orders.order_statuses_id',
            'order_details.name',
            'order_details.delivery_fee',
            'order_details.price',
            'order_details.weight',
            'order_details.volume',
            'order_details.photo',
            'order_details.phone as receiver_phone',
            'order_details.phone2 as receiver_phone2',
            'order_details.description',
            'users.name as client',
        )
        ->join('order_details','order_details.orders_id','orders.id')
        ->join('users','users.id','orders.user_id')
        ->where('orders.id',$no_order)
        ->get();


        $data_driver = Order::select('up.name as driver_pickup_name','ud.name as driver_delivery_name')
        ->leftjoin('users as up','orders.driver_id_pickup','up.id')
        ->leftjoin('users as ud','orders.driver_id_deliver','ud.id')
        ->where('orders.id',$no_order)
        ->get();

        $data_payment = Order::with('Payment')->get('payment_id');
        return $data_payment;
        if(count($data) > 0){
            return response()->json([
                'data' => $data,
                'driver' => $data_driver
            ]);
        }
        return response()->json('Data Not Found');
    }

    public function area()
    {
        $district = DB::table('districts')->get();
        $village = DB::table('villages')->get();
        $special_city = DB::table('city')->join('service_area','city.id','service_area.city_id')->orderBy('nama')->get();

        return response()->json([
            'district' => $district,
            'village' => $village,
            'special_city' => $special_city
            ]);
    }

    public function editOrder($no_order)
    {
        $data = DB::table('edit_orders')
                    ->where('no_order',$no_order)    
                    ->get();

        if($data){
            return response()->json([
                'data' => $data
            ]);
        }
        return response()->json('Data Not Found',401);
    }


    public function updateOrder(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required',
            'weight' => 'required',
            'volume' =>'required',
            'price' => 'required',
            'district' =>'required',
            'payment_method' =>'required',
        ]);
    
        $user_id = $request->input('user_id');
        $id = $request->input('order_id');
        $district = DB::table('user_profiles')->where('user_id',$id)->first('district_id')->district_id;
        $village = DB::table('user_profiles')->where('user_id',$id)->first('village_id')->village_id;

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

        //Create Delivery Address
        $deliv_address = DB::table('delivery_addresses')->where('id',$request->input('delivery_address_id'))
            ->update([
                'address' =>   $request->input('receiver_address'),
                'description' =>  $request->input('description_address'),
                'city' =>  $request->input('city'),
                'district' =>  $request->input('district'),
                'village' =>  $request->input('village'),
                'latitude' => $request->input('latitude'),
                'longitude' =>  $request->input('longitude')
        ]);
        

        $detail = DB::table('order_details')->where('orders_id', $id)->Update([
            'orders_id' => $id_order,
                'name' => $request->input('name'),
                'price' => $request->input('price'),
                'description' => $request->input('description_order'),
                'weight' => $request->input('weight'),
                'volume' => $request->input('volume'),
                'receiver' => $request->input('receiver_name'),
                'phone' => $request->input('receiver_phone'),
                'description' => $request->input('description_address'),
                'delivery_fee' => $delivery_fee,
                'photo' => $img
        ]);

        if($detail){
            return response()->json("Data Updated Successfully", 200);
        }

        return response()->json("Data Failed to Update");

    }

    //Driver

    public function driverFilterList(Request $request)
    {
        $req = $request->all();
        $district = DB::select('
                        SELECT
                        delivery_addresses.district
                        FROM
                        delivery_addresses
                        INNER JOIN
                        orders
                        ON 
                        delivery_addresses.id = orders.delivery_address_id
                        WHERE orders.id = '.$req[0]['id']);
        $data = DB::table('driver_list')->where('district_placement_id',$district[0]->district)->select('id','name')->get();
        if(!empty($data)){
            return response()->json(['data' => $data], 200);
        }
        return response()->json('Data Not Found', 200);
    }

    public function changeDriverFilterList(Request $request)
    {
        $req = $request->all();
        $district = DB::select('
                            SELECT
                            orders.user_id, 
                            user_profiles.user_id, 
                            user_profiles.district_id AS district, 
                            user_profiles.village_id AS village
                            FROM
                            orders
                            INNER JOIN
                            user_profiles
                            ON 
                            orders.user_id = user_profiles.user_id
                            WHERE orders.id = '.$req[0]['id']);
        // $data = DB::table('driver_list')->where('district_placement_id',$district[0]->district)->where('village_placement_id', 'like', '%'.$district[0]->village.'%')->select('id','name')->get();                            
        $data = DB::table('driver_list')->where('online',1)->select('id','name')->get();
        if(!empty($data)){
            return response()->json(['data' => $data], 200);
        }
        return response()->json('Data Not Found', 200);
    }

    public function changeDriverFilterListExp(Request $request)
    {
        $req = $request->all();
        $district = DB::select('
                            SELECT
                            orders.user_id, 
                            user_profiles.user_id, 
                            user_profiles.district_id AS district, 
                            user_profiles.village_id AS village
                        FROM
                            orders
                            INNER JOIN
                            user_profiles
                            ON 
                            orders.user_id = user_profiles.user_id  
                            WHERE orders.id = '.$req[0]['id']);
        $data = DB::table('driver_list_exp')->where('online',1)->select('id','name')->get();
        if(!empty($data)){
            return response()->json(['data' => $data], 200);
        }
        return response()->json('Data Not Found', 200);
    }

    public function DeliveryDriverFilterList(Request $request)
    {
        $req = $request->all();
        $district = DB::select('
                            SELECT
                            orders.user_id, 
                            delivery_addresses.district,
                            delivery_addresses.village
                            FROM
                            orders
                            INNER JOIN
                            delivery_addresses
                            ON 
                            orders.delivery_address_id = delivery_addresses.id
                            WHERE orders.id = '.$req[0]['id']); 
        
        $data = DB::table('driver_list')->where('online',1)->select('id','name')->get();
        if(!empty($data)){
            return response()->json(['data' => $data], 200);
        }
        return response()->json('Data Not Found', 200);
    }

    public function driverAssignRedelivery(Request $request)
    {
        $req = $request->all();
        $update = DB::table('orders')->where('id',$req[0]['id'])->Update([
            'driver_id_redeliver' => $req[0]['driver']
        ]);

        if($update){
            return response()->json('Data Updated Successfully', 200);
        }
        return response()->json('Data Update Failed');
    }

    public function driverAssignReturn(Request $request)
    {
        $req = $request->all();
        $update = DB::table('orders')->where('id',$req[0]['id'])->Update([
            'driver_id_return' => $req[0]['driver'],
            'order_statuses_id' => 9
        ]);

        if($update){
            return response()->json('Data Updated Successfully', 200);
        }
        return response()->json('Data Update Failed');
    }

    public function show($id)
    {
        $order = DB::table('list_orders_barcode')->where('id',$id)->first();
        if(!empty($order)){
        $date = date_create($order->order_date);    
        $data = array(
                    'id' => intval($order->id),
                    'no_order' => $order->no_order,
                    'client' => $order->name,
                    'date' => date_format($date,'Y/m/d') ,
                    'delivery_fee' => intval($order->delivery_fee),
                    'product_name' => $order->product_name,
                    'order_status' => $order->order_status,
                    'payment_status' => $order->payment_status,
                    'payment_method' => $order->method,
                    'sender_address' => $order->sender_address,
                    'sender_phone' => $order->sender_phone,
                    'receiver_name' => $order->receiver_name,
                    'receiver_phone' => $order->receiver_phone,
                    'receiver_address' => $order->receiver_address,
                    'receiver_district' => $order->receiver_district,
                    'delivery_driver_name' => $order->deliver_driver_name,
                    'price' => intval($order->price),
                    'total' => $order->price + $order->delivery_fee,
                    'driver_name' => $order->driver_name
        );
        
        $d = DB::table('driver_list')->join('users','users.id','driver_list.id')->where('users.online',1)->get();
        $driver = array();
        foreach($d as $val){
            $arr = array(
                'id' => intval($val->id),
                'name' => $val->name
            );

            array_push($driver,$arr);
        }
        
            return response()->json(['order' => $data , 'driver' => $driver], 200);
      }
        return response()->json('Data Not Found', 200);
    } 

    public function deliveryAssign(Request $request)
    {
        $a = $request->input('id_driver');
        $b = DB::table('orders')->where('id',$request->input('id'))->first('driver_id_deliver')->driver_id_deliver;
        if($a == null || $a == ""){
            $update = DB::table('orders')->where('id',$request->input('id'))
                        ->update([
                            'order_statuses_id' => 4,
                            'driver_id_deliver' => $b,
                            ]);
        }else{
            $update = DB::table('orders')->where('id',$request->input('id'))
            ->update([
                'order_statuses_id' => 4,
                'driver_id_deliver' => $a,
                ]);
        }
        return response()->json('Data Updated Successfully', 200);
        
    }

    public function driverDeliveryAssign(Request $request)
    {
        $req = $request->all();
        foreach($req as $val){
        $update = DB::table('orders')->where('id',$req[0]['id'])
                    ->update([
                        'driver_id_deliver' => $req[0]['driver'],
                        ]);
        }
        return response()->json('Data Updated Successfully', 200);
        
    }
 
    public function changeDriverPickUp(Request $request)
    {
        $req = $request->all();
        $update = DB::table('orders')->where('id',$req[0]['id'])->Update([
            'driver_id_pickup' => $req[0]['driver']
        ]);

        if($update){
            return response()->json('Data Updated Successfully', 200);
        }
        return response()->json('Data Update Failed');
    }
    public function changeDriverExp(Request $request)
    {
        $req = $request->all();
        $update = DB::table('orders')->where('id',$req[0]['id'])->Update([
            'driver_id_pickup' => $req[0]['driver'],
            'driver_id_deliver' => $req[0]['driver']
        ]);

        if($update){
            return response()->json('Data Updated Successfully', 200);
        }
        return response()->json('Data Update Failed');
    }

    public function changeDriverDelivery(Request $request)
    {
        $req = $request->all();
        $update = DB::table('orders')->where('id',$req[0]['id'])->Update([
            'driver_id_deliver' => $req[0]['driver']
        ]);

        if($update){
            return response()->json('Data Updated Successfully', 200);
        }
        return response()->json('Data Update Failed');
    }

    //Order Express

    public function allExpress()
    {
        $getData = DB::select('
        SELECT
	`orders`.`id` AS `id`,
	`orders`.`no_order` AS `no_order`,
	`order_statuses`.`status` AS `order_status`,
	`orders`.`created_at` AS `order_date`,
	`orders`.`category_id` AS `category_id`,
	`return`.`status` AS `return_status`,
	`driver_queue`.`d_driver` AS `delivery_driver`,
	`driver_queue`.`p_driver` AS `driver_name`,
	`cust_receiver_add`.`receiver_district` AS `receiver_district`,
	`cust_receiver_add`.`receiver_village` AS `receiver_village`,
	`cust_receiver_add`.`receiver_address` AS `receiver_address`,
    `cust_receiver_add`.`receiver_city` AS `receiver_city`,
	`order_details`.`receiver` AS `receiver_name`,
	`order_details`.`phone` AS `receiver_phone`,
	`users`.`name` AS `client`,
	`user_profiles`.`phone` AS `sender_phone`,
	`user_profiles`.`address` AS `sender_address`,
	`order_details`.`price` AS `price`,
	`order_details`.`delivery_fee` AS `delivery_fee`,
	`order_details`.`name` AS `name`,
	`payment`.`payment_status` AS `payment_status`,
	`payment`.`payment_method` AS `method`,
	`payment`.`payment_method_id` AS `payment_method_id` 
FROM
	((((((((((
									`orders`
									    LEFT JOIN `order_statuses` ON ( `orders`.`order_statuses_id` = `order_statuses`.`id` ))
                                    LEFT JOIN `users` ON ( `orders`.`user_id` = `users`.`id` ))
								  LEFT JOIN `user_profiles` ON ( `orders`.`pickup_address` = `user_profiles`.`id` ))
								LEFT JOIN `delivery_addresses` ON ( `orders`.`delivery_address_id` = `delivery_addresses`.`id` ))
							LEFT JOIN `return` ON ( `orders`.`id` = `return`.`id_orders` ))
						LEFT JOIN `driver_queue` ON ( `orders`.`id` = `driver_queue`.`id` ))
					LEFT JOIN `cust_receiver_add` ON ( `orders`.`id` = `cust_receiver_add`.`id` ))
				LEFT JOIN `order_details` ON ( `orders`.`id` = `order_details`.`orders_id` ))
			LEFT JOIN `user_list` ON ( `orders`.`user_id` = `user_list`.`id` ))
	LEFT JOIN `payment` ON ( `orders`.`payment_id` = `payment`.`payment_id` )) 
WHERE
	cast( `orders`.`created_at` AS date ) = curdate() AND
    orders.category_id = 2
GROUP BY orders.id
ORDER BY
	`orders`.`id` DESC
        ');
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $date = date_create($val->order_date); 
                $arr = array(
                    'id' => $val->id,   
                    'no_order' => '#'.intval($val->no_order),
                    'client' => $val->client,
                    'date' => date_format($date,'Y-m-d H:i:s') ,
                    'delivery_fee' => intval($val->delivery_fee),
                    'order_status' => $val->order_status,
                    'payment_status' => $val->payment_status,
                    'payment_method' => $val->method,
                    'payment_method_id' => $val->payment_method_id,
                    'sender_address' => $val->sender_address,
                    'sender_phone' => $val->sender_phone,
                    'receiver_name' => $val->receiver_name,
                    'receiver_phone' => $val->receiver_phone,
                    'receiver_address' => $val->receiver_address,
                    'receiver_district' => $val->receiver_district,
                    'receiver_village' => $val->receiver_village,
                    'price' => intval($val->price),
                    'total' => $val->price + $val->delivery_fee,
                    'driver_name' => $val->driver_name,
                    'delivery_driver' => $val->delivery_driver,
                    'return_status' => intval($val->return_status)
                   
                );
                array_push($data,$arr);
            }
        }else{
            return response()->json('Belum ada order', 404);
        }
        return response()->json(['data' => $data]);
    }

    public function pickingUpExp()
    {
      $getData = DB::table('list_orders_ready_to_deliver')
      ->where('category_id',2)
      ->where('pickup_status',0)
      ->get();  
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $date = date_create($val->order_date);
                $arr = array(
                    'id' => $val->id,
                    'no_order' => '#'.$val->no_order,
                    'client' => $val->name,
                    'delivery_fee' => $val->delivery_fee,
                    'order_status' => $val->order_status,
                    'payment_status' => $val->payment_status,
                    'driver_name' => $val->driver_name,
                    'payment_method' => $val->method,
                    'payment_method_id' => $val->method_id,
                    'category_id' => $val->category_id,
                    'order_date' => date_format($date,'Y/m/d'),
                    'deliver_driver_name' => $val->deliver_driver_name,
                );
                array_push($data,$arr);
            }
        }else{
            return response()->json('Belum ada order', 404);
        }
        return response()->json(['data' => $data]);
    }

    public function finishPickupListExp()
    {
        $getData = DB::table('list_orders_pickedup')->where('category_id',2)
        ->get();
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $date = date_create($val->order_date); 
                $arr = array(
                    'id' => $val->id,
                    'no_order' => '#'.intval($val->no_order),
                    'client' => $val->name,
                    'date' => date_format($date,'Y/m/d') ,
                    'delivery_fee' => intval($val->delivery_fee),
                    'order_status' => $val->order_status,
                    'payment_status' => $val->payment_status,
                    'payment_method' => $val->method,
                    'sender_address' => $val->sender_address,
                    'sender_phone' => $val->sender_phone,
                    'receiver_name' => $val->receiver_name,
                    'receiver_phone' => $val->receiver_phone,
                    'receiver_address' => $val->receiver_address,
                    'price' => intval($val->price),
                    'total' => $val->price + $val->delivery_fee,
                    'driver_name' => $val->driver_name
                   
                );
                array_push($data,$arr);
            }
        }else{
            return response()->json('Belum ada order', 404);
        }
        return response()->json(['data' => $data]);
    }

    public function readyToDeliveryListExp()
    {
        $getData = DB::table('list_orders_ready_to_deliver')->where('category_id',2)->where('pickup_status',1)
        ->get();
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $date = date_create($val->order_date);
                $arr = array(
                    'id' => $val->id,
                    'no_order' => '#'.$val->no_order,
                    'client' => $val->name,
                    'delivery_fee' => $val->delivery_fee,
                    'order_status' => $val->order_status,
                    'payment_status' => $val->payment_status,
                    'payment_method' => $val->method,
                    'method_id' => $val->method_id,
                    'driver_name' => $val->driver_name,
                    'date' => date_format($date, 'Y/m/d')
                );
                array_push($data,$arr);
            }
        }else{
            return response()->json('Belum ada order', 404);
        }

        return response()->json(['data' => $data]);
    }

    public function deliveredListExp()
    {
        $getData = DB::table('list_orders_delivered')->where('category_id',2)
        ->get();
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $arr = array(
                    'id' => $val->id,
                    'no_order' => '#'.$val->no_order,
                    'client' => $val->name,
                    'delivery_fee' => $val->delivery_fee,
                    'order_status' => $val->order_status,
                    'payment_status' => $val->payment_status,
                    'payment_method' => $val->method,
                    'pickup_driver' => $val->pickup_driver,
                    'deliver_driver' => $val->deliver_driver
                );
                array_push($data,$arr);
            }
        }else{
            return response()->json('Belum ada order', 404);
        }

        return response()->json(['data' => $data]);
    }
    public function deliveredHistoryListExp()
    {
        $getData = DB::table('list_orders_delivered_history')->where('category_id',2)
        ->get();
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $arr = array(
                    'id' => $val->id,
                    'no_order' => '#'.$val->no_order,
                    'client' => $val->name,
                    'delivery_fee' => $val->delivery_fee,
                    'order_status' => $val->order_status,
                    'payment_status' => $val->payment_status,
                    'payment_method' => $val->method,
                    'pickup_driver' => $val->pickup_driver,
                    'deliver_driver' => $val->deliver_driver
                );
                array_push($data,$arr);
            }
        }else{
            return response()->json('Belum ada order', 404);
        }

        return response()->json(['data' => $data]);
    }

    public function reDeliveryListExp()
    {
        $getData = DB::table('list_orders_redelivery')->where('category_id',2)
        ->get();
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $arr = array(
                    'id' => $val->id,
                    'no_order' => '#'.$val->no_order,
                    'client' => $val->name,
                    'delivery_fee' => $val->delivery_fee,
                    'order_status' => $val->order_status,
                    'payment_status' => $val->payment_status,
                    'payment_method' => $val->method,
                    'deliver_driver' => $val->deliver_driver,
                    'redeliver_driver' => $val->redeliver_driver
                    
                );
                array_push($data,$arr);
            }
        }else{
            return response()->json('Belum ada order', 404);
        }

        return response()->json(['data' => $data]);
    }

    public function canceledListExp()
    {
        $getData = DB::table('list_orders_cancel')->where('category_id',2)
        ->get();
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $arr = array(
                    'id' => $val->id,
                    'no_order' => '#'.$val->no_order,
                    'client' => $val->name,
                    'delivery_fee' => $val->delivery_fee,
                    'order_status' => $val->order_status,
                    'payment_status' => $val->payment_status,
                    'payment_method' => $val->method
                );
                array_push($data,$arr);
            }
        }else{
            return response()->json('Belum ada order', 404);
        }

        return response()->json(['data' => $data]);
    }

    public function canceledHistoryListExp()
    {
        $getData = DB::table('list_orders_cancel_history')->where('category_id',2)
        ->get();
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $arr = array(
                    'id' => $val->id,
                    'no_order' => '#'.$val->no_order,
                    'client' => $val->name,
                    'delivery_fee' => $val->delivery_fee,
                    'order_status' => $val->order_status,
                    'payment_status' => $val->payment_status,
                    'payment_method' => $val->method
                );
                array_push($data,$arr);
            }
        }else{
            return response()->json('Belum ada order', 404);
        }

        return response()->json(['data' => $data]);
    }

    public function returnListExp()
    {
        $getData = DB::table('list_orders_return')->where('category_id',2)
        ->get();
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $arr = array(
                    'id' => $val->id,
                    'no_order' => '#'.$val->no_order,
                    'client' => $val->name,
                    'delivery_fee' => $val->delivery_fee,
                    'order_status' => $val->order_status,
                    'payment_status' => $val->payment_status,
                    'payment_method' => $val->method,
                    'pickup_driver' => $val->pickup_driver,
                    'deliver_driver' => $val->deliver_driver
                );
                array_push($data,$arr);
            }
        }else{
            return response()->json('Belum ada order', 404);
        }

        return response()->json(['data' => $data]);
    }
    
    public function statusExp(Request $request)
    {   
        date_default_timezone_set('Asia/Bangkok');
        $req = $request->all();
        foreach ($req as $key => $val) {         
            if($val['status'] == 4 && $val['bailout'] == 1 && $val['method'] == 1 ){
            $getAmount = DB::table('order_details')->where('orders_id',$val['id'])->select('price','delivery_fee')->get();
            $user_id = DB::table('orders')->where('id',$val['id'])->select('driver_id_pickup','no_order')->get();
            $wallet_id = DB::select('select id from wallet where user_id = '.intval($user_id[0]->driver_id_pickup).' AND CAST(created_at as DATE) = CURRENT_DATE AND status = 0');
            $debit = DB::table('wallet_transaction')
                        ->insert([
                            'wallet_id' => $wallet_id[0]->id,
                            'type' => 'debit',
                            'description' => 'Talangan Barang (#'.$user_id[0]->no_order.')',
                            'amount' => -$getAmount[0]->price 
                            ]);
            $credit = DB::table('wallet_transaction')
            ->insert([
                'wallet_id' => $wallet_id[0]->id,
                'type' => 'credit',
                'description' => 'Ongkir (#'.$user_id[0]->no_order.')',
                'amount' => $getAmount[0]->delivery_fee
                ]);
            Order::where('id',intval($val['id']))
             ->update([
                'pickup_status' => 1 ,
                'order_statuses_id' =>$val['status'],
                'bailout_id' => $val['bailout'],
                'pickup_at' => date('Y-m-d H:i:s')  
             ]);
             //Barang diambil tanpa talangan & ongkir di tanggung pengirim
            }elseif($val['status'] == 4 && $val['bailout'] == 2 && $val['method'] == 1 ){
                $getAmount = DB::table('order_details')->where('orders_id',$val['id'])->select('price','delivery_fee')->get();
                $user_id = DB::table('orders')->where('id',$val['id'])->select('driver_id_pickup','no_order')->get();
                $wallet_id = DB::select('select id from wallet where user_id = '.intval($user_id[0]->driver_id_pickup).' AND CAST(created_at as DATE) = CURRENT_DATE AND status = 0');
                $credit = DB::table('wallet_transaction')
                ->insert([
                    'wallet_id' => $wallet_id[0]->id,
                    'type' => 'credit',
                    'description' => 'Ongkir (#'.$user_id[0]->no_order.')',
                    'amount' => $getAmount[0]->delivery_fee
                    ]);
                Order::where('id',intval($val['id']))
                 ->update([
                    'pickup_status' => 1 ,
                    'order_statuses_id' =>$val['status'],
                    'bailout_id' => $val['bailout'],
                    'pickup_at' => date('Y-m-d H:i:s')  
                 ]);
                 //Barang diambil dengan talangan & ongkir di tanggung penerima
                }elseif($val['status'] == 4 && $val['bailout'] == 1 && $val['method'] == 2 ){
                $getAmount = DB::table('order_details')->where('orders_id',$val['id'])->select('price','delivery_fee')->get();
                $user_id = DB::table('orders')->where('id',$val['id'])->select('driver_id_pickup','no_order')->get();
                $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($user_id[0]->driver_id_pickup).' AND CAST(created_at as date) = CURRENT_DATE');
                $debit = DB::table('wallet_transaction')
                            ->insert([
                                'wallet_id' => $wallet_id[0]->id,
                                'type' => 'debit',
                                'description' => 'Talangan Barang (#'.$user_id[0]->no_order.')',
                                'amount' => -$getAmount[0]->price 
                                ]);
                Order::where('id',intval($val['id']))
                 ->update([
                    'pickup_status' => 1 ,
                    'order_statuses_id' =>$val['status'],
                    'bailout_id' => $val['bailout'],
                    'pickup_at' => date('Y-m-d H:i:s')  
                 ]);
                 //Barang diambil tanpa talangan & ongkir di tanggung penerima
                }elseif($val['status'] == 4 && $val['bailout'] == 2 && $val['method'] == 2 ){
                    Order::where('id',intval($val['id']))
                     ->update([
                        'pickup_status' => 1 ,
                        'order_statuses_id' =>$val['status'],
                        'bailout_id' => $val['bailout'],
                        'pickup_at' => date('Y-m-d H:i:s')  
                     ]);
                //Driver Assign
                }elseif($val['status'] == 4 && $val['bailout'] == 2 && $val['method'] == 3 ){
                $getAmount = DB::table('order_details')->where('orders_id',$val['id'])->select('price','delivery_fee')->get();
                $user_id = DB::table('orders')->where('id',$val['id'])->select('driver_id_pickup','no_order')->get();
                $wallet_id = DB::select('select id from wallet where user_id = '.intval($user_id[0]->driver_id_pickup).' AND CAST(created_at as DATE) = CURRENT_DATE AND status = 0');
                $credit = DB::table('wallet_transaction')
                ->insert([
                    'wallet_id' => $wallet_id[0]->id,
                    'type' => 'credit',
                    'description' => 'Ongkir (#'.$user_id[0]->no_order.')',
                    'amount' => $getAmount[0]->delivery_fee
                    ]);
                Order::where('id',intval($val['id']))
                 ->update([
                    'pickup_status' => 1 ,
                    'order_statuses_id' =>$val['status'],
                    'bailout_id' => $val['bailout'],
                    'pickup_at' => date('Y-m-d H:i:s')  
                 ]);
                 //Barang diambil ongkir di tanggung penerima
                }elseif($val['status'] == 4 && $val['bailout'] == 2 && $val['method'] == 4 ){
                    Order::where('id',intval($val['id']))
                     ->update([
                        'pickup_status' => 1 ,
                        'order_statuses_id' =>$val['status'],
                        'bailout_id' => $val['bailout'],
                        'pickup_at' => date('Y-m-d H:i:s')  
                     ]);
                //Barang diantar dengan tagihan
                }elseif($val['status'] == 5 && $val['method'] == 1 ){
                    $getAmount = DB::table('order_details')->where('orders_id',$val['id'])->select('price','delivery_fee')->get();
                    $order = DB::table('orders')->join('delivery_addresses','delivery_addresses.id','orders.delivery_address_id')->where('orders.id',$val['id'])->select('driver_id_deliver','no_order','delivery_addresses.district')->get();
                    $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) = CURRENT_DATE');
                    $credit = DB::table('wallet_transaction')
                                ->insert([
                                    'wallet_id' => $wallet_id[0]->id,
                                    'type' => 'credit',
                                    'description' => 'Pembayaran Barang (#'.$order[0]->no_order.')',
                                    'amount' => $getAmount[0]->price 
                                    ]);
                    $driver = DB::table('drivers')->where('user_id',$order[0]->driver_id_deliver)->update(['district_placement' => $order[0]->district]);
                Order::where('id',intval($val['id']))
                ->update([
                   'order_statuses_id' =>$val['status'],
                   'delivered_at' => date('Y-m-d H:i:s')  
                ]);
            }elseif($val['status'] == 4){
                Order::where('id',intval($val['id']))
                ->update([
                   'order_statuses_id' =>$val['status'],
                //    'driver_id_deliver' =>$val['driver'],
                   'pickup_at' => date('Y-m-d H:i:s')  
                ]);
                //Barang diambil ongkir di tanggung pengirim
            }
            //Barang diantar dengan tagihan dan ongkir
            elseif($val['status'] == 5 && $val['method'] == 2 ){
                $getAmount = DB::table('order_details')->where('orders_id',$val['id'])->select('price','delivery_fee')->get();
                $order = DB::table('orders')->join('delivery_addresses','delivery_addresses.id','orders.delivery_address_id')->where('orders.id',$val['id'])->select('driver_id_deliver','no_order','delivery_addresses.district')->get();
                $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) = CURRENT_DATE');
                $credit = DB::table('wallet_transaction')
                            ->insert([
                                'wallet_id' => $wallet_id[0]->id,
                                'type' => 'credit',
                                'description' => 'Pembayaran Barang (#'.$order[0]->no_order.')',
                                'amount' => $getAmount[0]->price 
                                ]);
                $driver = DB::table('drivers')->where('user_id',$order[0]->driver_id_deliver)->update(['district_placement' => $order[0]->district]);
                $credit2 = DB::table('wallet_transaction')
                ->insert([
                    'wallet_id' => $wallet_id[0]->id,
                    'type' => 'credit',
                    'description' => 'Ongkir (#'.$order[0]->no_order.')',
                    'amount' => $getAmount[0]->delivery_fee
                    ]);
            Order::where('id',intval($val['id']))
            ->update([
               'order_statuses_id' =>$val['status'],
               'delivered_at' => date('Y-m-d H:i:s')  
            ]);
            //Barang diantar tanpa tagihan dan ongkir
            }elseif($val['status'] == 5 && $val['method'] == 3 ){
            $order = DB::table('orders')->join('delivery_addresses','delivery_addresses.id','orders.delivery_address_id')->where('orders.id',$val['id'])->select('driver_id_deliver','no_order','delivery_addresses.district')->get();   
            $driver = DB::table('drivers')->where('user_id',$order[0]->driver_id_deliver)->update(['district_placement' => $order[0]->district]);
            Order::where('id',intval($val['id']))
            ->update([
            'order_statuses_id' =>$val['status'],
            'delivered_at' => date('Y-m-d H:i:s')  
            ]);
            //Barang diantar dengan ongkir tanpa tagihan
            }elseif($val['status'] == 5 && $val['method'] == 4 ){
            $getAmount = DB::table('order_details')->where('orders_id',$val['id'])->select('price','delivery_fee')->get();
            $order = DB::table('orders')->join('delivery_addresses','delivery_addresses.id','orders.delivery_address_id')->where('orders.id',$val['id'])->select('driver_id_deliver','no_order','delivery_addresses.district')->get();
            $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) = CURRENT_DATE');
            $credit2 = DB::table('wallet_transaction')
                ->insert([
                    'wallet_id' => $wallet_id[0]->id,
                    'type' => 'credit',
                    'description' => 'Ongkir (#'.$order[0]->no_order.')',
                    'amount' => $getAmount[0]->delivery_fee
                    ]);
            $driver = DB::table('drivers')->where('user_id',$order[0]->driver_id_deliver)->update(['district_placement' => $order[0]->district]);
            Order::where('id',intval($val['id']))
            ->update([
            'order_statuses_id' =>$val['status'],
            'delivered_at' => date('Y-m-d H:i:s')  
            ]);
            //Cancel Order
            }elseif($val['status'] == 6){
                Order::where('id',intval($val['id']))
                ->update([
                   'order_statuses_id' =>$val['status'],  
                ]);
               }
            //Retur 
            elseif($val['status'] == 7){
                DB::table('return')->insert(['id_orders' => intval($val['id'])]);
                Order::where('id',intval($val['id']))
                ->update([
                   'order_statuses_id' =>$val['status'],  
                ]);
               }
            //Pengiriman Ulang
            elseif($val['status'] == 8){
            Order::where('id',intval($val['id']))
            ->update([
                'order_statuses_id' =>$val['status'],  
            ]);
            }
        }
        return redirect('admin/order/pickup');
    }
 
    public function specialRegionList()
    {
        $get = DB::table('special_region_fee')->get();
        $data = array();
        if(!empty($get)){
            foreach($get as $val){
                $arr = array(
                    'id' => intval($val->id),
                     'city' => $val->city_name,
                    'district' => $val->district_name,
                    'village' => $val->village_name,
                    'city_id' => $val->city_id,
                    'district_id' => $val->district_id,
                    'village_id' => $val->village_id,
                    'delivery_fee' => intval($val->delivery_fee),
                    'pickup_fee' => intval($val->pickup_fee)
                );

                array_push($data,$arr);
            }
            return response()->json(['data' => $data],200);
        }
        return response()->json('Data not found',201);
    }

    public function addSpecialRegion(Request $request)
    {
        $city_id = $request->input('city_id');
        $district_id = $request->input('district_id');
        $village_id = $request->input('village_id');
        $delivery_fee = $request->input('delivery_fee');
        $pickup_fee = $request->input('pickup_fee');

        $this->validate($request, [
            'city_id'  => 'required',
            'district_id' => 'required',
            'village_id' =>'required | unique:special_region',
            'delivery_fee' => 'required',
            'pickup_fee' => 'required'
            // 'photo' => 'required|image',
        ]);

        $add = DB::table('special_region')->insert([
            'city_id' => $city_id,
            'district_id' => $district_id,
            'village_id' => $village_id,
            'delivery_fee' => $delivery_fee,
            'pickup_fee' => $pickup_fee

            ]);

        if($add > 0 ){
            return response()->json("Data created successfully");
        }

        return response()->json("Data create failed");
    } 

    public function updateSpecialRegion(Request $request)
    {
        $city_id = $request->input('city_id');
        $district_id = $request->input('district_id');
        $village_id = $request->input('village_id');
        $delivery_fee = $request->input('delivery_fee');
        $pickup_fee = $request->input('pickup_fee');
        $id = $request->input('id');    

        $edit = DB::table('special_region')->where('id',$id)->update([
            'city_id' => $city_id,
            'district_id' => $district_id,
            'village_id' => $village_id,
            'delivery_fee' => $delivery_fee,
            'pickup_fee' => $pickup_fee
            ]);

        if($edit > 0 ){
            return response()->json("Data created successfully");
        }

        return response()->json("Data create failed");
    }

    public function deleteSpecialRegion($id)
    {
        $del = DB::table('special_region')->where('id', $id)->delete();

        if($del > 0 ){
            return response()->json("Data deleted successfully");
        }
        return response()->json("Data delete failed");

    }

    public function region()
    {
        $special_city = DB::table('city')->join('service_area','city.id','service_area.city_id')->orderBy('nama')->get();
        $city = DB::table('city')->orderBy('nama')->get();
        $district = DB::table('district')->orderBy('nama')->get();
        $village = DB::table('village')->orderBy('nama')->get();

        return response()->json(['city' => $city, 'district' => $district, 'village' => $village, 'special_city' => $special_city]);

    }

    public function deleteCity($id)
    {
        $del = DB::table('service_area')->where('id', $id)->delete();

        if($del > 0 ){
            return response()->json("Data deleted successfully");
        }
        return response()->json("Data delete failed");

    }

    public function addCity(Request $request)
    {
        $city_id = $request->input('city_id');

        $this->validate($request, [
            'city_id'  => 'required',
        ]);

        $add = DB::table('service_area')->insert([
            'city_id' => $city_id,
            ]);

        if($add > 0 ){
            return response()->json("Data created successfully");
        }

        return response()->json("Data create failed");
    } 

    public function specialDeliveryFee($id)
    {
        $get = DB::table('special_region')->where('village_id',$id)->first()->delivery_fee;
        $d = DB::table('delivery_fee_list')->first()->price;
        $add =intval($get) - intval($d);
        return response()->json(intval($add));
    }

    public function specialPickupFee($id)
    {
        $g = DB::table('user_profiles')->leftJoin('special_region','special_region.village_id','user_profiles.village_id')->where('user_id',$id)->first()->pickup_fee;
        // $get = DB::table('special_region')->where('village_id',$g)->first()->pickup_fee;
        if($g !== null){
            $d = DB::table('delivery_fee_list')->first()->price;
        $add =intval($g) - intval($d);
        return response()->json(intval($add));
        }
        return response()->json(0);
    }

    public function deliverCancelRefund(Request $request)
    {   
        $req = $request->all();
        foreach ($req as $key => $val) {
        if($val['status'] == 6 && $val['method'] == 1 ){
        $getAmount = DB::table('order_details')->where('orders_id',$val['id'])->select('price','delivery_fee')->get();
        $order = DB::table('orders')->join('delivery_addresses','delivery_addresses.id','orders.delivery_address_id')->where('orders.id',$val['id'])->select('driver_id_deliver','no_order','delivery_addresses.district')->get();
        $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) = CURRENT_DATE AND status = 0 OR user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
        $debit = DB::table('wallet_transaction')
            ->insert([
                'wallet_id' => $wallet_id[0]->id,
                'type' => 'credit',
                'description' => 'Pengembalian pembayaran barang (#'.$order[0]->no_order.')',
                'amount' => -$getAmount[0]->price
                ]);
        Order::where('id',intval($val['id']))
        ->update([
        'order_statuses_id' =>$val['status'],
        'delivered_at' => date('Y-m-d H:i:s')  
        ]);
        }elseif($val['status'] == 6 && $val['method'] == 2 ){
            $getAmount = DB::table('order_details')->where('orders_id',$val['id'])->select('price','delivery_fee')->get();
            $order = DB::table('orders')->join('delivery_addresses','delivery_addresses.id','orders.delivery_address_id')->where('orders.id',$val['id'])->select('driver_id_deliver','no_order','delivery_addresses.district')->get();
            $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) = CURRENT_DATE AND status = 0 OR user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
            $debit = DB::table('wallet_transaction')
                ->insert([
                    'wallet_id' => $wallet_id[0]->id,
                    'type' => 'debit',
                    'description' => 'Pengembalian pembayaran barang (#'.$order[0]->no_order.')',
                    'amount' => -$getAmount[0]->price
                    ]);
            $debit2 = DB::table('wallet_transaction')
            ->insert([
            'wallet_id' => $wallet_id[0]->id,
            'type' => 'debit',
            'description' => 'Pengembalian ongkir(#'.$order[0]->no_order.')',
            'amount' => -$getAmount[0]->delivery_fee
            ]);
            Order::where('id',intval($val['id']))
            ->update([
            'order_statuses_id' =>$val['status'],
            'delivered_at' => date('Y-m-d H:i:s')  
            ]);
            }elseif($val['status'] == 6 && $val['method'] == 3 ){
                $getAmount = DB::table('order_details')->where('orders_id',$val['id'])->select('price','delivery_fee')->get();
                $order = DB::table('orders')->join('delivery_addresses','delivery_addresses.id','orders.delivery_address_id')->where('orders.id',$val['id'])->select('driver_id_deliver','no_order','delivery_addresses.district')->get();
                $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) = CURRENT_DATE AND status = 0 OR user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
                Order::where('id',intval($val['id']))
                ->update([
                'order_statuses_id' =>$val['status'],
                'delivered_at' => date('Y-m-d H:i:s')  
                ]);
                }
            elseif($val['status'] == 6 && $val['method'] == 4 ){
                $getAmount = DB::table('order_details')->where('orders_id',$val['id'])->select('price','delivery_fee')->get();
                $order = DB::table('orders')->join('delivery_addresses','delivery_addresses.id','orders.delivery_address_id')->where('orders.id',$val['id'])->select('driver_id_deliver','no_order','delivery_addresses.district')->get();
                $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) = CURRENT_DATE AND status = 0 OR user_id ='.intval($order[0]->driver_id_deliver).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
                $debit = DB::table('wallet_transaction')
                ->insert([
                    'wallet_id' => $wallet_id[0]->id,
                    'type' => 'debit',
                    'description' => 'Pengembalian ongkir(#'.$order[0]->no_order.')',
                    'amount' => -$getAmount[0]->delivery_fee
                    ]);
                Order::where('id',intval($val['id']))
                ->update([
                'order_statuses_id' =>$val['status'],
                'delivered_at' => date('Y-m-d H:i:s')  
                ]);
                }
        }
    }

    public function pickupCancelRefund(Request $request)
    {   
        $req = $request->all();
        foreach ($req as $key => $val) {
        if($val['status'] == 6 && $val['method'] == 1 ){
        $getAmount = DB::table('order_details')->where('orders_id',$val['id'])->select('price','delivery_fee')->get();
        $order = DB::table('orders')->join('delivery_addresses','delivery_addresses.id','orders.delivery_address_id')->where('orders.id',$val['id'])->select('driver_id_pickup','no_order','delivery_addresses.district')->get();
        $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_pickup).' AND CAST(created_at as date) = CURRENT_DATE AND status = 0 OR user_id ='.intval($order[0]->driver_id_pickup).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
        $credit = DB::table('wallet_transaction')
            ->insert([
                'wallet_id' => $wallet_id[0]->id,
                'type' => 'credit',
                'description' => 'Pengembalian pembayaran barang (#'.$order[0]->no_order.')',
                'amount' => $getAmount[0]->price
                ]);
        $debit = DB::table('wallet_transaction')
        ->insert([
            'wallet_id' => $wallet_id[0]->id,
            'type' => 'debit',
            'description' => 'Pengembalian ongkir(#'.$order[0]->no_order.')',
            'amount' => -$getAmount[0]->delivery_fee
            ]);
        Order::where('id',intval($val['id']))
        ->update([
        'order_statuses_id' =>$val['status'],
        'pickup_at' => date('Y-m-d H:i:s')  
        ]);
        }elseif($val['status'] == 6 && $val['method'] == 2 ){
            $getAmount = DB::table('order_details')->where('orders_id',$val['id'])->select('price','delivery_fee')->get();
            $order = DB::table('orders')->join('delivery_addresses','delivery_addresses.id','orders.delivery_address_id')->where('orders.id',$val['id'])->select('driver_id_pickup','no_order','delivery_addresses.district')->get();
            $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_pickup).' AND CAST(created_at as date) = CURRENT_DATE AND status = 0 OR user_id ='.intval($order[0]->driver_id_pickup).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
            $credit = DB::table('wallet_transaction')
                ->insert([
                    'wallet_id' => $wallet_id[0]->id,
                    'type' => 'credit',
                    'description' => 'Pengembalian pembayaran barang (#'.$order[0]->no_order.')',
                    'amount' => $getAmount[0]->price
                    ]);
            Order::where('id',intval($val['id']))
            ->update([
            'order_statuses_id' =>$val['status'],
            'pickup_at' => date('Y-m-d H:i:s')  
            ]);
            }elseif($val['status'] == 6 && $val['method'] == 3 ){
                $getAmount = DB::table('order_details')->where('orders_id',$val['id'])->select('price','delivery_fee')->get();
                $order = DB::table('orders')->join('delivery_addresses','delivery_addresses.id','orders.delivery_address_id')->where('orders.id',$val['id'])->select('driver_id_pickup','no_order','delivery_addresses.district')->get();
                $wallet_id = DB::select('SELECT id from wallet where user_id ='.intval($order[0]->driver_id_pickup).' AND CAST(created_at as date) = CURRENT_DATE AND status = 0 OR user_id ='.intval($order[0]->driver_id_pickup).' AND CAST(created_at as date) < CURRENT_DATE AND status = 0');
                $debit = DB::table('wallet_transaction')
                ->insert([
                    'wallet_id' => $wallet_id[0]->id,
                    'type' => 'debit',
                    'description' => 'Pengembalian ongkir(#'.$order[0]->no_order.')',
                    'amount' => -$getAmount[0]->delivery_fee
                    ]);
                Order::where('id',intval($val['id']))
                ->update([
                'order_statuses_id' =>$val['status'],
                'pickup_at' => date('Y-m-d H:i:s')  
                ]);
                }
            elseif($val['status'] == 6 && $val['method'] == 4 ){
                Order::where('id',intval($val['id']))
                ->update([
                'order_statuses_id' =>$val['status'],
                'pickup_at' => date('Y-m-d H:i:s')  
                ]);
                }
        }

        return response()->json('data updated successfully');
    }

    public function sendNotif(Request $request)
    {   
        $user = DB::table('users')->where('device_id','!=',null)->get();
        $dev_id = array();
        foreach($user as $val){
            array_push($dev_id,$val->device_id);
        }
        // return $dev_id;
            $url = 'https://fcm.googleapis.com/fcm/send';
            $dataArr = array('click_action' => 'FLUTTER_NOTIFICATION_CLICK','status'=>"done");
            $notification = array('title' =>$request->input('body'), 'text' => $request->input('title'), 'sound' => 'default', 'badge' => '1',);
            
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
            $notification = array('title' =>'Ada orderan baru nich, yuk cek list orderan', 'text' => 'halo', 'sound' => 'default', 'badge' => '1',);
            
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

    public function appVersion()
    {
        $data = DB::select('select * from app_version order by id DESC');

        if($data){
            return response()->json(['data' => $data]);
        }

        return response()->json('Data Tidak Ditemukan');
    }

}
