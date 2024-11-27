<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddDamagesRequest;
use App\Http\Requests\Admin\UpdateDamageRequest;
use Illuminate\Http\Request;
use App\Models\Damage;

use function PHPUnit\Framework\isEmpty;

class DamageProcessController extends Controller
{
    //get all of damages that are in database
    public function get_damages(){
        $damages = Damage::all();
        return response()->json($damages,200);
     }
 
     //get specific damage
     public function get_specific_damage($id){
         $damage = Damage::where('id',$id)->first();
         return response()->json($damage,200);
     }
 
 
     //this function add new damage to damages table 
     public function add_damage(AddDamagesRequest $request){
         $damage = $request->validated();
 
         $damages = Damage::where('type_of_inverter',$damage['type_of_inverter'])->where('manufacture_company',$damage['manufacture_company'])->where('model_of_inverter',$damage['model_of_inverter'])->where('watt',$damage['watt'])->where('code',$damage['code'])->first();
         
         //this situation test if the damage in request is already exists to avoid add twice 
         if($damages != null){
             Damage::where('id',$damages->id)->update([
                                                             'description' => $damage['description'],
                                                             'solution' => $damage['solution'],
                                                         ]);
             return response()->json(['success'=>'This inverter already exists and its information has been updated '],200);                                            
         
         //add new damage if there is not in table
         }else{
 
             Damage::create([
                 'type_of_inverter' => $damage['type_of_inverter'],
                 'manufacture_company' => $damage['manufacture_company'],
                 'model_of_inverter' => $damage['model_of_inverter'],
                 'watt' => $damage['watt'],
                 'code' => $damage['code'],
                 'description' => $damage['description'],
                 'solution' => $damage['solution'],
             ]);
 
             return response()->json(['success'=>'the damage is added successfully'],200);
         }
 
         return response()->json(['error'=>'there is an error'],401);
     }
 
 
     //update a specific inverter in database
     public function update_damage(UpdateDamageRequest $request, $id){
         $damage = $request->validated();
 
         Damage::where('id',$id)->update($damage);
 
        
         return response()->json(['success'=>'the damage updated successfully'],200);
     }
 
 
     //to delete a inverter from database
     public function delete_damage($id){

         $damage = Damage::where('id',$id)->delete();
 
         return response()->json(['success'=>'the damage deleted successfully'],200);
     }
}
