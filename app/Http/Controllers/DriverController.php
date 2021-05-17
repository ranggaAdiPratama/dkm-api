<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Wallet;
use DB;

class DriverController extends Controller
{
 
    public function index()
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

    public function driverList()
    {
        $getDriver = DB::table('driver_list')->get();
        $special_city = DB::table('city')->join('service_area','city.id','service_area.city_id')->orderBy('nama')->get();
        $district = DB::table('district')->get();
        $village = DB::table('village')->get();
        $driver =[];
        if(!empty($getDriver)){
        foreach ($getDriver as $val) {
            // village
            $d = str_replace(str_split('\\[]"'), '', $val->village_placement_id);
            $e = explode(',',$d);
            $village_placement = array();
            if($val->village_placement_id !== '[]' && is_array($e) || $val->village_placement_id !== null && is_array($e)){
            foreach ($e as $v){
                $get = DB::table('village')->where('id',$v)->first('nama');
                if(!empty($get)){
                    array_push($village_placement,$get->nama);
                }     
                    
            }
            }
            $f = str_replace(str_split('\\[]"'), '', $val->district_placement_id);
            $g = explode(',',$f);
            $district_placement = array();
            if($val->district_placement_id !== '[]' && is_array($g) || $val->district_placement_id !== null && is_array($g)){
            foreach ($g as $i){
                $name = DB::table('district')->where('id',$i)->first('nama');
                if(!empty($name)){
                array_push($district_placement,$name->nama);
                }
            }
            }
            $arr = array(
           'id' => $val->id,
           'name' => $val->name,
           'email' => $val->email,
           'total_order' => $val->count,
           'category' => $val->category,
           'district_placement' => $district_placement,
           'village_placement' => $village_placement,
           'driver_district' => $val->driver_district,
           'driver_village' => $val->driver_village,
           'phone' => $val->phone,
           'photo' => $val->photo,
           'online' => $val->online,
       );
       array_push($driver,$arr);
    }
    }
    return response()->json([
        
        'driver' => $driver ,
        'district' =>$district,
        'village' =>$village,
        'city' => $special_city

        ], 200);
    }
    public function driverListExp()
    {
        $getDriver = DB::table('driver_list_exp')->get();
        $district = DB::table('districts')->get();
        $village = DB::table('villages')->get();
        $driver =[];
        if(!empty($getDriver)){
        foreach ($getDriver as $val) {
            $d = str_replace(str_split('\\[]"'), '', $val->village_placement_id);
            $e =explode(',',$d);
            $village_placement = array();
            if($val->village_placement_id !== null && $val->village_placement_id !== '[]'){
            foreach ($e as $v){
                $get = DB::table('village')->where('id',$v)->first('nama')->nama;     
                array_push($village_placement,$get);
            }
            }
          
            $arr = array(
           'id' => $val->id,
           'name' => $val->name,
           'email' => $val->email,
           'total_order' => $val->count,
           'category' => $val->category,
           'district_placement' => $val->district_placement,
           'village_placement' => $village_placement,
           'driver_district' => $val->driver_district,
           'driver_village' => $val->driver_village,
           'phone' => $val->phone,
           'photo' => $val->photo,
           'online' => $val->online,
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

    public function driverWallet()
    {
        $getData = DB::table('driver_wallet')->get();
        $data = array();
        if(!empty($getData)){
            foreach($getData as $val){
                $date = date_create($val->wallet_date);
                if(intval($val->amount) !== 0 ){
                    $amount = intval($val->amount);
                }else{
                    $amount = 0;
                }
                $arr = array(
                    'wallet_id' => intval($val->id_wallet),
                    'driver_name' => $val->name,
                    'begin_balance' =>intval($val->begin_balance),
                    'status' =>intval($val->status),
                    'wallet_date' =>date_format($date, 'Y-m-d'),
                    'amount' => $amount,
                    'ending_balance'=> $amount + $val->begin_balance
                );
                
                array_push($data,$arr);
               
            }
            return response()->json(['data' => $data]);
        }

        return response()->json('Data Not Found', 204);
    }

    public function driverWalletFilter(Request $request)
    {
        $from = date_format(date_create($request->input('from')),'Y-m-d' );
        $to = date_format(date_create($request->input('to')),'Y-m-d' );
        $role = $request->input('role');
        $getData = DB::select(
                        '
                        SELECT
                        wallet.begin_balance, 
                        sum(wallet_trans_view.amount) AS amount, 
                        users.`name`, 
                        wallet.user_id, 
                        wallet.id AS id_wallet, 
                        wallet.update_at AS wallet_date, 
                        wallet_trans_view.created_at AS wallet_trans_date, 
                        wallet.`status`
                        FROM
                        wallet
                        LEFT JOIN
                        wallet_trans_view
                        ON 
                            wallet.id = wallet_trans_view.wallet_id AND
                            CAST(wallet_trans_view.created_at AS DATE) = CURRENT_DATE
                        JOIN
                        users
                        ON 
                            wallet.user_id = users.id
                        WHERE
                        CAST(wallet.update_at AS DATE) >= "'.$from.'"
                        AND
                        CAST(wallet.update_at AS DATE) <= "'.$to.'"
                        AND
                        users.role_id = '.$role.'
                        GROUP BY
                        wallet.user_id, 
                        CAST(wallet_date AS DATE)
                        ORDER BY
                        users.`name` ASC 
                                '
                            );
        $data = array();
        if(!empty($getData)){
            foreach($getData as $val){
                $date = date_create($val->wallet_date);
                if(date_format(date_create($val->wallet_date),'d-M-Y' ) == date('d-M-Y') && date_format(date_create($val->wallet_trans_date),'d-M-Y' ) == date('d-M-Y')){
                    $amount = intval($val->amount);
                }else{
                    $amount = 0;
                }
                $arr = array(
                    'wallet_id' => intval($val->id_wallet),
                    'driver_name' => $val->name,
                    'begin_balance' =>intval($val->begin_balance),
                    'status' =>intval($val->status),
                    'wallet_date' =>date_format($date, 'Y-m-d'),
                    'amount' => $amount,
                    'ending_balance'=> $amount + $val->begin_balance
                );
                
                array_push($data,$arr);
               
            }
            return response()->json(['data' => $data]);
        }

        return response()->json('Data Not Found', 204);
    }

    public function driverWalletExp()
    {
        $getData = DB::table('driver_wallet_exp')->get();
        $data = array();
        if(!empty($getData)){
            foreach($getData as $val){
                if(date_format(date_create($val->wallet_date),'d-M-Y' ) == date('d-M-Y') && date_format(date_create($val->wallet_trans_date),'d-M-Y' ) == date('d-M-Y')){
                    $amount = intval($val->amount);
                }else{
                    $amount = 0;
                }
                $arr = array(
                    'wallet_id' => intval($val->id_wallet),
                    'driver_name' => $val->name,
                    'begin_balance' =>intval($val->begin_balance),
                    'amount' => $amount,
                    'ending_balance'=> $amount + $val->begin_balance
                );
                
                array_push($data,$arr);
               
            }
            return response()->json(['data' => $data]);
        }

        return response()->json('Data Not Found', 204);
    }

    //Admin Panel
    public function driverWalletDetail($id)
    {
        //Cek Saldo Awal
        $begin_balance_check = DB::select('SELECT begin_balance FROM wallet where CAST(update_at AS DATE) = CURRENT_DATE AND id='.$id.' OR CAST(update_at AS DATE) < CURRENT_DATE AND id='.$id);
        // dd($begin_balance_check);
        $begin_balance = $begin_balance_check;
        $getData = DB::table('driver_wallet_detail')
                    ->where('id',$id)
                    ->get();
        $data = array();
        $debit = [];
        $credit = [];
        $data_amount = array();
        if(!empty($getData)){
            $ending_balance = [];
            foreach($getData as $val){
                $date = date_create($val->created_at);
                if($val->type == 'debit'){
                    array_push($debit,$val->amount);
                }else{
                    array_push($credit,$val->amount);
                }
                $amount = intval($val->amount);
                array_push($data_amount,$amount);
                $arr = array(
                    // 'driver_id' => $val->id,
                    'date' =>date_format($date, 'd-M-Y'),
                    'description' => $val->description,
                    'type' => $val->type,
                    'amount' => intval($val->amount),
                    
                );
                $end = $begin_balance[0]->begin_balance + $val->amount;
                array_replace(array($ending_balance),array($end));
                array_push($data,$arr);
            }

            // return(array_sum($data_amount));
            return response()->json([
                'begin_balance' => intval($begin_balance[0]->begin_balance),
                'data' => $data,
                'debit' => array_sum($debit),
                'credit' => array_sum($credit),
                'ending_balance' => $begin_balance[0]->begin_balance + array_sum($data_amount)
                ]);
        }
        return response()->json('Data Not Found', 204);
    }

    //Driver Application

    public function driverTransaction()
    {
        //Cek Saldo Awal
        $id  = auth()->user()->id;
        $begin_balance_check = DB::select('SELECT begin_balance FROM wallet where CAST(update_at AS DATE) = CURRENT_DATE AND user_id='.$id.' AND wallet.status = 0 OR CAST(update_at AS DATE) < CURRENT_DATE AND user_id='.$id.' AND wallet.status = 0');
        // dd($begin_balance_check);
        $begin_balance = $begin_balance_check;
        $getData = DB::table('driver_wallet_detail')
                    ->where('user_id',$id)
                    ->get();
        if(count($getData) > 0 || count($begin_balance) > 0 ){
            $data = array();
            $data_amount = array();
            $ending_balance = [];
            foreach($getData as $val){
                $date = date_create($val->created_at);
                $amount = intval($val->amount);
                array_push($data_amount,$amount);
                $arr = array(
                    // 'driver_id' => $val->id,
                    'date' =>date_format($date, 'd-M-Y'),
                    'description' => $val->description,
                    'type' => $val->type,
                    'amount' => intval($val->amount),
                    
                );
                $end = $begin_balance[0]->begin_balance + $val->amount;
                array_replace(array($ending_balance),array($end));
                array_push($data,$arr);
            }
            // return(array_sum($data_amount));
            return response()->json([
                'begin_balance' => intval($begin_balance[0]->begin_balance),
                'data' => $data,
                'ending_balance' => $begin_balance[0]->begin_balance + array_sum($data_amount)
                ]);
        }else {
            return response()->json([
                'begin_balance' => 0,
                'data' => [],
                'ending_balance' => 0
                ]);
        } 
    }

        
        public function driverPlacement (Request $request)
        {
            $req = $request->all();
            foreach ($req as $val){
            if($val['village_placement'] !== '[]'  ){
                $save =   DB::table('drivers')->where('user_id',$val['id'])
                    ->update([
                    'district_placementt' => $val['district_placement'],
                    'village_placement' => $val['village_placement'],
                    ]);
            }else{
                    $save =   DB::table('drivers')->where('user_id',$val['id'])
                    ->update([
                    'district_placementt' => $val['district_placement'],
                    ]);
            }
        }
            
            if($save > 0){
                return response()->json('Data Updated Successfully', 200);
            }
            return response()->json('Data Updated Error', 409);
            }
        

        public function setSaldo(Request $request)
        {
            $req = $request->all();
            date_default_timezone_set('Asia/Bangkok');
            foreach ($req as $val){
                $check_saldo = DB::table('driver_wallet')->where('user_id',intval($val['id']))->get();
                if(count($check_saldo) > 0){
                $update = DB::update('update wallet set begin_balance = '.intval($val["begin_balance"]).',update_at = "'.date("Y-m-d H:i:s").'" WHERE user_id = '.intval($val['id']).' AND CAST(created_at as date)= CURRENT_DATE');
            
           }else{
                $create = DB::table('wallet')->insert(['begin_balance' => intval($val['begin_balance']),'update_at' => date('Y-m-d H:i:s'),'user_id' => intval($val['id'])]);
           }
            }
    
            return response()->json('Data Updated Successfully', 200); 
        }
    
        public function setSaldoExp(Request $request)
        {
            $req = $request->all();
            date_default_timezone_set('Asia/Bangkok');
            
            foreach ($req as $val){
                $check_saldo = DB::table('driver_wallet_exp')->where('user_id',intval($val['id']))->get();
                if(count($check_saldo) > 0){
                $update = DB::update('update wallet set begin_balance = '.intval($val["begin_balance"]).',update_at = "'.date("Y-m-d H:i:s").'" WHERE user_id = '.intval($val['id']).' AND CAST(created_at as date)= CURRENT_DATE');
           }else{   
                $create = DB::table('wallet')->insert(['begin_balance' => intval($val['begin_balance']),'update_at' => date('Y-m-d H:i:s'),'user_id' => intval($val['id'])]);
           }
            }
    
            return response()->json('Data Updated Successfully', 200); 
        }

        public function addSaldo(Request $request)
        {
            $id = $request->input('id');
            $amount = $request->input('amount');
            date_default_timezone_set('Asia/Bangkok');
                // $credit = DB::update('update wallet set begin_balance = '.intval($new_beg_bal).',update_at = "'.date("Y-m-d H:i:s").'" WHERE id = '.intval($id).' AND CAST(created_at as date)= CURRENT_DATE');
                $credit = DB::table('wallet_transaction')
                ->insert([
                    'wallet_id' => $id,
                    'type' => 'credit',
                    'description' => "Top Up",
                    'amount' => $amount
                    ]);
                if($credit == 1) {
                return response()->json('Data Updated Successfully', 200); 
             }
            
        }

        public function addSaldoExp(Request $request)
        {
            $id = $request->input('id');
            $amount = $request->input('amount');
            date_default_timezone_set('Asia/Bangkok');
                $credit = DB::table('wallet_transaction')
                ->insert([
                    'wallet_id' => $id,
                    'type' => 'credit',
                    'description' => "Top Up",
                    'amount' => $amount
                    ]);
                if($credit == 1) {
            return response()->json('Data Updated Successfully', 200); 
        }
    }

    public function pullBalance($id)
    {
        $update = DB::table('wallet')->where('id',$id)->update(['status'=> 1]);

        if($update > 0 ){
            return response()->json('Data Updated Successfully');
        }
        return response()->json('Data update failed'); 
    }

}
