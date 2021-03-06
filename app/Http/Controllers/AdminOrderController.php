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
        $this->middleware('role');
    }

   //Order
    public function index()
    {
      $getData = DB::table('list_orders_pickingup')->get();
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
                    'driver_name' => $val->driver_name,
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
        $getData = DB::table('list_orders_pickedup')->get();
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $date = date_create($val->order_date); 
                $arr = array(
                    'id' => $val->id,
                    'no_order' => '#'.intval($val->no_order),
                    'client' => $val->name,
                    'date' => date_format($date,'d-M-y') ,
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
                    'payment_method' => $val->method,
                    'driver_name' => $val->driver_name,
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

    public function reDeliveryList()
    {
        $getData = DB::table('list_orders_redelivery')->get();
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
            $img = 'https://cdn.medcom.id/dynamic/content/2018/12/31/971006/BNMY2dwu0a.jpg?w=700';
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

    public function userListCustomer()
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
            }elseif($val['status'] == 6){
                Order::where('id',intval($val['id']))
                ->update([
                   'order_statuses_id' =>$val['status'],  
                ]);
               }
            elseif($val['status'] == 7){
                Order::where('id',intval($val['id']))
                ->update([
                   'order_statuses_id' =>$val['status'],  
                ]);
               }
            elseif($val['status'] == 8){
            Order::where('id',intval($val['id']))
            ->update([
                'order_statuses_id' =>$val['status'],  
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

    public function detailOrder($no_order)
 {
     $data = DB::table('detail_orders')
                ->where('no_order',$no_order)    
                ->get();

     if($data){
         return response()->json([
             'data' => $data
         ]);
     }
     return response()->json('Data Not Found',401);
 }

 public function area()
 {
    $district = DB::table('districts')->get();
    $village = DB::table('villages')->get();

    return response()->json([
        'district' => $district,
        'village' => $village
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
         'user_id' =>'required',
         'order_id' =>'required',
         'district' =>'required',
         'payment_method' =>'required',
         'payment_method' =>'required',
         // 'photo' => 'required|image',
     ]);
  
    $user_id = $request->input('user_id');
    $id = $request->input('order_id');

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
         $img = $request->input('photo');
     }

     //Create Delivery Address
     $deliv_address = DB::table('delivery_addresses')->where('id',$request->input('delivery_address_id'))
        ->update([
         'address' =>   $request->input('receiver_address'),
         'description' =>  $request->input('description_address'),
         'district' =>  $request->input('district')
     ]);
      

     $detail = DB::table('order_details')->where('orders_id', $id)->Update([
         'name' => $request->input('name'),
         'price' => $request->input('price'),
         'description' => $request->input('description_order'),
         'weight' => $request->input('weight'),
         'volume' => $request->input('volume'),
         'receiver' => $request->input('receiver_name'),
         'phone' => $request->input('receiver_phone'),
         'description' => $request->input('description_address'),
         'delivery_fee' => $request->input('delivery_fee'),
         'photo' => $img

     ]);

     if($detail){
         return response()->json("Data Updated Successfully", 200);
     }

     return response()->json("Data Failed to Update");

 }

    //Driver

    public function driverList()
    {
        $getDriver = DB::table('driver_list')->get();
        $district = DB::table('districts')->get();
        $village = DB::table('villages')->get();
        $driver =[];
        if(!empty($getDriver)){
        foreach ($getDriver as $val) {
            $d = str_replace(str_split('\\[]"'), '', $val->village_placement_id);
            $e =explode(',',$d);
            $village_placement = array();
            foreach ($e as $v){
                $get = DB::table('village')->where('id',$v)->get();              
                array_push($village_placement,$get[0]->nama);
            }
          
            $arr = array(
           'id' => $val->id,
           'name' => $val->name,
           'email' => $val->email,
           'total_order' => $val->total_orders,
           'category' => $val->category,
           'district_placement' => $val->district_placement,
           'village_placement' => $village_placement,
           'driver_district' => $val->driver_district,
           'driver_village' => $val->driver_village,
           'phone' => $val->phone,
           'photo' => $val->photo,
           'saldo' => $val->begin_balance + $val->amount
       );
       array_push($driver,$arr);
    }
    }

    return response()->json([
        
        'driver' => $driver ,
        'district' =>$district,
        'village' =>$village,

        ], 200);
        }
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

    public function show($no_order)
    {
        $order = DB::table('list_orders_pickedup')->where('no_order',$no_order)->first();
        if(!empty($order)){
        $date = date_create($order->order_date);
        $data = array(
                    'id' => intval($order->id),
                    'no_order' => $order->no_order,
                    'client' => $order->name,
                    'date' => date_format($date,'d-M-y') ,
                    'delivery_fee' => intval($order->delivery_fee),
                    'order_status' => $order->order_status,
                    'payment_status' => $order->payment_status,
                    'payment_method' => $order->method,
                    'sender_address' => $order->sender_address,
                    'sender_phone' => $order->sender_phone,
                    'receiver_name' => $order->receiver_name,
                    'receiver_phone' => $order->receiver_phone,
                    'receiver_address' => $order->receiver_address,
                    'price' => intval($order->price),
                    'total' => $order->price + $order->delivery_fee,
                    'driver_name' => $order->driver_name
        );
        
        $d = DB::table('driver_list')->where('district_placement',$order->receiver_district)->get();
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
        $update = DB::table('orders')->where('id',$request->input('id'))
                    ->update([
                        'order_statuses_id' => 4,
                        'driver_id_deliver' => $request->input('id_driver'),
                        ]);
        return response()->json('Data Updated Successfully', 200);
        
    }
 

 
}
