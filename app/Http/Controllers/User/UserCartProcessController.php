<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StartReserveCartRequest;
use App\Models\AdditionalLoad;
use App\Models\Battery;
use App\Models\Cart;
use App\Models\Inverter;
use App\Models\Load;
use App\Models\Panel;
use App\Models\SupplementPrice;
use App\Models\User;
use App\Notifications\CartCompleteNotification;
use Illuminate\Http\Request;
use App\Notifications\YourCartIsProcessingNotification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NoQuantityAvailableOfInverters;
use Illuminate\Support\Facades\DB;
use App\Traits\GetCartInfo;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNotEmpty;

class UserCartProcessController extends Controller
{
    use GetCartInfo;

    //start reserve the cart
    public function start_reserve(StartReserveCartRequest $request){
        
        $number_of_carts = Cart::where('user_id',Auth::user()->id)->count();
        if($number_of_carts < 6){
        $success = Cart::create([
            'user_id' => $request->user()->id,
            'type_of_system'=>$request->type,
            'status'=>'incomplete',
        ]);

        if($success){
            return response()->json(['success'=>'the cart is reserved successfully, move to next interface',
                                     'cart_id'=>$success->id],200);
        }else{
            return response()->json(['error'=>'there is an error'],400);
        }
    }else{
        return response()->json(['success' => 'sorry sir, you have been reached to maximum number of carts, please check your carts in (my carts) item in navbar, thanks for your trust'],400);
    }
    }

//---------------------------------------------------------------------------------------------------------------------------    

    //get the loads from database for user to choose
    public function get_loads_for_user($id){
        $cart = Cart::where('id',$id)->with('additional_loads')->with('loads')->first();

        $choose_loads = array();
        $additional_loads = array();

        if(!empty($cart->loads)){
            foreach($cart->loads as $load){
                $info = DB::table('carts_loads_pivot')->where('cart_id',$cart->id)->where('load_id',$load->id)->select('run_at_night','operating_voltage')->first();
                $help_array = ['load_id' => $load->id , 'run_at_night' => $info->run_at_night , 'operating_voltage' => $info->operating_voltage];
                array_push($choose_loads,$help_array);
            }
        }

        if(!empty($cart->additional_loads)){
            foreach($cart->additional_loads as $additional_load){
                $help_array = ['load' => $additional_load->load, 'watt' => $additional_load->watt, 'run_at_night' => $additional_load->run_at_night , 'operating_voltage' => $additional_load->operating_voltage];
                array_push($additional_loads,$help_array);
            }
        }

        $loads = Load::select('id','load','watt','description','photo')->get();
        if($loads->isEmpty()){
            return response()->json(['error'=>'there is an error...No loads with your query'],400);
        }else{
            return response()->json(['loads'=>$loads,'choose_loads' => $choose_loads,'additional_loads' => $additional_loads,'cart_id'=>$id,'type_of_system'=>$cart->type_of_system,],200);
        }
    }
    
//-----------------------------------------------------------------------------------------------------------------------------------------------

    //get information about specific load to watch it
    public function get_specific_load_for_user($id){
        $load = Load::where('id',$id)->first();
        if($load != null){
            return response()->json(['load'=>$load],200);
        }else{
            return response()->json(['error'=>'there is an error,or this load is not there in database'],400);
        }
    }

//-----------------------------------------------------------------------------------------------------------------------------------------------

    //get the loads from user to calculate the total electric capacity
    public function collect_loads_from_user(Request $request,$id){
        $defLoads;
        $additional_loads;
        

        if($request->def != null){
            $defLoads = json_decode($request->def);
        }

        if($request->additional != null){
            $additional_loads = json_decode($request->additional);
            // foreach($additional as $item){
            //     foreach($item as $key=>$value){
            //        $help_array = array($key=>intval($value)); 
            //        array_push($additional_day_loads,$help_array);
            //     }
            // }
        }

        // if($request->additional_night_loads != null){
        //     $additional = json_decode($request->additional_night_loads);
        //     foreach($additional as $item){
        //         foreach($item as $key=>$value){
        //            $help_array = array($key=>intval($value)); 
        //            array_push($additional_night_loads,$help_array);
        //         }
        //     }
        // }

        if(!empty($defLoads)){
            $cart = Cart::where('id',$id)->with('loads')->first();
            
            if($cart->loads->isNotEmpty()){
                $cart->loads()->detach();
            }
            //$cart->loads()->sync($defLoads);
            foreach($defLoads as $load){
                $cart->loads()->attach($load->load_id);
                DB::table('carts_loads_pivot')->where('cart_id',$cart->id)->where('load_id',$load->load_id)->update(['run_at_night'=>$load->run_at_night, 'operating_voltage'=>$load->operating_voltage]);
            }
            // $cart_with_add = Cart::where('id',$id)->with('loads')->first();
            // foreach($cart_with_add->loads as $value ){
            //     $default_watt += $value->watt;
            // }

        }

        if(!empty($additional_loads)){

            $cart = Cart::where('id',$id)->with('additional_loads')->first();
            
            if($cart->additional_loads->isNotEmpty()){
                $cart->additional_loads()->delete();
            }

           foreach($additional_loads as $element){

            if($cart->type_of_system == 'agricultural'){
                $element->watt = $element->watt*750;
            }
            
            AdditionalLoad::create([
                'cart_id' => $cart->id,
                'load' => $element->load,
                'watt' => $element->watt,
                'run_at_night' => $element->run_at_night,
                'operating_voltage' => $element->operating_voltage,
            ]);
              
            
           }
        }

        $cart = Cart::where('id',$id)->first();

        return response()->json([
            'success'=>'your loads is added to database successfully',
            'cart_id : ' => $id,
            'type_of_system : ' => $cart->type_of_system,                                            
        ],200);

    }

//-----------------------------------------------------------------------------------------------------------------------------------------------

    //set an needed hours to operate the system in night winter
    public function set_winter_night_operate_hours($id,$hours){

        $cart = Cart::where('id',$id)->update(['number_of_operating_hours_at_night'=>$hours]);
        if($cart != null){
            return response()->json(['success'=>'the night winter hours is considered','id'=>$id],200);
        }else{
            return response()->json(['error'=>'there is an error, please try again'],400);
        }
    }

//-----------------------------------------------------------------------------------------------------------------------------------------------
    //set a way for run the system
    public function set_way_to_run(Request $request,$id){

        $cart = Cart::where('id',$id)->with('loads')->with('additional_loads')->first();

        $total_day_capacity = 0;
        $total_night_capacity = 0;


     if($request->way == 'guarantee'){


            if($cart->type_of_system == 'household'){

                if(!empty($cart->loads)){
            foreach($cart->loads as $load){
               $info = DB::table('carts_loads_pivot')->where('cart_id',$cart->id)->where('load_id',$load->id)->select('run_at_night','operating_voltage')->first();

                if($info->operating_voltage == 1){
                    $total_day_capacity = ($total_day_capacity + ($load->watt * 3));
                    if($info->run_at_night == 1){
                        $total_night_capacity = ($total_night_capacity + ($load->watt * 3));
                    }

                }elseif($info->operating_voltage == 0){
                    $total_day_capacity = ($total_day_capacity + $load->watt);
                    if($info->run_at_night == 1){
                        $total_night_capacity = ($total_night_capacity + $load->watt);
                    }

                }
                                          }
                                        }


                }
                
                if(!empty($cart->additional_loads)){

            foreach($cart->additional_loads as $additional_load){

                if($additional_load->operating_voltage == 1){
                    $total_day_capacity = ($total_day_capacity + ($additional_load->watt * 3));
                    if($additional_load->run_at_night == 1){
                        $total_night_capacity = ($total_night_capacity + ($additional_load->watt * 3));
                    }
                }elseif($additional_load->operating_voltage == 0){
                    $total_day_capacity = ($total_day_capacity + $additional_load->watt );
                    if($additional_load->run_at_night == 1){
                        $total_night_capacity = ($total_night_capacity + $additional_load->watt );
                    }
                }
            }
           }

                                                    
    
                                     }

            elseif($request->way == 'rationalization'){
                $maxDayWattLoad = 0;  $maxDayWattAdditional = 0; 
                $idMaxDayWattLoad = 0; $idMaxDayWattAdditional = 0;
                $maxNightWattLoad = 0; $maxNightWattAdditional = 0;
                $idMaxNightWattLoad = 0; $idMaxNightWattAdditional = 0;

                if($cart->type_of_system == 'household'){


                    if(!empty($cart->loads)){
                        foreach($cart->loads as $load){
                            $info = DB::table('carts_loads_pivot')->where('cart_id',$cart->id)->where('load_id',$load->id)->select('run_at_night','operating_voltage')->first();
                            if($info->operating_voltage == 1){
                                if($load->watt > $maxDayWattLoad ){
                                    $maxDayWattLoad = $load->watt;
                                    $idMaxDayWattLoad = $load->id;
                                }
                                if($info->run_at_night == 1){
                                    if($load->watt > $maxNightWattLoad ){
                                        $maxNightWattLoad = $load->watt;
                                        $idMaxNightWattLoad = $load->id;
                                    }
                                }
                            }
                        }
                    }
                }

                if(!empty($cart->additional_loads)){

                    foreach($cart->additional_loads as $additional_load){
                        if($additional_load->operating_voltage == 1){
                            if($additional_load->watt > $maxDayWattAdditional ){
                                $maxDayWattAdditional = $additional_load->watt;
                                $idMaxDayWattAdditional = $additional_load->id;
                            }
                            if($additional_load->run_at_night == 1){
                                if($additional_load->watt > $maxNightWattAdditional ){
                                    $maxNightWattAdditional = $additional_load->watt;
                                    $idMaxNightWattAdditional = $additional_load->id;
                                }
                            }
                        }
                    }

                }


                if($maxDayWattLoad > $maxDayWattAdditional){
                    $idMaxDayWattAdditional = 0;
                }elseif($maxDayWattLoad < $maxDayWattAdditional){
                    $idMaxDayWattLoad = 0;
                }elseif(($maxDayWattLoad == $maxDayWattAdditional)&($maxDayWattLoad != 0)){
                    $idMaxDayWattAdditional = 0;
                }


                if($maxNightWattLoad > $maxNightWattAdditional){
                    $idMaxNightWattAdditional = 0;
                }elseif($maxNightWattLoad < $maxNightWattAdditional){
                    $idMaxNightWattLoad = 0;
                }elseif(($maxNightWattLoad == $maxNightWattAdditional)&($maxNightWattLoad != 0)){
                    $idMaxNightWattAdditional = 0;
                }

                
                if($cart->type_of_system == 'household'){
                if(!empty($cart->loads)){
                foreach($cart->loads as $load){
                    $info = DB::table('carts_loads_pivot')->where('cart_id',$cart->id)->where('load_id',$load->id)->select('run_at_night','operating_voltage')->first();

                    if($info->operating_voltage == 1){
                        if($load->id == $idMaxDayWattLoad ){
                        $total_day_capacity = ($total_day_capacity + ($load->watt * 3));
                        }else{
                            $total_day_capacity = ($total_day_capacity + $load->watt);
                        }
                        if($info->run_at_night == 1){
                            if($load->id == $idMaxNightWattLoad){
                            $total_night_capacity = ($total_night_capacity + ($load->watt * 3));
                            }else{
                                $total_night_capacity = ($total_night_capacity + $load->watt);
                            }
                        }
    
                    }elseif($info->operating_voltage == 0){
                        $total_day_capacity = ($total_day_capacity + $load->watt);
                        if($info->run_at_night == 1){
                            $total_night_capacity = ($total_night_capacity + $load->watt);
                        }
    
                    }
                }
            }
        }


        if(!empty($cart->additional_loads)){

            foreach($cart->additional_loads as $additional_load){

                if($additional_load->operating_voltage == 1){
                    if($additional_load->id == $idMaxDayWattAdditional){
                    $total_day_capacity = ($total_day_capacity + ($additional_load->watt * 3));
                    }else{
                        $total_day_capacity = ($total_day_capacity + $additional_load->watt);
                    }
                    if($additional_load->run_at_night == 1){
                        if($additional_load->id == $idMaxNightWattAdditional){
                        $total_night_capacity = ($total_night_capacity + ($additional_load->watt * 3));
                        }else{
                            $total_night_capacity = ($total_night_capacity + $additional_load->watt);
                        }
                    }
                }elseif($additional_load->operating_voltage == 0){
                    $total_day_capacity = ($total_day_capacity + $additional_load->watt );
                    if($additional_load->run_at_night == 1){
                        $total_night_capacity = ($total_night_capacity + $additional_load->watt );
                    }
                }
            }
           }

            }        
            
            elseif($request->way == 'micro_consumerism'){
                $maxDayWattLoad = 0;  $maxDayWattAdditional = 0; 
                $idMaxDayWattLoad = 0; $idMaxDayWattAdditional = 0;
                $maxNightWattLoad = 0; $maxNightWattAdditional = 0;
                $idMaxNightWattLoad = 0; $idMaxNightWattAdditional = 0;

                
                if($cart->type_of_system == 'household'){


                    if(!empty($cart->loads)){
                        foreach($cart->loads as $load){
                            $info = DB::table('carts_loads_pivot')->where('cart_id',$cart->id)->where('load_id',$load->id)->select('run_at_night','operating_voltage')->first();
                            if($info->operating_voltage == 1){
                                if($load->watt > $maxDayWattLoad ){
                                    $maxDayWattLoad = $load->watt;
                                    $idMaxDayWattLoad = $load->id;
                                }
                                if($info->run_at_night == 1){
                                    if($load->watt > $maxNightWattLoad ){
                                        $maxNightWattLoad = $load->watt;
                                        $idMaxNightWattLoad = $load->id;
                                    }
                                }
                            }
                        }
                    }
                }

                if(!empty($cart->additional_loads)){

                    foreach($cart->additional_loads as $additional_load){
                        if($additional_load->operating_voltage == 1){
                            if($additional_load->watt > $maxDayWattAdditional ){
                                $maxDayWattAdditional = $additional_load->watt;
                                $idMaxDayWattAdditional = $additional_load->id;
                            }
                            if($additional_load->run_at_night == 1){
                                if($additional_load->watt > $maxNightWattAdditional ){
                                    $maxNightWattAdditional = $additional_load->watt;
                                    $idMaxNightWattAdditional = $additional_load->id;
                                }
                            }
                        }
                    }

                }

                if($maxDayWattLoad > $maxDayWattAdditional){
                    $data = DB::table('carts_loads_pivot')->where('cart_id',$id)->where('load_id',$idMaxDayWattLoad)->select('operating_voltage')->first();
                    if($data->operating_voltage){
                        $total_day_capacity = $maxDayWattLoad*3;  
                    }else{
                    $total_day_capacity = $maxDayWattLoad;
                }
                }elseif($maxDayWattLoad < $maxDayWattAdditional){
                    $data = AdditionalLoad::where('id',$idMaxDayWattAdditional)->select('operating_voltage')->first();
                    if($data->operating_voltage){
                        $total_day_capacity = $maxDayWattAdditional*3;
                    }else{
                    $total_day_capacity = $maxDayWattAdditional;
                }
                }elseif(($maxDayWattLoad == $maxDayWattAdditional)&($maxDayWattLoad != 0)){
                    $data = DB::table('carts_loads_pivot')->where('cart_id',$id)->where('load_id',$idMaxDayWattLoad)->select('run_at_night','operating_voltage')->first();
                    if($data->operating_voltage){
                        $total_day_capacity = $maxDayWattLoad*3;  
                    }else{
                    $total_day_capacity = $maxDayWattLoad;
                }
                }


                if($maxNightWattLoad > $maxNightWattAdditional){
                    $data = DB::table('carts_loads_pivot')->where('cart_id',$id)->where('load_id',$idMaxNightWattLoad)->select('operating_voltage')->first();
                    if($data->operating_voltage){
                        $total_night_capacity = $maxNightWattLoad*3;  
                    }else{
                    $total_night_capacity = $maxNightWattLoad;
                }
                }elseif($maxNightWattLoad < $maxNightWattAdditional){
                    $data = AdditionalLoad::where('id',$idMaxNightWattAdditional)->select('operating_voltage')->first();
                    if($data->operating_voltage){
                        $total_night_capacity = $maxNightWattAdditional*3;
                    }else{
                    $total_night_capacity = $maxNightWattAdditional;
                }
                }elseif(($maxNightWattLoad == $maxNightWattAdditional)&($maxNightWattLoad != 0)){
                    $data = DB::table('carts_loads_pivot')->where('cart_id',$id)->where('load_id',$idMaxNightWattLoad)->select('run_at_night','operating_voltage')->first();
                    if($data->operating_voltage){
                        $total_night_capacity = $maxNightWattLoad*3;  
                    }else{
                    $total_night_capacity = $maxNightWattLoad;
                }
                }

            }

            if(($cart->type_of_system == 'agricultural')||($cart->type_of_system == 'industrial')){

                $total_day_capacity = $total_day_capacity*1.4;
                
            }

            if($cart->type_of_system == 'household'){
                $total_night_capacity = $total_night_capacity * $cart->number_of_operating_hours_at_night;
            }


            $cart['run_way'] = $request->way;
            $cart['total_day_capacity'] = $total_day_capacity;
            $cart['total_night_capacity'] = $total_night_capacity;
            $cart->save();

            return response()->json([
                'success'=>'your request was processed successfully',
                'type_of_system' =>  $cart->type_of_system,
                'cart_id' => $cart->id,                 
            ],200);




    } 

//-----------------------------------------------------------------------------------------------------------------------------------------------
    //get the appropriate inverters 
    public function get_appropriate_inverters($id){
        $cart = Cart::where('id',$id)->with('inverters')->first();

        $choose_inverters = array();

        if(!empty($cart->inverters)){
            foreach($cart->inverters as $inverter){
                $pivot = DB::table('carts_inverters_pivot')->where('inverter_id',$inverter->id)->first();
                $help_array = array($inverter->id => $pivot->quantity);
                array_push($choose_inverters,$help_array);
            }
        }
 
        if($cart->type_of_system == 'household'){

        if($cart->total_day_capacity  < 1200){

         $inverters = Inverter::where('watt',1200)->where('type','household')->where('quantity_available','>',0)->get();
            
         Cart::where('id',$cart->id)->update(['voltage_system'=>'12']);
         if($inverters->isNotEmpty()){
         return response()->json([
            'message' => 'the total capacity for your cart is , and the appropriate inverters is',
            'total day capacity' => $cart->total_day_capacity ,
            'total price' => $cart->total_price,
            'inverters' => $inverters, 
            'choose_inverters' => $choose_inverters,
            'cart id' => $id,
            'type_of_system'=>$cart->type_of_system,
         ],200);
        }else{
            //send a notification to admins to alert them that there are a quantity of this inverters is zero
            $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
            if(!$recentNotification){
            $admins = User::where('role','admin')->get();
            Notification::send($admins,new NoQuantityAvailableOfInverters(1200));
        }
            return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],400);
        }

        }elseif($cart->total_day_capacity  < 2500){
 
                $inverters = Inverter::where('watt',2500)->where('type','household')->where('quantity_available','>',0)->get();
                   
            Cart::where('id',$cart->id)->update(['voltage_system'=>'24']);
            if($inverters->isNotEmpty()){
            return response()->json([
               'message' => 'the total capacity for your cart is , and the appropriate inverters is',
               'total day capacity' => $cart->total_day_capacity,
               'inverters' => $inverters, 
               'choose_inverters' => $choose_inverters,
               'cart id' => $id,
               'type_of_system'=>$cart->type_of_system,
            ],200);
           }else{
               //send a notification to admins to alert them that there are a quantity of this inverters is zero
               $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
               if(!$recentNotification){
               $admins = User::where('role','admin')->get();
               Notification::send($admins,new NoQuantityAvailableOfInverters(2500));
               }
               return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],400);
           }

        }elseif($cart->total_day_capacity  < 3000){

                $inverters = Inverter::where('watt',3000)->where('type','household')->where('quantity_available','>',0)->get();
                   
            Cart::where('id',$cart->id)->update(['voltage_system'=>'24']);
            if($inverters->isNotEmpty()){
            return response()->json([
               'message' => 'the total capacity for your cart is , and the appropriate inverters is',
               'total day capacity' => $cart->total_day_capacity ,
               'inverters' => $inverters, 
               'choose_inverters' => $choose_inverters,
               'cart id' => $id,
               'type_of_system'=>$cart->type_of_system,
            ],200);
           }else{
               //send a notification to admins to alert them that there are a quantity of this inverters is zero
               $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
               if(!$recentNotification){
               $admins = User::where('role','admin')->get();
               Notification::send($admins,new NoQuantityAvailableOfInverters(3000));
               }
               return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],400);
           }
         
        }elseif($cart->total_day_capacity  < 3500){

                $inverters = Inverter::where('watt',3500)->where('type','household')->where('quantity_available','>',0)->get();
                  
            Cart::where('id',$cart->id)->update(['voltage_system'=>'24']);
         if($inverters->isNotEmpty()){
         return response()->json([
            'message' => 'the total capacity for your cart is , and the appropriate inverters is',
            'total day capacity' => $cart->total_day_capacity ,
            'inverters' => $inverters, 
            'choose_inverters' => $choose_inverters,
            'cart id' => $id,
            'type_of_system'=>$cart->type_of_system,
         ],200);
        }else{
            //send a notification to admins to alert them that there are a quantity of this inverters is zero
            $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
            if(!$recentNotification){
            $admins = User::where('role','admin')->get();
            Notification::send($admins,new NoQuantityAvailableOfInverters(3500));
            }
            return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],400);
        }
            
        }elseif($cart->total_day_capacity  < 4000){

                $inverters = Inverter::where('watt',4000)->where('type','household')->where('quantity_available','>',0)->get();
                  
            Cart::where('id',$cart->id)->update(['voltage_system'=>'24']);
            if($inverters->isNotEmpty()){
            return response()->json([
               'message' => 'the total capacity for your cart is , and the appropriate inverters is',
               'total day capacity' => $cart->total_day_capacity ,
               'inverters' => $inverters, 
               'choose_inverters' => $choose_inverters,
               'cart id' => $id,
               'type_of_system'=>$cart->type_of_system,
            ],200);
           }else{
               //send a notification to admins to alert them that there are a quantity of this inverters is zero
               $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
               if(!$recentNotification){
               $admins = User::where('role','admin')->get();
               Notification::send($admins,new NoQuantityAvailableOfInverters(4000));
               }
               return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],400);
           }
            
        }elseif($cart->total_day_capacity  < 5000){

                $inverters = Inverter::where('watt',5000)->where('type','household')->where('quantity_available','>',0)->get();
                   
            Cart::where('id',$cart->id)->update(['voltage_system'=>'48',]);
         if($inverters->isNotEmpty()){
         return response()->json([
            'message' => 'the total capacity for your cart is , and the appropriate inverters is',
            'total day capacity' => $cart->total_day_capacity,
            'inverters' => $inverters, 
            'choose_inverters' => $choose_inverters,
            'cart id' => $id,
            'type_of_system'=>$cart->type_of_system,
         ],200);
        }else{
            //send a notification to admins to alert them that there are a quantity of this inverters is zero
            $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
            if(!$recentNotification){
            $admins = User::where('role','admin')->get();
            Notification::send($admins,new NoQuantityAvailableOfInverters(5000));
            }
            return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],400);
        }
            
        }elseif($cart->total_day_capacity  < 6000){

                $inverters = Inverter::where('watt',6000)->where('type','household')->where('quantity_available','>',0)->get();
                   
            Cart::where('id',$cart->id)->update(['voltage_system'=>'48']);
         if($inverters->isNotEmpty()){
         return response()->json([
            'message' => 'the total capacity for your cart is , and the appropriate inverters is',
            'total day capacity' => $cart->total_day_capacity ,
            'inverters' => $inverters, 
            'choose_inverters' => $choose_inverters,
            'cart id' => $id,
            'type_of_system'=>$cart->type_of_system,
         ],200);
        }else{
            //send a notification to admins to alert them that there are a quantity of this inverters is zero
            $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
            if(!$recentNotification){
            $admins = User::where('role','admin')->get();
            Notification::send($admins,new NoQuantityAvailableOfInverters(6000));
            }
            return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],400);
        }
            
        }elseif($cart->total_day_capacity  < 11000){

                $inverters = Inverter::where('watt',11000)->where('type','household')->where('quantity_available','>',0)->get();
                   
            Cart::where('id',$cart->id)->update(['voltage_system'=>'48']);
         if($inverters->isNotEmpty()){
         return response()->json([
            'message' => 'the total capacity for your cart is , and the appropriate inverters is',
            'total day capacity' => $cart->total_day_capacity ,
            'inverters' => $inverters, 
            'choose_inverters' => $choose_inverters,
            'cart id' => $id,
            'type_of_system'=>$cart->type_of_system,
         ],200);
        }else{
            //send a notification to admins to alert them that there are a quantity of this inverters is zero
            $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
            if(!$recentNotification){
            $admins = User::where('role','admin')->get();
            Notification::send($admins,new NoQuantityAvailableOfInverters(11000));
            }
            return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],401);
        }
            
        }elseif($cart->total_day_capacity  < 15000){

                $inverters = Inverter::where('watt','<',15000)->where('type','household')->where('quantity_available','>',0)->get();
                   
            Cart::where('id',$cart->id)->update(['voltage_system'=>'48']);
         if($inverters->isNotEmpty()){
         return response()->json([
            'message' => 'the total capacity for your cart is , and the appropriate inverters is',
            'total day capacity' => $cart->total_day_capacity ,
            'inverters' => $inverters, 
            'choose_inverters' => $choose_inverters,
            'cart id' => $id,
            'type_of_system'=>$cart->type_of_system,
         ],200);
        }else{
            //send a notification to admins to alert them that there are a quantity of this inverters is zero
            $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
            if(!$recentNotification){
            $admins = User::where('role','admin')->get();
            Notification::send($admins,new NoQuantityAvailableOfInverters(15000));
            }
            return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],401);
        }


         }else{
             return response()->json(['message'=>'there is no inverters appropriate for your cart now, sorry sir, please review your loads',],400);
         }




    }elseif($cart->type_of_system == 'agricultural'){

        if($cart->total_day_capacity  < 750){

            $inverters = Inverter::where('watt',750)->where('type','agricultural')->where('quantity_available','>',0)->get();
               
            Cart::where('id',$cart->id)->update(['voltage_system'=>'12']);
            if($inverters->isNotEmpty()){
            return response()->json([
               'message' => 'the total capacity for your cart is , and the appropriate inverters is',
               'total day capacity' => $cart->total_day_capacity ,
               'total price' => $cart->total_price,
               'inverters' => $inverters, 
               'choose_inverters' => $choose_inverters,
               'cart id' => $id,
               'type_of_system'=>$cart->type_of_system,
            ],200);
           }else{
               //send a notification to admins to alert them that there are a quantity of this inverters is zero
               $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
               if(!$recentNotification){
               $admins = User::where('role','admin')->get();
               Notification::send($admins,new NoQuantityAvailableOfInverters(750));
               }
               return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],400);
           }
   
           }elseif($cart->total_day_capacity  < 1500){
 
            $inverters = Inverter::where('watt',1500)->where('type','agricultural')->where('quantity_available','>',0)->get();
               
        Cart::where('id',$cart->id)->update(['voltage_system'=>'24']);
        if($inverters->isNotEmpty()){
        return response()->json([
           'message' => 'the total capacity for your cart is , and the appropriate inverters is',
           'total day capacity' => $cart->total_day_capacity,
           'inverters' => $inverters, 
           'choose_inverters' => $choose_inverters,
           'cart id' => $id,
           'type_of_system'=>$cart->type_of_system,
        ],200);
       }else{
           //send a notification to admins to alert them that there are a quantity of this inverters is zero
           $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
           if(!$recentNotification){
           $admins = User::where('role','admin')->get();
           Notification::send($admins,new NoQuantityAvailableOfInverters(1500));
           }
           return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],400);
       }

    }elseif($cart->total_day_capacity  < 2200){
 
        $inverters = Inverter::where('watt',2200)->where('type','agricultural')->where('quantity_available','>',0)->get();
           
    Cart::where('id',$cart->id)->update(['voltage_system'=>'24']);
    if($inverters->isNotEmpty()){
    return response()->json([
       'message' => 'the total capacity for your cart is , and the appropriate inverters is',
       'total day capacity' => $cart->total_day_capacity,
       'inverters' => $inverters, 
       'choose_inverters' => $choose_inverters,
       'cart id' => $id,
       'type_of_system'=>$cart->type_of_system,
    ],200);
   }else{
       //send a notification to admins to alert them that there are a quantity of this inverters is zero
       $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
       if(!$recentNotification){
       $admins = User::where('role','admin')->get();
       Notification::send($admins,new NoQuantityAvailableOfInverters(2200));
       }
       return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],400);
   }

         }elseif($cart->total_day_capacity  < 4000){
 
             $inverters = Inverter::where('watt',4000)->where('type','agricultural')->where('quantity_available','>',0)->get();
       
         Cart::where('id',$cart->id)->update(['voltage_system'=>'24']);
         if($inverters->isNotEmpty()){
         return response()->json([
            'message' => 'the total capacity for your cart is , and the appropriate inverters is',
            'total day capacity' => $cart->total_day_capacity,
            'inverters' => $inverters, 
            'choose_inverters' => $choose_inverters,
            'cart id' => $id,
            'type_of_system'=>$cart->type_of_system,
         ],200);
         }else{
            //send a notification to admins to alert them that there are a quantity of this inverters is zero
            $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
            if(!$recentNotification){
            $admins = User::where('role','admin')->get();
            Notification::send($admins,new NoQuantityAvailableOfInverters(4000));
            }
            return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],400);
         }

         }elseif($cart->total_day_capacity  < 5500){
 
             $inverters = Inverter::where('watt',5500)->where('type','agricultural')->where('quantity_available','>',0)->get();
       
         Cart::where('id',$cart->id)->update(['voltage_system'=>'24']);
         if($inverters->isNotEmpty()){
         return response()->json([
            'message' => 'the total capacity for your cart is , and the appropriate inverters is',
            'total day capacity' => $cart->total_day_capacity,
            'inverters' => $inverters, 
            'choose_inverters' => $choose_inverters,
            'cart id' => $id,
            'type_of_system'=>$cart->type_of_system,
         ],200);
         }else{
            //send a notification to admins to alert them that there are a quantity of this inverters is zero
            $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
            if(!$recentNotification){
            $admins = User::where('role','admin')->get();
            Notification::send($admins,new NoQuantityAvailableOfInverters(5500));
            }
            return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],400);
         }

         }elseif($cart->total_day_capacity  < 7500){
 
             $inverters = Inverter::where('watt',7500)->where('type','agricultural')->where('quantity_available','>',0)->get();
       
         Cart::where('id',$cart->id)->update(['voltage_system'=>'24']);
         if($inverters->isNotEmpty()){
         return response()->json([
            'message' => 'the total capacity for your cart is , and the appropriate inverters is',
            'total day capacity' => $cart->total_day_capacity,
            'inverters' => $inverters, 
            'choose_inverters' => $choose_inverters,
            'cart id' => $id,
            'type_of_system'=>$cart->type_of_system,
         ],200);
         }else{
            //send a notification to admins to alert them that there are a quantity of this inverters is zero
            $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
            if(!$recentNotification){
            $admins = User::where('role','admin')->get();
            Notification::send($admins,new NoQuantityAvailableOfInverters(7500));
            }
            return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],400);
         }

         }elseif($cart->total_day_capacity  < 22000){
 
             $inverters = Inverter::where('watt',22000)->where('type','agricultural')->where('quantity_available','>',0)->get();
       
         Cart::where('id',$cart->id)->update(['voltage_system'=>'24']);
         if($inverters->isNotEmpty()){
         return response()->json([
            'message' => 'the total capacity for your cart is , and the appropriate inverters is',
            'total day capacity' => $cart->total_day_capacity,
            'inverters' => $inverters, 
            'choose_inverters' => $choose_inverters,
            'cart id' => $id,
            'type_of_system'=>$cart->type_of_system,
         ],200);
         }else{
            //send a notification to admins to alert them that there are a quantity of this inverters is zero
            $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
            if(!$recentNotification){
            $admins = User::where('role','admin')->get();
            Notification::send($admins,new NoQuantityAvailableOfInverters(22000));
            }
            return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],400);
         }

         }elseif($cart->total_day_capacity  < 37000){
 
             $inverters = Inverter::where('watt',37000)->where('type','agricultural')->where('quantity_available','>',0)->get();
       
         Cart::where('id',$cart->id)->update(['voltage_system'=>'24']);
         if($inverters->isNotEmpty()){
         return response()->json([
            'message' => 'the total capacity for your cart is , and the appropriate inverters is',
            'total day capacity' => $cart->total_day_capacity,
            'inverters' => $inverters, 
            'choose_inverters' => $choose_inverters,
            'cart id' => $id,
            'type_of_system'=>$cart->type_of_system,
         ],200);
         }else{
            //send a notification to admins to alert them that there are a quantity of this inverters is zero
            $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
            if(!$recentNotification){
            $admins = User::where('role','admin')->get();
            Notification::send($admins,new NoQuantityAvailableOfInverters(37000));
            }
            return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],400);
         }

         }else{
                 return response()->json(['message'=>'there is no inverters appropriate for your cart now, sorry sir',],400);
              }


             }elseif($cart->type_of_system == 'industrial'){


                if($cart->total_day_capacity  < 60000){

                    $inverters = Inverter::where('watt',60000)->where('type','industrial')->where('quantity_available','>',0)->get();
                       
                    Cart::where('id',$cart->id)->update(['voltage_system'=>'12']);
                    if($inverters->isNotEmpty()){
                    return response()->json([
                       'message' => 'the total capacity for your cart is , and the appropriate inverters is',
                       'total day capacity' => $cart->total_day_capacity ,
                       'total price' => $cart->total_price,
                       'inverters' => $inverters, 
                       'choose_inverters' => $choose_inverters,
                       'cart id' => $id,
                       'type_of_system'=>$cart->type_of_system,
                    ],200);
                   }else{
                       //send a notification to admins to alert them that there are a quantity of this inverters is zero
                       $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
                       if(!$recentNotification){
                       $admins = User::where('role','admin')->get();
                       Notification::send($admins,new NoQuantityAvailableOfInverters(60000));
                       }
                       return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],400);
                   }
           
                   }elseif($cart->total_day_capacity  < 125000){
         
                    $inverters = Inverter::where('watt',125000)->where('type','industrial')->where('quantity_available','>',0)->get();
                       
                Cart::where('id',$cart->id)->update(['voltage_system'=>'24']);
                if($inverters->isNotEmpty()){
                return response()->json([
                   'message' => 'the total capacity for your cart is , and the appropriate inverters is',
                   'total day capacity' => $cart->total_day_capacity,
                   'inverters' => $inverters, 
                   'choose_inverters' => $choose_inverters,
                   'cart id' => $id,
                   'type_of_system'=>$cart->type_of_system,
                ],200);
               }else{
                   //send a notification to admins to alert them that there are a quantity of this inverters is zero
                   $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
                   if(!$recentNotification){
                   $admins = User::where('role','admin')->get();
                   Notification::send($admins,new NoQuantityAvailableOfInverters(125000));
                   }
                   return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],400);
               }
        
            }elseif($cart->total_day_capacity  < 137000){
         
                $inverters = Inverter::where('watt',137000)->where('type','industrial')->where('quantity_available','>',0)->get();
                   
            Cart::where('id',$cart->id)->update(['voltage_system'=>'24']);
            if($inverters->isNotEmpty()){
            return response()->json([
               'message' => 'the total capacity for your cart is , and the appropriate inverters is',
               'total day capacity' => $cart->total_day_capacity,
               'inverters' => $inverters, 
               'choose_inverters' => $choose_inverters,
               'cart id' => $id,
               'type_of_system'=>$cart->type_of_system,
            ],200);
           }else{
               //send a notification to admins to alert them that there are a quantity of this inverters is zero
               $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
               if(!$recentNotification){
               $admins = User::where('role','admin')->get();
               Notification::send($admins,new NoQuantityAvailableOfInverters(137000));
               }
               return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],400);
           }
    
            }elseif($cart->total_day_capacity  < 300000){
         
            $inverters = Inverter::where('watt',300000)->where('type','industrial')->where('quantity_available','>',0)->get();
               
               Cart::where('id',$cart->id)->update(['voltage_system'=>'24']);
               if($inverters->isNotEmpty()){
               return response()->json([
                  'message' => 'the total capacity for your cart is , and the appropriate inverters is',
                  'total day capacity' => $cart->total_day_capacity,
                  'inverters' => $inverters, 
                  'choose_inverters' => $choose_inverters,
                  'cart id' => $id,
                  'type_of_system'=>$cart->type_of_system,
               ],200);
              }else{
                  //send a notification to admins to alert them that there are a quantity of this inverters is zero
                  $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
                  if(!$recentNotification){
                  $admins = User::where('role','admin')->get();
                  Notification::send($admins,new NoQuantityAvailableOfInverters(300000));
                  }
                  return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],400);
              }

               }elseif($cart->total_day_capacity  < 600000){
         
                   $inverters = Inverter::where('watt',600000)->where('type','industrial')->where('quantity_available','>',0)->get();
           
               Cart::where('id',$cart->id)->update(['voltage_system'=>'24']);
               if($inverters->isNotEmpty()){
               return response()->json([
                  'message' => 'the total capacity for your cart is , and the appropriate inverters is',
                  'total day capacity' => $cart->total_day_capacity,
                  'inverters' => $inverters, 
                  'choose_inverters' => $choose_inverters,
                  'cart id' => $id,
                  'type_of_system'=>$cart->type_of_system,
               ],200);
              }else{
                  //send a notification to admins to alert them that there are a quantity of this inverters is zero
                  $recentNotification = DB::table('notifications')->where('created_at', '>=', now()->subSeconds(3))->exists();
                  if(!$recentNotification){
                  $admins = User::where('role','admin')->get();
                  Notification::send($admins,new NoQuantityAvailableOfInverters(600000));
                  }
                  return response()->json(['message'=>'sorry sir, but there is not appropriate inverters for your cart, please try agin later '],400);
              }

            }else{
                 return response()->json(['message'=>'there is no inverters appropriate for your cart now, sorry sir',],400);
                 }



             }


    }


//-----------------------------------------------------------------------------------------------------------------------------------------------
    //get information about a specific inverter
    public function get_specific_inverter($id){
        $inverter = Inverter::where('id',$id)->first();
        return response()->json($inverter,200);
    }

//-----------------------------------------------------------------------------------------------------------------------------------------------
    
    //this function to insert the choose inverters by users
    public function add_inverters_to_cart(Request $request,$id){
        $i = 0;
        $cart = Cart::where('id',$id)->with('inverters')->first();
        if($cart->inverters->isNotEmpty()){
            $cart->inverters()->detach();
        }

        $inverters = json_decode($request->inverters);
        foreach($inverters as $item){
            foreach($item as $key=>$value){
            $cart->inverters()->attach(intval($key));
            DB::table('carts_inverters_pivot')->where('inverter_id',intval($key))->update(['quantity'=>$value,]);
            $i = $i + $value;
            }
        }

        $cart_up = Cart::where('id',$id)->update(['number_of_inverters'=>$i,]);

        if(!empty($cart_up)){
            return response()->json([
                'success'=>'your inverters has been added to cart successfully',
                'cart id' => $id,
                'type_of_system'=>$cart->type_of_system,                
            ],200);

        }else{
            return response()->json(['error'=>'there is an error, please try again'],401);
        }
        

    } 

     
//-----------------------------------------------------------------------------------------------------------------------------------------------

    //this method to get the appropriate batteries for user to choose from it and if he come to update  his request, this method give him the element that he choose its

                                    /*-----------this function need to difficult work in front end please do not forget!-----------*/
                                    /*-----------this function need to difficult work in front end please do not forget!-----------*/
                                    /*-----------this function need to difficult work in front end please do not forget!-----------*/
    public function get_appropriate_batteries($id){
        
        $cart = Cart::where('id',$id)->with('batteries')->first();

        $choose_batteries = array();

        if(!empty($cart->batteries)){
            foreach($cart->batteries as $battery){
                $pivot = DB::table('carts_batteries_pivot')->where('battery_id',$battery->id)->first();
                $help_array = array($battery->id => $pivot->quantity);
                array_push($choose_batteries,$help_array);
            }
        }

        $lithium_batteries = Battery::where('type','lithium')->where('quantity_available','>',0)->get();
        $gel_batteries = Battery::where('type','gel')->where('quantity_available','>',0)->get();
        $tubular_batteries = Battery::where('type','tubular')->where('quantity_available','>',0)->get();

        $total_amber = $cart->number_of_operating_hours_at_night*($cart->total_night_capacity/$cart->voltage_system);

        return response()->json([
            'lithium batteries' => $lithium_batteries,
            'gel batteries' => $gel_batteries, 
            'tubular batteries' => $tubular_batteries,
            'voltage system' => $cart->voltage_system,
            'total night capacity' => $cart->total_night_capacity,
            'total_amber' => $total_amber,
           // 'total price' => $cart->total_price,
            'choose batteries' => $choose_batteries,
            'cart id' => $id,
            'type_of_system'=>$cart->type_of_system
         ],200);

                            /*-----------this function need to difficult work in front end please do not forget-----------*/
    }


//-----------------------------------------------------------------------------------------------------------------------------------------------

    //this function to get information about a specific battery to user
    public function get_specific_battery($id){
        $battery = Battery::where('id',$id)->first();
        return response()->json($battery,200);
    }

//-----------------------------------------------------------------------------------------------------------------------------------------------
    //this function to insert the choose batteries by users
    public function add_batteries_to_cart(Request $request,$id){
        $i = 0;
        $cart = Cart::where('id',$id)->first();
        if($cart->batteries->isNotEmpty()){
            $cart->batteries()->detach();
        }

        $batteries = json_decode($request->batteries);
        foreach($batteries as $item){
            foreach($item as $key=>$value){
            $cart->batteries()->attach(intval($key));
            DB::table('carts_batteries_pivot')->where('battery_id',intval($key))->update(['quantity'=>$value,]);
            $i = $i + $value;
            }
        }

        $cart_up = Cart::where('id',$id)->update(['number_of_batteries'=>$i,]);

        if(!empty($cart_up)){
            return response()->json([
                'success'=>'your batteries has been added to cart successfully',
                'cart id' => $id,
                'type_of_system'=>$cart->type_of_system,                
            ],200);

        }else{
            return response()->json(['error'=>'there is an error, please try again'],401);
        }
        

    } 


//-------------------------------------------------------------------------------------------------------------------------------------------------

//this method to get the panels for user to choose from it and if he come to update  his request, this method give him the element that he choose its

    public function get_appropriate_panels($id){
            
        $cart = Cart::where('id',$id)->with('panels')->first();

        $choose_panels = array();

        if(!empty($cart->panels)){
            foreach($cart->panels as $panel){
                $pivot = DB::table('carts_panels_pivot')->where('panel_id',$panel->id)->first();
                $help_array = array($panel->id => $pivot->quantity);
                array_push($choose_panels,$help_array);
            }
        }

        $panels = Panel::where('quantity_available','>',0)->get();
        $base_of_panel = SupplementPrice::select('base_panel_cost')->first();
        
        return response()->json([
            'panels' => $panels,
            'price of base panel' => $base_of_panel->base_panel_cost,
            'voltage system' => $cart->voltage_system,
            'total day capacity' => $cart->total_day_capacity,
           // 'total price' => $cart->total_price,
            'choose panels' => $choose_panels,
            'cart id' => $id,
            'type_of_system'=>$cart->type_of_system,
        ],200);

                        
    }

//------------------------------------------------------------------------------------------------------------------------    

    //get specific panel
    public function get_specific_panel($id){
        $panel = Panel::where('id',$id)->first();
        return response()->json($panel,200);
    }

//-------------------------------------------------------------------------------------------------------------------------
    //this function to insert the choose panels by users
    public function add_panels_to_cart(Request $request,$id){
        $i = 0;
        $cart = Cart::where('id',$id)->with('panels')->first();
        if($cart->panels->isNotEmpty()){
           $cart->panels()->detach();
        }

        $panels = json_decode($request->panels);
        foreach($panels as $item){
            foreach($item as $key=>$value){
            $cart->panels()->attach(intval($key));
            DB::table('carts_panels_pivot')->where('panel_id',intval($key))->update(['quantity'=>$value,]);
            $i = $i + $value;
            }
        }

        $cart_up = Cart::where('id',$id)->update(['number_of_panels'=>$i,]);

        if(!empty($cart_up)){
            return response()->json([
                'success'=>'your panels has been added to cart successfully',
                'cart id' => $id,
                'type_of_system'=>$cart->type_of_system,                
            ],200);

        }else{
            return response()->json(['error'=>'there is an error, please try again'],401);
        }
        

    } 

//---------------------------------------------------------------------------------------------------------------------------

    //this function to insert the choose cables by users
    public function add_distance_for_cables_to_cart(Request $request,$id){
        
        $distance = intval($request->distance);

        $cart = Cart::where('id',$id)->first();
       // $price_of_one_metr_of_cable = SupplementPrice::select('one_metr_of_cables')->first();
        $cart['distance_from_panels_to_inverter'] = $distance;
       // $cart['total_price'] = $cart->total_price + ($distance*$price_of_one_metr_of_cable->one_metr_of_cables);
        $cart->save();
        


            return response()->json([
                'success'=>'your distance for cables has been added to cart successfully',
                'cart id' => $id,
                'type_of_system'=>$cart->type_of_system,                
            ],200);

        

    } 

//------------------------------------------------------------------------------------------------------------------------------

    //this function get the information's cart to user to confirm
    public function get_cart_info_to_confirm($id){
        $cart = $this->get_cart_info($id);

        if(!empty($cart)){
        return response()->json(['cart'=>$cart],200);
        }else{
            return response()->json(['error'=>'there is an error, please try it again or refresh the page'],401);
        }
    }

//------------------------------------------------------------------------------------------------------------------------------

    //this function to confirm the input data that associated with specific cart
    public function confirm_the_cart($id){

        $cart = $this->get_cart_info($id);


            foreach($cart->inverters as $item){
                $inverter = Inverter::where('id',$item->id)->first();
                if(($inverter['quantity_available'] - $item->pivot->quantity) > 0){
                $inverter['quantity_available'] = $inverter['quantity_available'] - $item->pivot->quantity;
                $inverter->save();
                }else{
                    return response()->json(['error' => 'there is not enough amount of this inverters in the store, please change your choice or wait until the admins add this type of inverters',
                                             'id' => $item->id,
                                             'message' => 'go to choice inverter page',
                                                                ],400);
                }
                
            }


            foreach($cart->batteries as $item){
                $battery = Battery::where('id',$item->id)->first();
                if(($battery['quantity_available'] - $item->pivot->quantity) >= 0){
                $battery['quantity_available'] = $battery['quantity_available'] - $item->pivot->quantity;
                $battery->save();
                }else{
                    return response()->json(['error' => 'there is not enough amount of this batteries in the store, please change your choice or wait until the admins add this type of batteries',
                                             'id' => $item->id,
                                             'message' => 'go to choice battery page',
                                                                ],400);
                }
                
            }


            foreach($cart->panels as $item){
                $panel = Panel::where('id',$item->id)->first();
                if(($panel['quantity_available'] - $item->pivot->quantity) >= 0){
                $panel['quantity_available'] = $panel['quantity_available'] - $item->pivot->quantity;
                $panel->save();
                }else{
                    return response()->json(['error' => 'there is not enough amount of this panels in the store, please change your choice or wait until the admins add this type of panels',
                                             'id' => $item->id,
                                             'message' => 'go to choice panel page',
                                                                ],400);
                }
                
            }


           

            Cart::where('id',$cart->id)->update(['total_price'=>$cart->total_price,'status'=>'processing',]);

            $admins = User::where('role','admin')->get();
            $user = auth()->user();
            Notification::send($admins,new CartCompleteNotification($cart->id,$user));
            Notification::send($user,new YourCartIsProcessingNotification($cart->id));

            return response()->json(['success' => 'the cart has been confirmed successfully'],200);



    }

//--------------------------------------------------------------------------------------------------------------------------------------------------


}

