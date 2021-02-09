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
    public function getDistrict()
    {
        $data = DB::table('districts')->get();

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
