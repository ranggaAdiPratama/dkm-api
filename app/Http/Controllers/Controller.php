<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use DB;
  
class Controller extends BaseController
{
    public function respondWithToken($token ,$currentUser)
    {
        $id = $currentUser->id;
        $profile = DB::select('select * from users join user_profiles ON users.id = user_profiles.user_id where users.id='.$id);
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
            'token' => $token,
            'expires_in' => Auth::factory()->getTTL()*30,
            'users' => array(
                'id' => intval($profile[0]->id),
                'name' => $profile[0]->name,
                'email' => $profile[0]->email,
                'password' => $profile[0]->password,
                'role_id' => intval($profile[0]->role_id),
                'phone' => $profile[0]->phone,
                'phone2' => $profile[0]->phone2,
                'city' => $profile[0]->city_id,
                'district' => $profile[0]->district_id,
                'village' => $profile[0]->village_id,
                'address' => $profile[0]->address,
                'photo' =>$profile[0]->photo,
                'saldo' =>$end_balance,
                'status' => intval($profile[0]->online)
            )

        ], 200);
    }
}