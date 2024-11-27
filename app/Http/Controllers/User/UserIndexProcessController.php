<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\GetDamagesInformationToSuggestRequest;
use App\Http\Requests\User\GetSolutionForDamageRequest;
use App\Models\Cart;
use App\Models\Damage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserIndexProcessController extends Controller
{
    //notifications

    //send unread notification 
    public function get_unread_notifications(){
        $user = User::find(Auth::user()->id);
        $dataUnreadNotificationsCount = $user->unreadNotifications->count();
        if($dataUnreadNotificationsCount > 0){
        $dataUnreadNotifications = $user->unreadNotifications;
        $unreadNotifications = array();
        foreach($dataUnreadNotifications as $item){
            $help_array = ['id'=> $item->id, 'message' => $item->data['message'], 'read_at' => $item->read_at, 'created_at' => $item->created_at];
            array_push($unreadNotifications,$help_array);
        }
        return response()->json(['Notifications' => $unreadNotifications],200);
    }else{
        return response()->json(['message' => 'there is no unread notifications'],400);
    }
    }
    //----------------------------------------------------------------------------------
    //send all notifications
    public function get_all_notifications(){
        $user = User::find(Auth::user()->id); 
        $dataNotificationsCount = $user->notifications->count();
        if($dataNotificationsCount > 0){
        $dataNotifications = $user->notifications;
        $notifications = array();
        foreach($dataNotifications as $item){
            $help_array = ['id'=> $item->id, 'message' => $item->data['message'], 'read_at' => $item->read_at, 'created_at' => $item->created_at];
            array_push($notifications,$help_array);
        }
        return response()->json(['Notifications' => $notifications],200);
    }else{
        return response()->json(['message' => 'there is no notifications'],400);
    }
    }

    //----------------------------------------------------------------------------------
    //mark notification as read
    public function mark_notification_as_read($notificationId){

        $user = User::find(Auth::user()->id); // Retrieve the user

        $notification = $user->notifications()->where('id', $notificationId)->first();
       
        if ($notification->read_at == null) {
            $notification->markAsRead();
            return response()->json(['message' => 'Notification marked as read'],200);
        } else {
            return response()->json(['error' => 'Notification is already read'], 404);
        }
    }

    //-----------------------------------------------------------------------------------
    //mark all notifications as read
    public function mark_all_notifications_as_read(){

        $user = User::find(Auth::user()->id); // Retrieve the user
        $unreadNotificationsCount = $user->unreadNotifications()->count();
        if($unreadNotificationsCount > 0){
            $user->unreadNotifications->markAsRead();
            return response()->json(['message' => 'All notifications marked as read'],200);
        }else{
            return response()->json(['message' => 'All notifications already marked as read'],200);
        }

        
    }

    //-----------------------------------------------------------------------------------
    //delete notification
    public function delete_notification($notificationId){

        $user = User::find(Auth::user()->id); // Retrieve the user

        $notification = $user->notifications()->where('id', $notificationId)->first() ?? 0;

        if ($notification) {
            $notification->delete();
            return response()->json(['message' => 'Notification deleted successfully'],200);
        } else {
            return response()->json(['error' => 'Notification not found'], 404);
        }
    }

//-----------------------------------------------------------------------------------------------------------------------


    //my carts in index
    public function get_my_carts(){

        $my_carts = Cart::where('user_id',Auth::user()->id)->select('id','user_id','type_of_system','status')->get();
        if($my_carts->isNotEmpty()){
            return response()->json(['my_cart' => $my_carts],200);
        }else{
            return response()->json(['my_cart' => 'there is not carts for you yet'],200);
        }
    }

//---------------------------------------------------------------------------------------------------------------------------------------

    //damages

    //get damages information for suggestion
    public function get_damages_information_to_suggest(GetDamagesInformationToSuggestRequest $request){
        $data = $request->validated();
        $manufactureCompanies = [];
        $modelOfInverters = [];
        $watt = [];
        $codes = [];

        if(empty($data['manufacture_company'])&& empty($data['model_of_inverter']) && empty($data['watt']) && empty($data['code'])){
            $manufactureCompanies = Damage::where('type_of_inverter',$data['type_of_inverter'])->distinct('manufacture_company')->pluck('manufacture_company');
      
        }elseif(empty($data['model_of_inverter']) && empty($data['watt']) && empty($data['code'])){
            $modelOfInverters = Damage::where('type_of_inverter',$data['type_of_inverter'])->where('manufacture_company',$data['manufacture_company'])->distinct('model_of_inverter')->pluck('model_of_inverter');
      
        }elseif(empty($data['watt']) && empty($data['code'])){
            $watt = Damage::where('type_of_inverter',$data['type_of_inverter'])->where('manufacture_company',$data['manufacture_company'])->where('model_of_inverter',$data['model_of_inverter'])->distinct('watt')->pluck('watt');
        
        }elseif(empty($data['code'])){
            $codes = Damage::where('type_of_inverter',$data['type_of_inverter'])->where('manufacture_company',$data['manufacture_company'])->where('model_of_inverter',$data['model_of_inverter'])->where('watt',$data['watt'])->distinct('code')->pluck('code');
        }else{
            $manufactureCompanies = [];
            $modelOfInverters = [];
            $watt = [];
            $codes = [];
        }
        

        $information = [
            'manufacture_companies' => $manufactureCompanies,
            'model_of_inverters' => $modelOfInverters,
            'watts' => $watt,
            'codes' => $codes,
        ];

        return response()->json($information,200);
    }

    //-----------------------------------------------------------------------------------

    //method to get specific damage
    public function get_solution(GetSolutionForDamageRequest $request){

        $information = $request->validated();

        
        $test_if_exists = Damage::where('type_of_inverter',$information['type_of_inverter'])
                                      ->where('manufacture_company',$information['manufacture_company'])
                                      ->where('model_of_inverter',$information['model_of_inverter'])
                                      ->where('watt',$information['watt'])->where('code',$information['code'])->exists();
                                    
        if($test_if_exists){
            $problem_and_solution = Damage::where('type_of_inverter',$information['type_of_inverter'])
                                          ->where('manufacture_company',$information['manufacture_company'])
                                          ->where('model_of_inverter',$information['model_of_inverter'])
                                          ->where('watt',$information['watt'])->where('code',$information['code'])
                                          ->select('description','solution')->first();
            
            return response()->json([
                'problem' => $problem_and_solution->description,
                'solution' => $problem_and_solution->solution,
            ],200);


        }else{
            return response()->json(['message' => 'sorry sir, this code does not exists in our database, to be sure please review your information that you have inter it in form....thanks for your trust'],400);
        }                              

    }

//-----------------------------------------------------------------------------------------------------------------------------

    
    


















}
