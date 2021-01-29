<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
class PropertyImages extends Model
{
    use HasFactory;
    protected $table = 'tbl_property_images';
	
	protected $fillable = [
        'property_id',
        'image_name',
        'image_type',
    ];  

    public static function uploadPropertyImages($property_images, $property_id) {

        if ($property_images != null && is_array($property_images)) {

            foreach ($property_images as $k => $v) {

                // find image details
                $imgDetails = TempImageUpload::find($v);

                // create new image
                $old_name = $imgDetails->image_name;
               
                 
                $new_name = str_replace("images/", "images/propertyImages/", $imgDetails->image_name);
               // dd($old_name,$new_name,$imgDetails->image_name);
                              // move temp image to property folder
                 \Storage::disk('local')->move($old_name, $new_name);

                // resize image 
                //self::createPropertyThumbs("images/propertyImages", \Helpers::cdnurl($new_name));

                // create database entry for image
                PropertyImages::create([
                    'property_id' => $property_id,
                    'image_name' => $new_name,
                    'image_type' => $imgDetails->image_category
                ]);

                // delete temp image record from database
                TempImageUpload::where('id', $v)->delete();
            }

        }
        return true;
    }

    public static function createPropertyThumbs($dir, $path) {
        \Helpers::resizeImage($dir, $path, 400);
        \Helpers::resizeImage($dir, $path, 1200);
    }

    public static function deleteImagesOfProperty($id) {
    	
    	// check id is integer else ignore
    	if (isset($id) && $id > 0) {
	    
	    	// select product image name to delete from storage
	    	$images = PropertyImages::where('property_id', $id)->get();
	    	
	    	// get image in loop
	    	foreach ($images as $image) {

	    		// delete from storage
	    		\Storage::disk('local')->delete($image->image_name);
                //\Storage::disk('local')->delete(\Helpers::prepareThumbDirName($image->image_name, 400));
                //\Storage::disk('local')->delete(\Helpers::prepareThumbDirName($image->image_name, 1200));

	    		// delete from database table 
	    		PropertyImages::where('id', $image->id)->delete();

	    	}

	    	return true;
    	}

    	return false;
    }

    public static function deletePropertySingleImage($id) {

        // check id is integer else ignore
        if (isset($id) && $id > 0) {

            // select product image name to delete from storage
            $images = PropertyImages::where('id', $id)->get();
            
            // get image in loop
            foreach ($images as $image) {

                // delete from storage
                \Storage::disk('local')->delete($image->image_name);
                //\Storage::disk('local')->delete(\Helpers::prepareThumbDirName($image->image_name, 400));
               // \Storage::disk('local')->delete(\Helpers::prepareThumbDirName($image->image_name, 1200));

                // delete from database table 
                PropertyImages::where('id', $image->id)->delete();
            }   
            return true;
        }
        return false;
    }
}
