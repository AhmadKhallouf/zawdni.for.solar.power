<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\Notifications\ResetPasswordVerificationNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Otp;

class ForgetPasswordController extends Controller
{
    

    public function forget_password(ForgetPasswordRequest $request){

        $input = $request->only('email');

        $user = User::where('email',$input)->first();
        $user->notify(new ResetPasswordVerificationNotification());

        return response()->json(['success'=>'true'],200);
    }
}
