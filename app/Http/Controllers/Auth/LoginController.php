<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    public function login(LoginRequest $request){

        $credentials = [
            'email'=>$request->email,
            'password'=>$request->password,
        ];

        if(auth()->attempt($credentials)){

            $user = Auth::user();
            $user->tokens()->delete();

            $success['token'] = $user->createToken(request()->userAgent())->plainTextToken;
            $success['name'] = $user->first_name;
            $success['success'] = true;

            

            return response()->json($success,201);

        }else{

            return response()->json(['error'=>__('auth.Unauthorized')],401);

        }


    }
}
