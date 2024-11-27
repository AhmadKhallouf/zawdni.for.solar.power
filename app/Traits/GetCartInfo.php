<?php

namespace App\Traits;

use App\Models\Cart;
use App\Models\SupplementPrice;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNotEmpty;

trait GetCartInfo{


    public function get_cart_info($id){

        $total_price = 0;
        $cart = Cart::where('id',$id)
        ->with('inverters')
        ->with('batteries')
        ->with('panels')
        ->with('loads')
        ->with('additional_loads')->first();

        if($cart->inverters->isNotEmpty()){
            foreach($cart->inverters as $inverter){
                $pivot = DB::table('carts_inverters_pivot')->where('cart_id',$id)->where('inverter_id',$inverter->id)->first();
                $inverter->pivot['quantity'] = $pivot->quantity;
                $total_price = $total_price + ($inverter->price*$pivot->quantity);
            }
        }

        if($cart->batteries->isNotEmpty()){
            foreach($cart->batteries as $battery){
                $pivot = DB::table('carts_batteries_pivot')->where('cart_id',$id)->where('battery_id',$battery->id)->first();
                $battery->pivot['quantity'] = $pivot->quantity;
                $total_price = $total_price + ($battery->price*$pivot->quantity);
            }
        }

        if($cart->panels->isNotEmpty()){
            foreach($cart->panels as $panel){
                $pivot = DB::table('carts_panels_pivot')->where('cart_id',$id)->where('panel_id',$panel->id)->first();
                $panel->pivot['quantity'] = $pivot->quantity;
                $total_price = $total_price + ($panel->price*$pivot->quantity);
            }
        } 

        $supplement_price = SupplementPrice::first();
        $distance = $cart->distance_from_panels_to_inverter;
        $installation_price;
        if($cart->type_of_system == 'household'){
            $installation_price = $supplement_price->household_installation_cost;
        }elseif($cart->type_of_system == 'agricultural'){
            $installation_price = $supplement_price->agriculture_installation_cost;
        }else{
            $installation_price = $supplement_price->industrial_installation_cost;
        }
        $total_price = $total_price + ($distance*$supplement_price->one_meter_of_cables_cost) + ($cart->number_of_panels*$supplement_price->base_panel_cost) + $installation_price ;
        $cart['total_price'] = $total_price;
        return $cart;

        
    }


    public function get_cart_info_to_admin($id){


        $cart = Cart::where('id',$id)
        ->with('users')
        ->with('inverters')
        ->with('batteries')
        ->with('panels')
        ->with('loads')
        ->with('additional_loads')->first();

        if($cart->inverters->isNotEmpty()){
            foreach($cart->inverters as $inverter){
                $pivot = DB::table('carts_inverters_pivot')->where('cart_id',$id)->where('inverter_id',$inverter->id)->first();
                $inverter->pivot['quantity'] = $pivot->quantity;
            }
        }

        if($cart->batteries->isNotEmpty()){
            foreach($cart->batteries as $battery){
                $pivot = DB::table('carts_batteries_pivot')->where('cart_id',$id)->where('battery_id',$battery->id)->first();
                $battery->pivot['quantity'] = $pivot->quantity;
            }
        }

        if($cart->panels->isNotEmpty()){
            foreach($cart->panels as $panel){
                $pivot = DB::table('carts_panels_pivot')->where('cart_id',$id)->where('panel_id',$panel->id)->first();
                $panel->pivot['quantity'] = $pivot->quantity;
            }
        }

        if($cart->loads->isNotEmpty()){
            foreach($cart->loads as $load){
                $pivot = DB::table('carts_loads_pivot')->where('cart_id',$id)->where('load_id',$load->id)->first();
                $load->pivot['run_at_night'] = $pivot->run_at_night;
                $load->pivot['operating_voltage'] = $pivot->operating_voltage;
            }
        }

        

        
        return $cart;

        
    }



}















?>