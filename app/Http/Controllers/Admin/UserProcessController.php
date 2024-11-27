<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserProcessController extends Controller
{
    //get all user accounts from database
    public function get_users(){
        $users = User::where('role','user')->get();
        return response()->json($users,200);
     }


    //get a specific user account from database
    public function get_specific_user($id){
        $user = User::where('role','user')->where('id',$id)->first();
        return response()->json($user,200);
    }

    //blocked a specific user account in database
    public function block_user($id){
        $success = User::where('role','user')->where('id',$id)->update(['status'=>'blocked']);
        if($success == true){
            return response()->json(['success'=>'this account is blocked successfully'],200);
        }else{
            return response()->json(['error'=>'!!!!!this account does not block, please check if this account is deleted from database ' ],400);
        }
        
    }

     //blocked a specific user account in database
     public function unblock_user($id){
        $success = User::where('role','user')->where('id',$id)->update(['status'=>'active']);
        if($success == true){
            return response()->json(['success'=>'this account is active successfully'],200);
        }else{
            return response()->json(['error'=>'!!!!!this account does not active, please check if this account is deleted from database ' ],400);
        }
        
    }

    //delete an account from database
    public function delete_user($id){
        $success = User::where('role','user')->where('id',$id)->delete();
        if($success == true){
            return response()->json(['success'=>'this account is deleted successfully'],200);
        }else{
            return response()->json(['error'=>'!!!!!this account does not delete, please check if this account is deleted from database previously ' ],400);
        }
    }
}
