<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\Handler;
use DB;

class ApiRegionController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function getCity()
    {
        $data = DB::table('city')->join('service_area','city.id','service_area.city_id')->orderBy('nama')->get();

        if(!empty($data)){
        return response()->json([
                   'data' => $data,
                   'status' => 200
                    ]);
                }
        return response()->json([
            'data' => 'Data Not Found',
            'status' => 400
                ]);
    }


    public function getDistrict($id)
    {
        $data = DB::table('districts')
        ->where('kabupaten_id',$id)
        ->get();

        if(!empty($data)){
        return response()->json([
                   'data' => $data,
                   'status' => 200
                    ]);
                }
        return response()->json([
            'data' => 'Data Not Found',
            'status' => 400
                ]);
    }


    public function getVillage($id)
    {   
        $data = DB::table('villages')
                    ->where('kecamatan_id',$id)
                    ->get();

        if(!empty($data)){
        return response()->json([
                   'data' => $data,
                   'status' => 200
                    ]);
                }
        return response()->json([
            'data' => 'Data Not Found',
            'status' => 400
                ]);
    }
}
