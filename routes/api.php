<?php

use App\Http\Controllers\Admin\AdminIndexProcessController;
use App\Http\Controllers\Admin\BatteryProcessController;
use App\Http\Controllers\Admin\PanelProcessController;
use App\Http\Controllers\Admin\InverterProcessController;
use App\Http\Controllers\Admin\DamageProcessController;
use App\Http\Controllers\Admin\LoadProcessController;
use App\Http\Controllers\Admin\UserProcessController;
use App\Http\Controllers\Admin\CartProcessController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UpdateEmailAddressController;
use App\Http\Controllers\Auth\UpdateProfileController;
use App\Http\Controllers\User\UserCartProcessController;
use App\Http\Controllers\User\UserIndexProcessController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\TextUI\XmlConfiguration\Group;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//register and login routes
Route::post('register',[RegisterController::class,'register']);
Route::post('login',[LoginController::class,'login']);

//forget and reset password
Route::post('password/forget_password',[ForgetPasswordController::class,'forget_password']);
Route::post('password/reset_password',[ResetPasswordController::class,'reset_password']);

//users has authentication route
Route::middleware('auth:sanctum')->group(function(){

    //update profile routes
     Route::get('/profile',function (Request $request) {return $request->user();});
     Route::post('profile',[UpdateProfileController::class,'update_profile']);

    //send email verification routes
    
    Route::get('send_emailVerification',[EmailVerificationController::class,'send_emailVerification']);
    Route::post('email_verification',[EmailVerificationController::class,'email_verification']);
    //send updated email verification routes
    Route::post('send_Notification_to_update_email',[UpdateEmailAddressController::class,'send_Notification_to_update_email']);
    Route::post('verification_new_email',[UpdateEmailAddressController::class,'verification_new_email']);

    //Admin only can access to this group of routes
    Route::middleware('AuthAdmin')->group(function(){

        //routes for process in batteries
        Route::get('get_batteries',[BatteryProcessController::class,'get_batteries']);
        Route::get('get_specific_battery_to_admin/{id}',[BatteryProcessController::class,'get_specific_battery']);
        Route::post('add_battery',[BatteryProcessController::class,'add_battery']);
        Route::post('update_battery/{id}',[BatteryProcessController::class,'update_battery']);
        Route::get('delete_battery/{id}',[BatteryProcessController::class,'delete_battery']);

        //routes for process in panels
        Route::get('get_panels',[PanelProcessController::class,'get_panels']);
        Route::get('get_specific_panel_to_admin/{id}',[PanelProcessController::class,'get_specific_panel']);
        Route::post('add_panel',[PanelProcessController::class,'add_panel']);
        Route::post('update_panel/{id}',[PanelProcessController::class,'update_panel']);
        Route::get('delete_panel/{id}',[PanelProcessController::class,'delete_panel']);

        //routes for process in inverters
        Route::get('get_inverters',[InverterProcessController::class,'get_inverters']);
        Route::get('get_specific_inverter_to_admin/{id}',[InverterProcessController::class,'get_specific_inverter']);
        Route::post('add_inverter',[InverterProcessController::class,'add_inverter']);
        Route::post('update_inverter/{id}',[InverterProcessController::class,'update_inverter']);
        Route::get('delete_inverter/{id}',[InverterProcessController::class,'delete_inverter']);

       

        //routes for process in Damages
        Route::get('get_damages',[DamageProcessController::class,'get_damages']);
        Route::get('get_specific_damage/{id}',[DamageProcessController::class,'get_specific_damage']);
        Route::post('add_damage',[DamageProcessController::class,'add_damage']);
        Route::post('update_damage/{id}',[DamageProcessController::class,'update_damage']);
        Route::get('delete_damage/{id}',[DamageProcessController::class,'delete_damage']);

        //routes for process in Loads
        Route::get('get_loads',[LoadProcessController::class,'get_loads']);
        Route::get('get_specific_load/{id}',[LoadProcessController::class,'get_specific_load']);
        Route::post('add_load',[LoadProcessController::class,'add_load']);
        Route::post('update_load/{id}',[LoadProcessController::class,'update_load']);
        Route::get('delete_load/{id}',[LoadProcessController::class,'delete_load']);

        //routes for process in users
        Route::get('get_users',[UserProcessController::class,'get_users']);
        Route::get('get_specific_user/{id}',[UserProcessController::class,'get_specific_user']);
        Route::get('block_user/{id}',[UserProcessController::class,'block_user']);
        Route::get('unblock_user/{id}',[UserProcessController::class,'unblock_user']);
        Route::get('delete_user/{id}',[UserProcessController::class,'delete_user']);

        //routes for process in carts
        Route::get('get_carts',[CartProcessController::class,'get_carts']);
        Route::get('get_specific_cart/{id}',[CartProcessController::class,'get_specific_cart']);
        Route::get('accept_cart/{id}',[CartProcessController::class,'accept_cart']);
        Route::get('refuse_cart/{id}',[CartProcessController::class,'refuse_cart']);
        Route::get('back_to_processing/{id}',[CartProcessController::class,'back_to_processing']);
        Route::get('deliver_cart/{id}',[CartProcessController::class,'deliver_cart']);
        Route::post('update_total_price/{id}',[CartProcessController::class,'update_total_price']);
        Route::get('archive_cart/{id}',[CartProcessController::class,'archive_cart']);
        Route::get('delete_cart/{id}',[CartProcessController::class,'delete_cart']);

        //routes for index page in dashboard
        Route::get('get_information_count',[AdminIndexProcessController::class,'get_information_count']);
       
        //notifications
        Route::get('get_unread_notifications',[AdminIndexProcessController::class,'get_unread_notifications']);
        Route::get('get_all_notifications',[AdminIndexProcessController::class,'get_all_notifications']);
        Route::get('mark_notification_as_read/{notificationId}',[AdminIndexProcessController::class,'mark_notification_as_read']);
        Route::get('mark_all_notifications_as_read',[AdminIndexProcessController::class,'mark_all_notifications_as_read']);
        Route::get('delete_notification/{notificationId}',[AdminIndexProcessController::class,'delete_notification']);

        //supplement price
        Route::get('get_supplement_price',[AdminIndexProcessController::class,'get_supplement_price']);
        Route::post('update_supplement_price',[AdminIndexProcessController::class,'update_supplement_price']);

        //archive
        Route::get('get_archives',[AdminIndexProcessController::class,'get_archives']);
        Route::get('delete_item_from_archive/{id}',[AdminIndexProcessController::class,'delete_item_from_archive']);
    });  

    //only auth users can access to this routes
    Route::middleware('AuthUser')->group(function(){

        //route for start reserve a cart 
        Route::post('start_reserve',[UserCartProcessController::class,'start_reserve']);
        //route for get all loads for user to choose 
        Route::get('get_loads_for_user/{id}',[UserCartProcessController::class,'get_loads_for_user']);
        //route for get specific load for user to check details
        Route::get('get_specific_load_for_user/{id}',[UserCartProcessController::class,'get_specific_load_for_user']);
        //route for added loads from user to database to calculate the total capacity
        Route::post('collect_loads_from_user/{id}',[UserCartProcessController::class,'collect_loads_from_user']); 
        //route for set night winter hours to operate the system on batteries
        Route::get('set_winter_night_operate_hours/{id}/{hours}',[UserCartProcessController::class,'set_winter_night_operate_hours']);
        //route for set run way to operate the system
        Route::post('set_way_to_run/{id}',[UserCartProcessController::class,'set_way_to_run']);
        //route for get the appropriate inverter for cart's user
        Route::get('get_appropriate_inverters/{id}',[UserCartProcessController::class,'get_appropriate_inverters']);
        //route for get the information about specific inverter for user
        Route::get('get_specific_inverter/{id}',[UserCartProcessController::class,'get_specific_inverter']);
        //route for added the chooses inverter by user to his cart in database
        Route::post('add_inverters_to_cart/{id}',[UserCartProcessController::class,'add_inverters_to_cart']);
        //route for get the appropriate batteries for cart's user
        Route::get('get_appropriate_batteries/{id}',[UserCartProcessController::class,'get_appropriate_batteries']);
        //route for get information about specific battery for user
        Route::get('get_specific_battery/{id}',[UserCartProcessController::class,'get_specific_battery']);
        //route for added the chooses batteries by user to his cart in database
        Route::post('add_batteries_to_cart/{id}',[UserCartProcessController::class,'add_batteries_to_cart']);
        //route for get the appropriate panels for cart's user
        Route::get('get_appropriate_panels/{id}',[UserCartProcessController::class,'get_appropriate_panels']);
        //route for get information about specific panel for c user
        Route::get('get_specific_panel/{id}',[UserCartProcessController::class,'get_specific_panel']);
        //route for added the chooses panels by user to his cart in database
        Route::post('add_panels_to_cart/{id}',[UserCartProcessController::class,'add_panels_to_cart']);
        //route for add the distance between the panels and the inverter 
        Route::post('add_distance_for_cables_to_cart/{id}',[UserCartProcessController::class,'add_distance_for_cables_to_cart']);
        //route for get the information that are input by user to confirm it
        Route::get('get_cart_info_to_confirm/{id}',[UserCartProcessController::class,'get_cart_info_to_confirm']);
        //route for confirm the cart
        Route::get('confirm_the_cart/{id}',[UserCartProcessController::class,'confirm_the_cart']);



        //notifications
        Route::get('u_get_unread_notifications',[UserIndexProcessController::class,'get_unread_notifications']);
        Route::get('u_get_all_notifications',[UserIndexProcessController::class,'get_all_notifications']);
        Route::get('u_mark_notification_as_read/{notificationId}',[UserIndexProcessController::class,'mark_notification_as_read']);
        Route::get('u_mark_all_notifications_as_read',[UserIndexProcessController::class,'mark_all_notifications_as_read']);
        Route::get('u_delete_notification/{notificationId}',[UserIndexProcessController::class,'delete_notification']);
        

        //my carts
        Route::get('get_my_carts',[UserIndexProcessController::class,'get_my_carts']);

        //damage
        Route::post('get_damages_information_to_suggest',[UserIndexProcessController::class,'get_damages_information_to_suggest']);
        Route::post('get_solution',[UserIndexProcessController::class,'get_solution']);

    });
    
});

