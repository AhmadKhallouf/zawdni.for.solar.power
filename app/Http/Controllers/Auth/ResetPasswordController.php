<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Otp;

class ResetPasswordController extends Controller
{
    private $otp;

    public function __construct()
    {
        $this->otp = new Otp;
    }

    public function reset_password(ResetPasswordRequest $request){

        $otp2 = $this->otp->validate($request->email,$request->otp);

        if(!$otp2->status){
            return response()->json(['error'=>$otp2],401);
        }

        $user = User::where('email',$request->email)->first();
        $user->update(['password'=> Hash::make($request->password)]);
        $user->tokens()->delete();

        return response()->json([
        
            'success'=>true,
            'name'=>$user->first_name,
            'message'=>'your password updated successfully, please login agin',

        ],200);




    }
}
