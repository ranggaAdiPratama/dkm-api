<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
  
class Controller extends BaseController
{
    public function respondWithToken($token ,$currentUser)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => null,
            'users' => $currentUser

        ], 200);
    }
}