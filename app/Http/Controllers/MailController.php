<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use DB;
class MailController extends Controller

{
public function mail(Request $request) {
$email = $request->input('email');
$data = DB::table('users')->join('user_profiles','users.id','user_profiles.user_id')->where('users.email',$email)->get();
$arr = array(
    'name' => $data[0]->name,
    'id' => $data[0]->id,
    'email' => $data[0]->email,
    'phone' => $data[0]->phone
);
$Mail = Mail::send('changePassword', $arr, function($message) use ($email,$data) {
$message->to($email, $data[0]->name)
->subject('Email Konfirmasi Penggantian Password');
$message->from('yogi@enjiner.id', 'DKM');
});
// Mail::to($email)->send();
echo "Email Sent. Check your inbox.";
}
}