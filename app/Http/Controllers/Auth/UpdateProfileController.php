<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ImageProcessing;
use Storage;

class UpdateProfileController extends Controller
{
    use ImageProcessing;

    public function update_profile(UpdateProfileRequest $request){

        $user = $request->user();
        $validateData = $request->validated();

        $user->update($validateData);
        $user = $user->refresh();

        return response()->json([
            'user'=>$user,
            'success'=>true, 
        ]);

    }
}
