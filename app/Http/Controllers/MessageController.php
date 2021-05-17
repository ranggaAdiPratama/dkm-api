<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
class MessageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function sendMessage(Request $request){
      $actionId = $request->input('actionId');
      $actionData = $request->input('actionData');
      event(new ActionEvent($actionId, $actionData));
    }
    //
}
