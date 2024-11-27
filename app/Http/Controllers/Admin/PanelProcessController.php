<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddPanelsRequest;
use App\Http\Requests\Admin\UpdatedPanelsRequest;
use App\Models\Panel;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;
use App\Traits\ImageProcessing;

class PanelProcessController extends Controller
{

    use ImageProcessing;

     //get all of panels that are in database
     public function get_panels(){
        $panels = Panel::all();
        return response()->json($panels,200);
     }

      //get specific panel
    public function get_specific_panel($id){
        $panel = Panel::where('id',$id)->first();
        return response()->json($panel,200);
    }

    //this function add new panel to panels table 
    public function add_panel(AddPanelsRequest $request){
        $panel = $request->validated();

        $panels = Panel::where('type',$panel['type'])->where('manufacture_company',$panel['manufacture_company'])->where('model',$panel['model'])->where('watt',$panel['watt'])->where('width',$panel['width'])->where('hight',$panel['hight'])->first();
        
        //this situation test if the panel in request is already exists to avoid add twice 
        if($panels != null){
            $quantityAvailable = $panels->quantity_available + $panel['quantity'];
            Panel::where('id',$panels->id)->update([
                                                            'description' => $panel['description'],
                                                            'price' => $panel['price'],
                                                            'quantity_available' => $quantityAvailable,
                                                        ]);
            return response()->json(['success'=>'This panel already exists and its information has been updated '],200);                                            
        
        //add new battery if there is not in table
        }else{

            $extension = $request->file('photo')->getClientOriginalExtension();
            $imagePath = $this->saveImage($request->file('photo'),$extension,300,300,'panel_images');
            Panel::create([
                'type' => $panel['type'],
                'manufacture_company' => $panel['manufacture_company'],
                'model' => $panel['model'],
                'watt' => $panel['watt'],
                'width' => $panel['width'],
                'hight' => $panel['hight'],
                'description' => $panel['description'],
                'quantity_available' => $panel['quantity'],
                'price' => $panel['price'],
                'photo'=>$imagePath,
            ]);

            return response()->json(['success'=>'the panel is added successfully'],200);
        }

        return response()->json(['error'=>'there is an error'],401);
    }


    //update a specific battery in database
    public function update_panel(UpdatedPanelsRequest $request, $id){
        $panel = $request->validated();

         //if the update request has a new photo
         if($request->hasFile('photo')){
            $old_panel = Panel::where('id',$id)->first();
            $image = $old_panel->photo;
            $this->deleteImage($image);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $imagePath = $this->saveImage($request->file('photo'),$extension,300,300,'panel_images');
            $panel['photo'] = $imagePath;
            
        }
 
        Panel::where('id',$id)->update($panel);

       

        return response()->json(['success'=>'the panel updated successfully'],200);
    }


    //to delete a battery from database
    public function delete_panel($id){
        $panel = Panel::where('id',$id)->first();
        $image = $panel->photo;
        $this->deleteImage($image);
        $panel->delete();

        return response()->json(['success'=>'the panel deleted successfully'],200);
    }
}
