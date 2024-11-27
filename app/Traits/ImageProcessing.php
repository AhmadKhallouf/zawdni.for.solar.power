<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage as FacadesStorage;
use Image;
use Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

trait ImageProcessing{

    //this function use to save image in a specific place that is determined by admin
    public function saveImage($image,$extension,$width,$hight,$folder){
        $manager = ImageManager::gd();
        $img = $manager->read($image);
        $img->resize($width,$hight);
        $str_random = Str::random(8);
        $imagePath = $str_random.time().".".$extension;
        $img->save(storage_path('app/public/images').'/'.$folder.'/'.$imagePath);

        $file_url = Storage::url('images/'.$folder.'/'.$imagePath);
        
        return $file_url;

    } 


    //delete a specific image
public function deleteImage($image){
    
    // Check if the file exists
    if (File::exists(public_path($image))) {
        // Delete the file
        File::delete(public_path($image));
    }
}

}