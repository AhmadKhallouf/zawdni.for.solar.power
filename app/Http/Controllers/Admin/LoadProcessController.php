<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddLoadsRequest;
use App\Http\Requests\Admin\UpdateLoadRequest;
use Illuminate\Http\Request;
use App\Models\Load;
use App\Traits\ImageProcessing;

use function PHPUnit\Framework\isEmpty;

class LoadProcessController extends Controller
{
    use ImageProcessing;

   
    //get all of loads that are in database
    public function get_loads(){
       $loads = Load::all();
       return response()->json($loads,200);
    }

    //get specific Load
    public function get_specific_load($id){
        $load = Load::where('id',$id)->first();
        return response()->json($load,200);
    }


    //this function add new Load to inverters table 
    public function add_load(AddLoadsRequest $request){
        $load = $request->validated();

        $loads = Load::where('load',$load['load'])->where('watt',$load['watt'])->first();
        
        //this situation test if the Load in request is already exists to avoid add twice 
        if($loads != null){
            Load::where('id',$loads->id)->update([
                                                            'description' => $load['description'],
                                                        ]);
            return response()->json(['success'=>'This load already exists and its information has been updated '],200);                                            
        
        //add new Load if there is not in table
        }else{

            $extension = $request->file('photo')->getClientOriginalExtension();
            $imagePath = $this->saveImage($request->file('photo'),$extension,300,300,'Load_images');
            Load::create([
                'load' => $load['load'], 
                'watt' => $load['watt'],
                'description' => $load['description'],
                'photo'=>$imagePath,
            ]);

            return response()->json(['success'=>'the load is added successfully'],200);
        }

        return response()->json(['error'=>'there is an error'],401);
    }


    //update a specific inverter in database
    public function update_load(UpdateLoadRequest $request, $id){
        $load = $request->validated();

         //if the update request has a new photo
         if($request->hasFile('photo')){
            $old_load = Load::where('id',$id)->first();
            $image = $old_load->photo;
            $this->deleteImage($image);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $imagePath = $this->saveImage($request->file('photo'),$extension,300,300,'load_images');
            $load['photo'] = $imagePath;
        }

        Load::where('id',$id)->update($load);

       

        return response()->json(['success'=>'the load updated successfully'],200);
    }


    //to delete a Load from database
    public function delete_load($id){
        $load = Load::where('id',$id)->first();
        $image = $load->photo;
        $this->deleteImage($image);
        $load->delete();

        return response()->json(['success'=>'the load deleted successfully'],200);
    }
}
