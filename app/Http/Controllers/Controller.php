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
        return response()->json([
            'token' => $token,
            // 'expires_in' => null,
            'users' => $profile,

        ], 200);
    }
}