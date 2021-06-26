<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Dashboard;
use App\Models\User;
use DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('CorsMiddleware');
    }
    
    public function getMenus()
    {
     $menus = Dashboard::all();
     return $menus;  
    }

    public function addMenus(Request $request)
    {
      $save =  DB::table('menus')->insert($request->all());
      if($save){
          return 'data berhasil di simpan';
      }
     return ;  
    }

    public function deleteMenus(Request $request)
    {
      $save =  DB::table('menus')->where('id', '=', $request)->delete();
      if($save){
          return 'data berhasil di simpan';
      }
     return ;  
    }

    public function countCustomer()
    {
        $order = DB::select('select * from orders where cast(created_at AS date ) = curdate()');
        $driver = DB::table('drivers')->get();
        $cust =   User::where('role_id',5)->get();
        $cust_today =   DB::select('select * from users where role_id = 5 AND cast(created_at AS date ) = curdate()');
        // return $cust_today;
        return response()->json([
            'total_customer' => count($cust),
            'total_customer_today' => count($cust_today),
            'total_order' => count($order),
            'total_driver' => count($driver)
        ]);
    }
    
}
