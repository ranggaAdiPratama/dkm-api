<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\Handler;
use App\Models\Order\Order;
use DB;

class AdminOrderController extends Controller
{
   //Order
    public function index()
    {
      $getData = DB::table('list_orders_before_pickup')->get();
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

    public function finishPickupList()
    {
        $getData = DB::table('list_orders_finish_pickup')->get();
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

    public function readyToDeliveryList()
    {
        $getData = DB::table('list_orders_ready_to_deliver')->get();
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

    public function deliveredList()
    {
        $getData = DB::table('list_orders_delivered')->get();
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

    public function canceledList()
    {
        $getData = DB::table('list_orders_cancel')->get();
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

    public function returnList()
    {
        $getData = DB::table('list_orders_return')->get();
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

    public function create(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required',
            'weight' => 'required',
            'volume' =>'required',
            'price' => 'required'
            // 'photo' => 'required|image',
        ]);
        $id = auth()->user()->id;
        $numb = rand(0,999999);
        $date = str_shuffle(date('dY'));
        $code = substr($numb + $date, 0, 6);
        $district = $request->input('district');
        $price = $request->input('price');

        //Create Payment
        $payment_id = DB::table('payments')->insertGetId([
            'user_id' => $id,
            'status' => 1,
            'price' => $price,
            'payment_method_id' => $request->input('payment_method')
        ]);

        //Create Delivery Address
        $deliv_address = DB::table('delivery_addresses')->insertGetId([
            'address' =>   $request->input('receiver_addres'),
            'description' =>  $request->input('description_address'),
            'district' =>  $request->input('district'),
            'latitude' => $request->input('latitude'),
            'longitude' =>  $request->input('longitude')
        ]);

        //Get Delivery Fee List
        $del_fee_list = DB::table('delivery_fee_list')->get();

        //Check Delivery List
        $w = $request->input('weight');
        if($del_fee_list !== null){
            foreach($del_fee_list as $val){
                if($w > $val->from_weight AND $w < $val->to_weight){
                    $delivery_fee = $val->price;
                }
            }
        } 


        //Check Customer
        $checkCust = DB::table('pre-pickup-assigned-check')->where('user_id',$id)->get();

        //Assign Pickup Driver
        if(count($checkCust) == 0 ){
            $getDriver = DB::select('SELECT
                                user_id
                                FROM
                                drivers
                                WHERE
                                drivers.placement_district = '.$district.'
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
            'description' => $request->input('description_address'),
            'delivery_fee' => $delivery_fee
        ]);

        if(!empty($detail)){
            return response()->json("Order berhasil di buat", 200);
        }

        return response()->json("Order gagal di buat");

 
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
            }elseif($val['status'] == 3){
             Order::where('id',intval($val['id']))
             ->update([
                'pickup_status' => 1 ,
                'order_statuses_id' =>$val['status'],
                'pickup_at' => date('Y-m-d H:i:s')  
             ]);
            }elseif($val['status'] == 4){
                Order::where('id',intval($val['id']))
                ->update([
                   'order_statuses_id' =>$val['status'],
                   'pickup_at' => date('Y-m-d H:i:s')  
                ]);
            }elseif($val['status'] == 5){
                Order::where('id',intval($val['id']))
                ->update([
                   'order_statuses_id' =>$val['status'],
                   'delivered_at' => date('Y-m-d H:i:s')  
                ]);
               }
        }
        return redirect('admin/order/pickup');
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


    //Driver

    public function driverList()
    {
        $getDriver = DB::select(
            'SELECT
            users.id, 
            users.`name`, 
            users.email, 
            driver_category.category, 
            drivers.total_orders, 
            wallet.begin_balance, 
            sum(wallet_transaction.debit) as debit, 
            sum(wallet_transaction.credit) as credit
            FROM
            users
            INNER JOIN
            drivers
            ON 
                users.id = drivers.user_id
            INNER JOIN
            driver_category
            ON 
                drivers.driver_category_id = driver_category.id
            LEFT JOIN
            wallet
            ON 
                users.id = wallet.user_id
            INNER JOIN
            db_dkm.wallet_transaction
            ON 
                wallet.id = wallet_transaction.wallet_id
            where users.role_id = 3
            
            GROUP BY 
            users.id,
            users.`name`, 
            users.email, 
            drivers.total_orders,
            driver_category.category, 
            wallet.begin_balance 
            ');
$driver =[];
if(!empty($getDriver)){
   foreach ($getDriver as $val) {
       $arr = array(
           'id' => $val->id,
           'name' => $val->name,
           'email' => $val->email,
           'total_order' => $val->total_orders,
           'category' => $val->category,
           'saldo' => $val->begin_balance + $val->debit + $val->credit
       );
       array_push($driver,$arr);
   }
}

return response()->json([
    
    'driver' => $driver ,

    ], 200);
    }

    
}
