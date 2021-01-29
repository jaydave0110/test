<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TempImageUpload;
use File;
class TempImageUploadController extends Controller
{
    public function upload(Request $request) {

		$imageUrl = array();
		$errors = '';

		if (isset($request->tempImage)) {
           
            foreach ($request->tempImage as $photo) {
                
                $extension = strtolower($photo->extension());
                $file_size = $photo->getSize();

                // check file extension
                if (in_array($extension, array('jpeg', 'jpg', 'png'))) {

                	// check file size must not be more than permitted
                	if ($file_size <= config('app.max_file_size')) {

                		$filename = \Storage::disk('local')->put('/images', $photo, 'public');
 			        	/*$filename = time().'.'.$photo->getClientOriginalExtension();
				        $destinationPath = public_path('/uploads/images/tmpImages');
				        $photo->move($destinationPath, $filename);*/


		            	$imageDetails = TempImageUpload::create([
		                    'image_name' => $filename,
		                    'datecreated' => date('Y-m-d H:i:s')
		                ]);

		            	$imageUrl[] = array(
	            			'id' => $imageDetails->id, 
	            			'url' => url($imageDetails->image_name)
	            		);


                	} else {
                		$errors = 'Please upload image file size up to '.(config('app.max_file_size') / 1024 / 1024).' MB';
                	}
                } else {
                	$errors = 'Please upload only jpeg, jpg, png images, other formats are not supported';
                }
            }
        }
        return response()->json(array('status' => 'success', 'images' => $imageUrl, 'errors' => $errors));
	}


	public function changeImageType(Request $request) {
			
		if (isset($request->image_id) && $request->image_id > 0) {

			$id = TempImageUpload::where('id', $request->image_id)
					->update(['image_category' => $request->image_category]);

			return response()->json(array('status' => 'success'));

		}
	}

	public function tempRemoveImg(Request $request) {

		if (isset($request->image_id) && $request->image_id > 0) {

			// store id in variable
			$id = $request->image_id;

			// select product image name to delete from storage
	    	$image = TempImageUpload::where('id', $id)->get();
	    	
	    	// get image in loop
	    	foreach ($image as $photo) {

	    		// delete from storage
	    		//\Storage::disk(config('app.cdn'))->delete($photo->image_name);
	    		File::delete($photo->image_name);


	    		// delete from database table 
	    		TempImageUpload::where('id', $photo->id)->delete();

	    	}

			return response()->json(array('status' => 'success'));

		}
	}

	public function tempUpdatesitecover(Request $request) {

        if (isset($request->imgId) && $request->imgId > 0 && 
    		isset($request->imageList) && count(array($request->imageList)) > 0) {

        	// convert json to php array
        	$arrImage = json_decode($request->imageList);
        	
        	// remove mark from all images 
        	if (is_array($arrImage) && count($arrImage) > 0) {
        		TempImageUpload::whereIn('id', $arrImage)->update(['extra_params' => '']);
        	}

        	// mark selected image as covered
            TempImageUpload::where(['id' => $request->imgId])
							->update(['extra_params' => serialize(array('is_covered' => 1	))]);

			return response()->json([
                'status' => 'success',
                'msg' => 'Image marked as cover image.'
            ]);

        } else {
            return response()->json([
                'status' => 'error',
                'msg' => 'Unable to change site cover image, please try again.'
            ]);
        }

    }
}
