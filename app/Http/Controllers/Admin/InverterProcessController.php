<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddInvertersRequest;
use App\Http\Requests\Admin\UpdateInverterRequest;
use Illuminate\Http\Request;
use App\Traits\ImageProcessing;
use App\Models\Inverter;

use function PHPUnit\Framework\isEmpty;

class InverterProcessController extends Controller
{
    use ImageProcessing;

   
    //get all of inverters that are in database
    public function get_inverters(){
       $inverters = Inverter::all();
       return response()->json($inverters,200);
    }

    //get specific inverter
    public function get_specific_inverter($id){
        $inverter = Inverter::where('id',$id)->first();
        return response()->json($inverter,200);
    }


    //this function add new inverter to inverters table 
    public function add_inverter(AddInvertersRequest $request){
        $inverter = $request->validated();

        $inverters = Inverter::where('type',$inverter['type'])->where('manufacture_company',$inverter['manufacture_company'])->where('model',$inverter['model'])->where('watt',$inverter['watt'])->first();
        
        //this situation test if the inverter in request is already exists to avoid add twice 
        if($inverters != null){
            $quantityAvailable = $inverters->quantity_available + $inverter['quantity'];
            Inverter::where('id',$inverters->id)->update([
                                                            'description' => $inverter['description'],
                                                            'price' => $inverter['price'],
                                                            'quantity_available' => $quantityAvailable,
                                                        ]);
            return response()->json(['success'=>'This inverter already exists and its information has been updated '],200);                                            
        
        //add new inverter if there is not in table
        }else{

            $extension = $request->file('photo')->getClientOriginalExtension();
            $imagePath = $this->saveImage($request->file('photo'),$extension,300,300,'inverter_images');
            Inverter::create([
                'type' => $inverter['type'],
                'manufacture_company' => $inverter['manufacture_company'],
                'model' => $inverter['model'],
                'watt' => $inverter['watt'],
                'description' => $inverter['description'],
                'quantity_available' => $inverter['quantity'],
                'price' => $inverter['price'],
                'photo'=>$imagePath,
            ]);

            return response()->json(['success'=>'the inverter is added successfully'],200);
        }

        return response()->json(['error'=>'there is an error'],401);
    }


    //update a specific inverter in database
    public function update_inverter(UpdateInverterRequest $request, $id){
        $inverter = $request->validated();

         //if the update request has a new photo
         if($request->hasFile('photo')){
            $old_inverter = Inverter::where('id',$id)->first();
            $image = $old_inverter->photo;
            $this->deleteImage($image);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $imagePath = $this->saveImage($request->file('photo'),$extension,300,300,'inverter_images');
            $inverter['photo'] = $imagePath;
        }

        Inverter::where('id',$id)->update($inverter);

       

        return response()->json(['success'=>'the inverter updated successfully'],200);
    }


    //to delete a inverter from database
    public function delete_inverter($id){
        $inverter = Inverter::where('id',$id)->first();
        $image = $inverter->photo;
        $this->deleteImage($image);
        $inverter->delete();

        return response()->json(['success'=>'the inverter deleted successfully'],200);
    }
}
