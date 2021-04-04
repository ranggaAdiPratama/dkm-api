<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\UserProfile;
use App\Models\Wallet;
use DB;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','getPhoto','getPhotoProduct','refresh']]);
    }

    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        //validate incoming request 
        date_default_timezone_set('Asia/Bangkok');
        $role = $request->input('role_id');
        $this->validate($request, [
            'name'  => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'role_id' =>'required',
            'phone' => 'required',
            'address' => 'required',
        ]);
        if ($request->hasFile('photo')) 
        { 
            $fileExtension = $request->file('photo')->getClientOriginalName(); 
            $file = pathinfo($fileExtension, PATHINFO_FILENAME); 
            $extension = $request->file('photo')->getClientOriginalExtension();
            $fileStore = $file . '_' . time() . '.' . $extension; 
            $path = $request->file('photo')->storeAs('photos',$fileStore); 
        }
        try 
        {
            $user = new User;
            $user->name = $request->input('name');
            $user->email= $request->input('email');
            $user->role_id= $request->input('role_id');
            $user->password = app('hash')->make($request->input('password'));
            $user->save();

            if($user->id !== null &&  $request->hasFile('photo')){
                $profile = new UserProfile;
                $profile->user_id = $user->id;
                $profile->phone = $request->input('phone');
                $profile->phone2 = $request->input('phone2');
                $profile->address = $request->input('address');
                $profile->district_id = $request->input('district_id');
                $profile->village_id = $request->input('village_id');
                $profile->photo = 'photo/'. base64_encode($fileStore);
                $profile->save();
            }else{
                $profile = new UserProfile;
                $profile->user_id = $user->id;
                $profile->phone = $request->input('phone');
                $profile->phone2 = $request->input('phone2');
                $profile->address = $request->input('address');
                $profile->district_id = $request->input('district_id');
                $profile->village_id = $request->input('village_id');
                $profile->photo = 'photo/YXZhdGFyXzE2MTM4NzQ3NzlfMTYxNDA2NDQ2Mi5qcGc=';
                $profile->save();
            }
            if(intval($role) == 3){
                DB::insert('insert into drivers (user_id,driver_category_id) values (?, ?)', [$user->id, 1]);
                DB::insert('insert into wallet (user_id,begin_balance,update_at) values (?, ?, ?)', [$user->id, 0,date('Y-m-d H:i:s')]);
            }elseif(intval($role) == 4){
                DB::insert('insert into drivers (user_id,driver_category_id) values (?, ?)', [$user->id, 2]);
                DB::insert('insert into wallet (user_id,begin_balance,update_at) values (?, ?, ?)', [$user->id, 0,date('Y-m-d H:i:s')]);
            }

            return response()->json( [
                        'entity' => 'Users Created Successfully', 
            ], 201);

        } 
        catch (\Exception $e) 
        {
            return response()->json( [
                       'entity' => 'users', 
                       'action' => 'create', 
                       'result' => 'failed'
            ], 409);
        }
    }
	
     /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */	 
    public function login(Request $request)
    {
          //validate incoming request 
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        
        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {			
            return response()->json(['message' => 'Email/Password Salah'], 401);
        }
        // if(auth()->user()->online === '1'){
        //     return response()->json(['message' => 'Akun anda telah login di perangkat lain'], 402);
        // }
        $id = auth()->user()->id;
        DB::table('users')->where('id',$id)->update(['online'=> '1']);
        $currentUser = Auth::user();
        $token = auth()->setTTL(7200)->attempt($credentials);
        return $this->respondWithToken($token, $currentUser);
    }
	
     /**
     * Get user details.
     *
     * @param  Request  $request
     * @return Response
     */	 	
    public function me()
    {   
        $id = auth()->user()->id;
        // dd($id);
        $profile = User::join('user_profiles as up','users.id','up.user_id')
                        ->where('users.id',$id)
                        ->first();
        $saldo = DB::select('
                        SELECT
                        wallet.id, 
                        wallet_transaction.wallet_id,   
                        wallet.begin_balance, 
                        wallet.ending_balance, 
                        sum(wallet_transaction.amount) as amount
                        FROM
                        wallet
                        INNER JOIN
                        wallet_transaction
                        ON 
                        wallet.id = wallet_transaction.wallet_id
                        where user_id ='.$id.'
                        GROUP BY wallet.id,wallet_id,wallet.begin_balance,wallet.ending_balance,amount
            ');
            if(!empty($saldo)){
                $end_balance = $saldo[0]->begin_balance + $saldo[0]->amount;
            } else{
                $end_balance = 0;
            }

        return response()->json([
            'data' =>  array(
            'id' => intval($profile->id),
            'name' => $profile->name,
            'email' => $profile->email,
            'password' => $profile->password,
            'role_id' => intval($profile->role_id),
            'phone' => $profile->phone,
            'phone2' => $profile->phone2,
            'district' => $profile->district_id,
            'village' => $profile->village_id,
            'address' => $profile->address,
            'photo' =>$profile->photo,
            'status' =>intval($profile->online),
            'saldo' =>$end_balance
        )
        ]);
    }

    public function logout()
    {   $id = auth()->user()->id;
        DB::table('users')->where('id',$id)->update(['online'=> 0]);
        $logout = Auth::logout(true);
        return "Logout Berhasil";
    }

    public function refresh()
    {   
        $currentUser = Auth::user();
        $newToken = auth()->refresh(true, true);
        return $this->respondWithToken($newToken, $currentUser);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function roleList()
    {
        $data = Role::all('id','name');

        if(!empty($data)){
            return response()->json(['data' => $data], 200);
        }
        return response()->json('Data Tidak Ditemukan');
    }

    public function userList()
    {
        $data = DB::table('user_list')->get();
        if(!empty($data)){
            return response()->json(['data' => $data], 200);
        }
        return response()->json('Data Tidak Ditemukan');
    }
    
    public function getPhoto($name)
    {
        $avatar_path = storage_path('app/photos') . '/'.base64_decode($name);

        if (file_exists($avatar_path)) {
            $file = file_get_contents($avatar_path);
            return response($file, 200)->header('Content-Type', 'image/jpeg');
          }
           $res['success'] = false;
           $res['message'] = "Avatar not found";
          
          return $res;
    }

    public function getPhotoProduct($name)
    {
        $avatar_path = storage_path('app/photo/product') . '/'.base64_decode($name);

        if (file_exists($avatar_path)) {
            $file = file_get_contents($avatar_path);
            return response($file, 200)->header('Content-Type', 'image/jpeg');
          }
           $res['success'] = false;
           $res['message'] = "Avatar not found";
          
          return $res;
    }


    public function update(Request $request)
    {
         //validate incoming request 
         $role = $request->input('role_id');
         $id = $request->input('id');
         $this->validate($request, [
             'name'  => 'required|string',
             'email' => 'required|email',
             'phone' => 'required',
             'address' => 'required',
         ]);
         if ($request->hasFile('photo')) 
         { 
             $fileExtension = $request->file('photo')->getClientOriginalName(); 
             $file = pathinfo($fileExtension, PATHINFO_FILENAME); 
             $extension = $request->file('photo')->getClientOriginalExtension();
             $fileStore = $file . '_' . time() . '.' . $extension; 
             $img ='http://192.168.18.60:8000/photo/product'. base64_encode($fileStore);
             $path = $request->file('photo')->storeAs('photos',$fileStore); 
         }
         try 
         {
            $user = User::where('id',$id)
                        ->Update([
                            'name' => $request->input('name'),
                            'email' => $request->input('email'),
                            'role_id' => $request->input('role_id')
                        ]);
        if ($request->hasFile('photo')) 
        { 
            $userProfile = UserProfile::where('user_id',$id)
                                    ->update([
                                        'phone' => $request->input('phone'),
                                        'phone2' => $request->input('phone2'),
                                        'district_id' => $request->input('district_id'),
                                        'village_id' => $request->input('village_id'),
                                        'address' => $request->input('address'),
                                        'photo' => $img
                                    ]);
        }else{
            { 
                $userProfile = UserProfile::where('user_id',$id)
                                        ->update([
                                            'phone' => $request->input('phone'),
                                            'phone2' => $request->input('phone2'),
                                            'district_id' => $request->input('district_id'),
                                            'village_id' => $request->input('village_id'),
                                            'address' => $request->input('address'),
                                        ]);
            }
        }
 
             return response()->json( [
                         'entity' => 'Users Updated Successfully', 
             ], 201);
 
         } 
         catch (\Exception $e) 
         {
             return response()->json( [
                        'entity' => 'users', 
                        'action' => 'create', 
                        'result' => 'failed'
             ], 409);
         }
    }

    public function updateAddress(Request $request)
    {
         //validate incoming request 
         $id = auth()->user()->id;
        DB::table('user_profiles')->where('user_id',$id)->update([
                            'district_id' => $request->input('district_id'),
                            'village_id' => $request->input('village_id'),
                            'address' => $request->input('address')
                        ]);
        
            return response()->json('data updated successfully',200);
     
     
    }

    public function changeStatus ($id) 
    {
      $cek =  DB::table('users')->where('id', $id)->first('online')->online;
      if($cek == 1){
      DB::table('users')->where('id', $id)->update(['online' => 0]);
      }else{
        DB::table('users')->where('id', $id)->update(['online' => 1]);
      }
    }

  
    
}