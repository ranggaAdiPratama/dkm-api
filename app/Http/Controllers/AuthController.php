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
        $this->middleware('auth:api', ['except' => ['login','register']]);
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
        $role = $request->input('role_id');
        $this->validate($request, [
            'name'  => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'role_id' =>'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        try 
        {
            $user = new User;
            $user->name = $request->input('name');
            $user->email= $request->input('email');
            $user->role_id= $request->input('role_id');
            $user->password = app('hash')->make($request->input('password'));
            $user->save();

            if($user->id !== null){
                $profile = new UserProfile;
                $profile->user_id = $user->id;
                $profile->phone = $request->input('phone');
                $profile->address = $request->input('address');
                $profile->photo = $request->input('photo');
                $profile->save();
            }
            
            if($role == 3){
                DB::insert('insert into wallet (user_id,begin_balance) values (?, ?)', [$user->id, 0]);
                DB::insert('insert into drivers (user_id,driver_category_id) values (?, ?)', [$user->id, 1]);
                $wallet_id = DB::select('select id from wallet order by id desc LIMIT 1');
              
            }
            if(!empty($wallet_id)){
                DB::insert('insert into wallet_transaction (wallet_id) values ('.$wallet_id[0]->id.')');
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
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $currentUser = Auth::user();
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
        return response()->json(auth()->user());
    }

    public function logout()
    {
        $logout = Auth::logout(true);
        return "Logout Berhasil";
    }

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

    
}