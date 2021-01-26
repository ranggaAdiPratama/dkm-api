<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
  
class Controller extends BaseController
{
    public function respondWithToken($token ,$currentUser)
    {
        $id = $currentUser->id;
        $profile = User::all('');
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => null,
            'users' => $currentUser,
            'profile' => $profile

        ], 200);
    }
}