<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSupplementPriceRequest;
use App\Models\Archive;
use App\Models\Battery;
use App\Models\Cart;
use App\Models\Inverter;
use App\Models\Panel;
use App\Models\SupplementPrice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminIndexProcessController extends Controller
{
    //method to get counting about carts in database
    public function get_information_count(){

        //get counting about carts in database
        $allCarts = Cart::count();
        $incompleteCarts = Cart::where('status','incomplete')->count();
        $processingCarts = Cart::where('status','processing')->count();
        $acceptedCarts = Cart::where('status','accepted')->count();
        $refusedCarts = Cart::where('status','refused')->count();
        $deliveredCarts = Cart::where('status','delivered')->count();


        // get counting about users in database
        $allUser = User::count();
        $admins = User::where('role','admin')->count();
        $users = User::where('role','user')->count();


        //get counting about inverters in database
        $allInverters = Inverter::sum('quantity_available');

        $household_1200 = Inverter::where('type','household')->where('watt',1200)->sum('quantity_available');
        $household_2500 = Inverter::where('type','household')->where('watt',2500)->sum('quantity_available');
        $household_3000 = Inverter::where('type','household')->where('watt',3000)->sum('quantity_available');
        $household_3500 = Inverter::where('type','household')->where('watt',3500)->sum('quantity_available');
        $household_4000 = Inverter::where('type','household')->where('watt',4000)->sum('quantity_available');
        $household_5000 = Inverter::where('type','household')->where('watt',5000)->sum('quantity_available');
        $household_6000 = Inverter::where('type','household')->where('watt',6000)->sum('quantity_available');
        $household_11000 = Inverter::where('type','household')->where('watt',11000)->sum('quantity_available');
        $household_15000 = Inverter::where('type','household')->where('watt',15000)->sum('quantity_available');


        $agriculture_750 = Inverter::where('type','agriculture')->where('watt',750)->sum('quantity_available');
        $agriculture_1500 = Inverter::where('type','agriculture')->where('watt',1500)->sum('quantity_available');
        $agriculture_2200 = Inverter::where('type','agriculture')->where('watt',2200)->sum('quantity_available');
        $agriculture_4000 = Inverter::where('type','agriculture')->where('watt',4000)->sum('quantity_available');
        $agriculture_5500 = Inverter::where('type','agriculture')->where('watt',5500)->sum('quantity_available');
        $agriculture_7500 = Inverter::where('type','agriculture')->where('watt',7500)->sum('quantity_available');
        $agriculture_22000 = Inverter::where('type','agriculture')->where('watt',22000)->sum('quantity_available');
        $agriculture_37000 = Inverter::where('type','agriculture')->where('watt',37000)->sum('quantity_available');


        $industrial_60000 = Inverter::where('type','industrial')->where('watt',60000)->sum('quantity_available');
        $industrial_125000 = Inverter::where('type','industrial')->where('watt',125000)->sum('quantity_available');
        $industrial_137000 = Inverter::where('type','industrial')->where('watt',137000)->sum('quantity_available');
        $industrial_300000 = Inverter::where('type','industrial')->where('watt',300000)->sum('quantity_available');
        $industrial_600000 = Inverter::where('type','industrial')->where('watt',600000)->sum('quantity_available');


        //get counting about batteries in database
        $allBatteries = Battery::sum('quantity_available');

        $batteries_12 = Battery::where('volt',12)->sum('quantity_available');
        $batteries_24 = Battery::where('volt',24)->sum('quantity_available');
        $batteries_48 = Battery::where('volt',48)->sum('quantity_available');


        //get counting about panels in database
        $allPanels = Panel::sum('quantity_available');


       //get counting sells for every month in this year
       $year = date('Y'); // Get the current year
        $totalPrices = Archive::selectRaw('MONTH(created_at) as month, SUM(total_price) as total_price')
                         ->whereYear('created_at', $year)
                         ->groupBy('month')
                         ->pluck('total_price', 'month');

        $fullYearTotalPrices = [];
        for ($i = 1; $i <= 12; $i++) {
            $fullYearTotalPrices[$i] = $totalPrices[$i] ?? 0;
        }




        return response()->json([
            'allCarts' => $allCarts,
            'incompleteCarts' => $incompleteCarts,
            'processingCarts' => $processingCarts,
            'acceptedCarts' => $acceptedCarts,
            'refusedCarts' => $refusedCarts,
            'deliveredCarts' => $deliveredCarts,

            'allUser' => $allUser,
            'admins' => $admins,
            'users' => $users,

            'allInverters' => $allInverters,
            'household_1200' => $household_1200,
            'household_2500' => $household_2500,
            'household_3000' => $household_3000,
            'household_3500' => $household_3500,
            'household_4000' => $household_4000,
            'household_5000' => $household_5000,
            'household_6000' => $household_6000,
            'household_11000' => $household_11000,
            'household_15000' => $household_15000,
            'agriculture_750' => $agriculture_750,
            'agriculture_1500' => $agriculture_1500,
            'agriculture_2200' => $agriculture_2200,
            'agriculture_4000' => $agriculture_4000,
            'agriculture_5500' => $agriculture_5500,
            'agriculture_7500' => $agriculture_7500,
            'agriculture_22000' => $agriculture_22000,
            'agriculture_37000' => $agriculture_37000,
            'industrial_60000' => $industrial_60000,
            'industrial_125000' => $industrial_125000,
            'industrial_137000' => $industrial_137000,
            'industrial_300000' => $industrial_300000,
            'industrial_600000' => $industrial_600000,

            'allBatteries' => $allBatteries,
            'batteries_12' => $batteries_12,
            'batteries_24' => $batteries_24,
            'batteries_48' => $batteries_48,

            'allPanels' => $allPanels,

            'fullYearTotalPrices' => $fullYearTotalPrices,

        ],200);
    }

//-------------------------------------------------------------------------------------------

   
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


//---------------------------------------------------------------------------------------------------------------------

    //get supplement price
    public function get_supplement_price(){

        $supplement_price = SupplementPrice::where('id',1)->select('delivery_for_one_kiloMeter_cost','base_panel_cost','dollar_price_against_sp','one_meter_of_cables_cost','household_installation_cost','agriculture_installation_cost','industrial_installation_cost')->first();

        return response()->json(['supplement_price' => $supplement_price],200);
    }

    //-------------------------------------------------------------------------

    //update supplement price
    public function update_supplement_price(UpdateSupplementPriceRequest $request){

        $supplement_price = SupplementPrice::where('id',1)->first();
        $validateData = $request->validated();

        $supplement_price->update($validateData);
        $true = $supplement_price->refresh();

        if($true){
        return response()->json(['success' => 'the supplement price has been updated successfully'],200);
        }else{
            return response()->json(['success' => 'there is wrong please try again!!!'],200);
        }
    }

//------------------------------------------------------------------------------------------------------------------


    //archive method
    public function get_archives(){
        $archives = Archive::all();
        return response()->json(['archives' => $archives],200);
    }

    //-------------------------------------------------------------

    //delete record from archive
    public function delete_item_from_archive($id){
        Archive::where('id',$id)->delete();
        return response()->json(['success' => 'the item has been deleted from archive successfully'],200);
    }




}
