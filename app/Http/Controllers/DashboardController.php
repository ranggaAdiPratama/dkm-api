<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Dashboard;
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
    
}
