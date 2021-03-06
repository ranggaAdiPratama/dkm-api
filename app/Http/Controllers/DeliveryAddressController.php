<?php

namespace App\Http\Controllers;
use App\Models\DeliveryAddress;

use Illuminate\Http\Request;

class DeliveryAddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('CorsMiddleware');
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DeliveryAddress::all();
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $input = $request->all();
        $save = DeliveryAddress::insert($input);

        return response()->json('Data Sukses Disimpan');
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
        $data = DeliveryAddress::findOrFail($id);
        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = DeliveryAddress::findOrFail($id);
        return response()->json($data,"data sukses di tampilkan");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $request->all();
        $id = $request->input('id');
        DeliveryAddress::where('id',$id)
                            ->update($input);
        return response()->json('data sukses di update');
        

        return 'Data Sukses di Update';
    }

}
