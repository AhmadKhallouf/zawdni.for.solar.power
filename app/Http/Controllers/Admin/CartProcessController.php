<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdditionalLoad;
use App\Models\Archive;
use App\Models\Battery;
use App\Models\Cart;
use App\Models\Inverter;
use App\Models\Panel;
use App\Models\SupplementPrice;
use App\Models\User;
use App\Notifications\AcceptCartNotification;
use App\Notifications\RefuseCartNotification;
use App\Notifications\ReturnCartToProcessNotification;
use Illuminate\Http\Request;
use App\Traits\GetCartInfo;
use Illuminate\Support\Facades\Notification as FacadesNotification;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNotEmpty;

class CartProcessController extends Controller
{
    use GetCartInfo;

     //get all carts from database
     public function get_carts(){
        $carts = Cart::with('users:id,first_name,last_name')->get();
        return response()->json($carts,200);
     }


    //get a specific cart from database
    public function get_specific_cart($id){
        $cart = $this->get_cart_info_to_admin($id);
        return response()->json($cart,200);
    }


    


    //accept cart by admin
    public function accept_cart($id){
        Cart::where('id',$id)->update(['status' => 'accepted']);
        $cart = $this->get_cart_info_to_admin($id);
        $user = User::where('id',$cart->users->id)->first();
        $supplement_price = SupplementPrice::first();
        FacadesNotification::send($user,new AcceptCartNotification($cart,$user->first_name,$supplement_price));
        return response()->json(['success' => 'the cart had been accepted successfully'],200);
    }


     //refuse cart by admin
     public function refuse_cart($id){

        $cart = $this->get_cart_info_to_admin($id);


        foreach($cart->inverters as $item){
            $inverter = Inverter::where('id',$item->id)->first();
            $inverter['quantity_available'] = $inverter['quantity_available'] + $item->pivot->quantity;
            $inverter->save();
           
        }

        if($cart->batteries->isNotEmpty()){
        foreach($cart->batteries as $item){
            $battery = Battery::where('id',$item->id)->first();
            $battery['quantity_available'] = $battery['quantity_available'] + $item->pivot->quantity;
            $battery->save();
            
        }
    }


        foreach($cart->panels as $item){
            $panel = Panel::where('id',$item->id)->first();
            $panel['quantity_available'] = $panel['quantity_available'] + $item->pivot->quantity;
            $panel->save();
           
            
        }
        Cart::where('id',$id)->update(['status' => 'refused']);
        $cart = Cart::where('id',$id)->with('users')->first();
        $user = User::where('id',$cart->users->id)->first();
        FacadesNotification::send($user,new RefuseCartNotification($cart->id,$user->first_name));
        return response()->json(['success' => 'the cart had been refused successfully'],200);
    }


    //return the cart to processing status if there is any update from user
    public function back_to_processing($id){

        $cart = $this->get_cart_info_to_admin($id);


        foreach($cart->inverters as $item){
            $inverter = Inverter::where('id',$item->id)->first();
            $inverter['quantity_available'] = $inverter['quantity_available'] + $item->pivot->quantity;
            $inverter->save();
           
        }

        if($cart->batteries->isNotEmpty()){
        foreach($cart->batteries as $item){
            $battery = Battery::where('id',$item->id)->first();
            $battery['quantity_available'] = $battery['quantity_available'] + $item->pivot->quantity;
            $battery->save();
            
        }
    }


        foreach($cart->panels as $item){
            $panel = Panel::where('id',$item->id)->first();
            $panel['quantity_available'] = $panel['quantity_available'] + $item->pivot->quantity;
            $panel->save();
           
            
        }


       

        Cart::where('id',$cart->id)->update(['total_price'=> null , 'status'=>'processing']);

        $cart_a = Cart::where('id',$id)->with('users')->first();
        $user = User::where('id',$cart_a->users->id)->first();
        FacadesNotification::send($user,new ReturnCartToProcessNotification($cart_a->id,$user->first_name));
        return response()->json(['success' => 'the cart had been returned to processing status successfully'],200);
    }


    //deliver the cart
    public function deliver_cart($id){
        $cart = Cart::where('id',$id)->with('additional_loads')->first();
        $cart->loads()->detach();
        $cart->inverters()->detach();
        if($cart->batteries->isNotEmpty()){
        $cart->batteries()->detach();
        }
        $cart->panels()->detach();
        if($cart->additional_loads->isNotEmpty()){
        AdditionalLoad::where('cart_id',$id)->delete();
    }
        Cart::where('id',$id)->update(['status'=>'delivered']);
        return response()->json(['success'=>'the cart had been delivered successfully'],200);
    }


    //archive the cart info
    public function archive_cart($id){
        $cart = Cart::where('id',$id)->with('users')->first();
        Cart::where('id',$id)->update(['status'=>'archived']);
        Archive::create([
            'name_of_user' => $cart->users->first_name.' '.$cart->users->last_name,
            'type_of_system' => $cart->type_of_system,
            'voltage_system' => $cart->voltage_system,
            'number_of_inverters' => $cart->number_of_inverters,
            'number_of_batteries' => $cart->number_of_batteries,
            'number_of_panels' => $cart->number_of_panels,
            'distance_from_panels_to_inverter' => $cart->distance_from_panels_to_inverter,
            'number_of_operating_hours_at_night' => $cart->number_of_operating_hours_at_night,
            'total_day_capacity' => $cart->total_day_capacity,
            'total_night_capacity' => $cart->total_night_capacity,
            'run_way' => $cart->run_way,
            'total_price' => $cart->total_price,
        ]);

        return response()->json(['success' => 'the cart had been archived successfully'],200);
    }



    //update total price of cart
    public function update_total_price(Request $request,$id){
        Cart::where('id',$id)->update(['total_price' => $request->total_price]);
        return response()->json(['success'=>'the total price in this cart had been updated successfully'],200);
    }

    //delete a cart from database
    public function delete_cart($id){
        $cart = $this->get_cart_info_to_admin($id);

        if($cart->status == 'processing'){

            foreach($cart->inverters as $item){
                $inverter = Inverter::where('id',$item->id)->first();
                $inverter['quantity_available'] = $inverter['quantity_available'] + $item->pivot->quantity;
                $inverter->save();
               
            }

    
            if($cart->batteries->isNotEmpty()){
            foreach($cart->batteries as $item){
                $battery = Battery::where('id',$item->id)->first();
                $battery['quantity_available'] = $battery['quantity_available'] + $item->pivot->quantity;
                $battery->save();
                
            }
        }
    
    
            foreach($cart->panels as $item){
                $panel = Panel::where('id',$item->id)->first();
                $panel['quantity_available'] = $panel['quantity_available'] + $item->pivot->quantity;
                $panel->save();
               
                
            }

            
        }

        $success = Cart::where('id',$id)->delete();
        if($success == true){
            return response()->json(['success'=>'this cart is deleted successfully'],200);
        }else{
            return response()->json(['error'=>'!!!!!this cart does not delete, please check if this cart is deleted from database previously ' ],400);
        }
    }







}
