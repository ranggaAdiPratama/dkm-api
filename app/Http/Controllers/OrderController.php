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
      $getData = DB::table('list_orders')->get();
        $data = [];
        if(!empty($getData)){
            foreach($getData as $val){
                $arr = array(
                    'id' => $val->id,
                    'no_order' => 'ID#000'.$val->no_order,
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'name'  => '',
            'weight' => 'required',
            'volume' =>'required',
            'price' => 'required',
            'photo' => 'required|image',
        ]);

        $photo = Str::random(34);
        $request->file('photo')->move(storage_path('photo'), $avatar);
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
                                    orders.delivery_fee
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
        $id  = auth()->user()->id;

        $getOrder = DB::table('pickup_list')
                            ->where('driver_id_pickup',$id)
                            ->get();
       
        $getData = DB::table('pickup_detail_list')
                        ->where('driver_id_pickup', $id)
                        ->get();
        $data = array();
        
        if (!empty($getOrder)){
            foreach ($getOrder as $val) {
                $date = date_create($val->created_at);
                $detail_order =  $getData = DB::table('pickup_detail_list')
                                ->where('driver_id_pickup', $id)
                                ->where('no_order',$val->no_order)
                                ->get();
                $detailArr = array() ;
                $total = array();
            if(!empty($detail_order)){
                foreach ($detail_order as $val) {
                    $array = array(
                    'delivery_fee' => intval($val->delivery_fee) ,
                    'name' => $val->name,
                    'price' => intval($val->price),
                    'description' => $val->description,
                    'weight' => intval($val->weight),
                    'volume' => intval($val->volume),
                    'photo' => $val->photo,
                    'receiver' => $val->receiver,
                    // 'phone' => $val->phone,
                    // 'method' => $val->method,
                    // 'status' => $val->status,
                    'address' => $val->address,
                    'desc_add' => $val->desc_add,
                    'latitude' => $val->latitude,
                    'longitude' => $val->longitude,
                    // 'sender_name' => $val->sender_name,
                    // 'sender_phone' => $val->sender_phone,
                    'subtotal' => $val->delivery_fee + $val->price
                    );
                    
                    array_push($total,$array['subtotal']);
                    array_push($detailArr,$array);
                }
            }
                $arr = array(
                    'id' => $val->id,
                    'no_order' => 'ID#'.'000'.$val->no_order,
                    'tanggal_order' => date_format($date,"d-M-Y"),
                    'detail_order' => $detailArr,
                     'phone' => $val->phone,
                    'method' => $val->method,
                    'status' => $val->status,
                     'sender_name' => $val->sender_name,
                    'sender_phone' => $val->sender_phone,
                    'address' => $val->address,
                    'desc_add' => $val->desc_add,
                    'latitude' => $val->latitude,
                    'longitude' => $val->longitude,
                    'total' => array_sum($total)
                      
                    
                );
                array_push($data,$arr);
            }
        }

        return response()->json(['data' => $data], 200);
    }

    public function pickupShow($id)
    {
        $getDetail = Order::join('order_details as od','orders.id','od.id')
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
        
        $detail_penerima = Order::join('order_details as od','orders.id','od.id')
                                ->join('delivery_addresses as da', 'orders.delivery_address_id','da.id')
                                ->select(
                                    'od.receiver',
                                    'od.phone',
                                    'da.address'
                                )
                                ->where('orders.id',$id)
                                ->get();
        
        $getData = Order:: join('payments as p','orders.payment_id','p.id')
                                    ->join('order_details as od','orders.id','od.id')
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
      $method = $request->input('payment_method');
      date_default_timezone_set('Asia/Bangkok');
       if($status == 3){
           Order::join('payments','payments.id','orders.payment_id')
                ->where('orders.id',$id)
                ->update([
                'pickup_status' => 1 ,
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
        $getData = DB::table('pickup_list_history')
                        ->where('driver_id_pickup', $id)
                        ->get();     
        $data = array();
        
        if (!empty($getData)){
            foreach ($getData as $val) {
                $date = date_create($val->created_at);
                $arr = array(
                    'id' => $val->id,
                    'no_order' => 'ID#'.'000'.$val->no_order,
                    'tanggal_order' => date_format($date,"d-M-Y"),
                    'delivery_fee' => $val->delivery_fee,
                    'name' => $val->name,
                    'price' => $val->price,
                    'description' => $val->description,
                    'weight' => $val->weight,
                    'volume' => $val->volume,
                    'photo' => $val->photo,
                    'receiver' => $val->receiver,
                    'phone' => $val->phone,
                    'method' => $val->method,
                    'status' => $val->status,
                    'address' => $val->address,
                    'desc_add' => $val->desc_add,
                    'latitude' => $val->latitude,
                    'longitude' => $val->longitude,
                    'sender_name' => $val->sender_name,
                    'sender_phone' => $val->sender_phone,
                    'subtotal' => $val->delivery_fee + $val->price
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
        $getData = DB::table('delivery_list')
                        ->where('driver_id_deliver',$id)
                        ->get();
        $data = array();
        if (!empty($getData)){
            foreach ($getData as $key=>$val) {
                $date = date_create($val->pickup_at);
                $arr = array(
                    'id' => $val->id,
                    'no_order' => 'ID#'.'000'.$val->no_order,
                    'tanggal_pickup' => date_format($date, 'd-M-Y'),
                    'delivery_fee' => $val->delivery_fee,
                    'name' => $val->name,
                    'price' => $val->price,
                    'description' => $val->description,
                    'weight' => $val->weight,
                    'volume' => $val->volume,
                    'photo' => $val->photo,
                    'receiver' => $val->receiver,
                    'phone' => $val->phone,
                    'method' => $val->method,
                    'status' => $val->status,
                    'address' => $val->address,
                    'desc_add' => $val->desc_add,
                    'latitude' => $val->latitude,
                    'longitude' => $val->longitude,
                    'sender_name' => $val->sender_name,
                    'sender_phone' => $val->sender_phone,
                    'subtotal' => $val->delivery_fee + $val->price
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
        $getData= Order::join('order_details','orders.id','order_details.orders_id')
                        ->select(
                            'orders.id',
                            'orders.no_order',
                            'orders.delivered_at'
                            )
                        ->where('orders.pickup_status',1)
                        ->where('orders.order_statuses_id','=',3)
                        
                        ->where('orders.driver_id_deliver',$id)
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
        $detail_barang = Order::join('order_details as od','orders.id','od.id')
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
        
        $detail_penerima = Order::join('order_details as od','orders.id','od.id')
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
                                    ->join('order_details as od','orders.id','od.id')
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

    public function orderHistory()
    {
        try {
            $data = DB::table('history_orders')->get();

            return response()->json(['data' =>$data], 200);
            
        } catch (\Exception $e) {
            return response()->json('Belum Ada History', 200);
        }
    }

}
