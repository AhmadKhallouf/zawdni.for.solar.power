<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Notifications\RegisterNotification;
use App\Models\Sanctum\PersonalAccessToken;
use App\Notifications\EmailVerificationNotification;
use Laravel\Sanctum\Sanctum;

class RegisterController extends Controller
{
    public function register(RegistrationRequest $request){
        $newUser = $request->validated();

        $newUser['password'] = Hash::make($newUser['password']);
        $newUser['role'] = 'user';
        $newUser['status'] = 'active';

        $user = User::create($newUser);

        $success['token'] = $user->createToken('user',['app:all'])->plainTextToken;
        $success['name'] = $user->first_name;
        $success['success'] = true;

         $user->notify(new RegisterNotification());
         $user->notify(new EmailVerificationNotification());

        return response()->json($success,200);

       
 
    }
}