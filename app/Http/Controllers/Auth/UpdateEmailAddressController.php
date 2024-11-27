<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateEmailAddressRequest;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\Request;
use Otp;

class UpdateEmailAddressController extends Controller
{
    private $otp;

    public function __construct()
    {
        $this->otp = new Otp;
    }


    public function send_Notification_to_update_email(Request $request){

        $input = $request->only('email');

       User::where('id',$request->user()->id)->update(['email'=>$request->email,'email_verified_at'=>Null,]);
       $user =  User::where('id',$request->user()->id)->first();
        $user->notify(new EmailVerificationNotification());

        $success['success'] = true;

        return response()->json($success,200);
        
        
    }

    public function verification_new_email(UpdateEmailAddressRequest $request){

        $otp2 = $this->otp->validate($request->email,$request->otp);

        if(!$otp2->status){
            return response()->json(['error'=>$otp2],401);
        }

        $user = $request->user();
        $user->update(['email_verified_at'=>now(),]);

        $success['success'] = true;

        return response()->json($success,200);

    }
}
