<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddBatteriesRequest;
use App\Http\Requests\Admin\UpdateBatteryRequest;
use App\Models\Battery;
use Illuminate\Http\Request;
use App\Traits\ImageProcessing;

use function PHPUnit\Framework\isEmpty;

class BatteryProcessController extends Controller
{
    use ImageProcessing;

   
    //get all of batteries that are in database
    public function get_batteries(){
       $batteries = Battery::all();
       return response()->json($batteries,200);
    }

    //get specific battery
    public function get_specific_battery($id){
        $battery = Battery::where('id',$id)->first();
        return response()->json($battery,200);
    }


    //this function add new battery to batteries table 
    public function add_battery(AddBatteriesRequest $request){
        $battery = $request->validated();

        $batteries = Battery::where('type',$battery['type'])->where('manufacture_company',$battery['manufacture_company'])->where('model',$battery['model'])->where('ampere',$battery['ampere'])->first();
        
        //this situation test if the battery in request is already exists to avoid add twice 
        if($batteries != null){
            $quantityAvailable = $batteries->quantity_available + $battery['quantity'];
            Battery::where('id',$batteries->id)->update([
                                                            'description' => $battery['description'],
                                                            'price' => $battery['price'],
                                                            'quantity_available' => $quantityAvailable,
                                                        ]);
            return response()->json(['success'=>'This battery already exists and its information has been updated '],200);                                            
        
        //add new battery if there is not in table
        }else{ 

            $extension = $request->file('photo')->getClientOriginalExtension();
            $imagePath = $this->saveImage($request->file('photo'),$extension,300,300,'battery_images');
            Battery::create([
                'type' => $battery['type'],
                'manufacture_company' => $battery['manufacture_company'],
                'model' => $battery['model'],
                'ampere' => $battery['ampere'],
                'volt' => $battery['volt'],
                'description' => $battery['description'],
                'quantity_available' => $battery['quantity'],
                'price' => $battery['price'],
                'photo'=>$imagePath,
            ]);

            return response()->json(['success'=>'the battery is added successfully'],200);
        }

        return response()->json(['error'=>'there is an error'],401);
    }
 

    //update a specific battery in database
    public function update_battery(UpdateBatteryRequest $request, $id){
        $battery = $request->validated();

         //if the update request has a new photo
         if($request->hasFile('photo')){
            $old_battery = Battery::where('id',$id)->first();
            $image = $old_battery->photo;
            $this->deleteImage($image);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $imagePath = $this->saveImage($request->file('photo'),$extension,300,300,'battery_images');
            $battery['photo'] = $imagePath;
        }

        Battery::where('id',$id)->update($battery);

       

        return response()->json(['success'=>'the battery updated successfully'],200);
    }


    //to delete a battery from database
    public function delete_battery($id){
        $battery = Battery::where('id',$id)->first();
        $image = $battery->photo;
        $this->deleteImage($image);
        $battery->delete();

        return response()->json(['success'=>'the battery deleted successfully'],200);
    }
    
}
