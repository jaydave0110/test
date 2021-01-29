<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SitesImages extends Model
{
    use HasFactory;

    protected $table = 'tbl_builder_sites_images';

    protected $fillable = [
        'site_id',
        'image_name',
        'image_type',
        'is_covered'
    ];

    public static function uploadSiteImages($site_images, $site_id) {

        if ($site_images != null && is_array($site_images)) {

            foreach ($site_images as $k => $v) {

                // find image details
                $imgDetails = TempImageUpload::find($v);

                // check for cover image
                $is_covered = 0;
                if ($imgDetails->extra_params != '') {
                    $extra_params = unserialize($imgDetails->extra_params);
                    if (isset($extra_params['is_covered'])) {
                        $is_covered = (int) $extra_params['is_covered'];
                    }
                }

                // create new image
                $old_name = $imgDetails->image_name;
                $new_name = str_replace("images/", "images/siteImages/", $imgDetails->image_name);

                // move temp image to property folder
                \Storage::disk('local')->move($old_name, $new_name);
                
                // resize image 
                //self::createSiteThumbs("images/siteImages", url('/public/storage/'.$imgDetails->image_name));

                // create database entry for image
                self::create([
                    'site_id' => $site_id,
                    'image_name' => $new_name,
                    'is_covered' => (int) $is_covered,
                    'image_type' => $imgDetails->image_category
                ]);

                // delete temp image record from database
                TempImageUpload::where('id', $v)->delete();
            }

        }
        return true;
    }

    public static function createSiteThumbs($dir, $path) {
        \Helpers::resizeImage($dir, $path, 400);
        \Helpers::resizeImage($dir, $path, 1200);
    }

    public static function quickUpload($request) {
        
        $imageUrl = array();
        $errors = '';

        if (isset($request->siteImage) && isset($request->site_id) && $request->site_id > 0) {
            
            foreach ($request->siteImage as $photo) {
                
                $extension = strtolower($photo->extension());
                
                if (\Helpers::isValidImage($extension)) {

                    $filename = \Storage::disk('local')->put('/images/siteImages', $photo, 'public');
                    //self::createSiteThumbs('images/siteImages', \Helpers::cdnurl($filename));

                    $imageDetails = self::create([
                        'site_id' => $request->site_id,
                        'image_name' => $filename,
                        'datecreated' => date('Y-m-d H:i:s')
                    ]);

                    $imageUrl[] = array(
                        'id' => $imageDetails->id, 
                        'url' => url($imageDetails->image_name)
                    );
                } else {
                    $errors = 'File has "'.$extension.'"" extension it\'s not supported currently, JPG image is preferred.';
                }
            }            
            
            return [
                'status' => 'success', 
                'imageUrl' => $imageUrl, 
                'errors' => $errors
            ];
        }
        return false;
    }

    public static function siteImageUpload($request) {
        
        $imageUrl = array();
        $errors = '';

        if (isset($request->siteImage) && isset($request->image_type)  && isset($request->site_id) && $request->site_id > 0) {
            
            foreach ($request->siteImage as $photo) {
                
                $extension = strtolower($photo->extension());
                
                if (\Helpers::isValidImage($extension)) {

                    $filename = \Storage::disk('local')->put('/images', $photo, 'public');
                    //self::createSiteThumbs('images/siteImages', \Helpers::cdnurl($filename));

                    $imageDetails = self::create([
                        'site_id' => $request->site_id,
                        'image_name' => $filename,
                        'image_type' => $request->image_type,
                        'datecreated' => date('Y-m-d H:i:s')
                    ]);

                    $imageUrl[] = array(
                        'id' => $imageDetails->id, 
                        'url' => url($imageDetails->image_name),                        
                        'thumb' => \Helpers::getSiteThumbUrl($filename, 400),
                    );
                } else {
                    $errors = 'File has "'.$extension.'"" extension it\'s not supported currently, JPG image is preferred.';
                }
            }            
            
            return [
                'status' => 'success', 
                'imageUrl' => $imageUrl, 
                'errors' => $errors
            ];
        }
        return false;
    }

    public static function deleteImagesOfSites($id) {
        
        // check id is integer else ignore
        if (isset($id) && $id > 0) {
        
            // select site image name to delete from storage
            $siteImages = self::where('site_id', $id)->get();
            
            // get image in loop
            foreach ($siteImages as $image) {

                // delete from storage
                \Storage::get('/images')->delete($image->image_name);
                \Storage::disk('/images')->delete(\Helpers::prepareThumbDirName($image->image_name, 400));
                \Storage::disk('/images')->delete(\Helpers::prepareThumbDirName($image->image_name, 1200));

                // delete from database table 
                self::where('site_id', $image->id)->delete();
            }
            return true;
        }
        return false;
    }

    public static function deleteSiteSingleImage($id) {
        
        if (isset($id) && $id > 0) {
            
            // select product image name to delete from storage
            $siteImages = self::where('id', $id)->get();
            
            // get image in loop
            foreach ($siteImages as $image) {

                unlink('public/'.$image->image_name);
                // delete from storage
                /*\Storage::disk('/images/')->delete($image->image_name);
                \Storage::disk('/images')->delete(\Helpers::prepareThumbDirName($image->image_name, 400));
                \Storage::disk('/images')->delete(\Helpers::prepareThumbDirName($image->image_name, 1200));
*/
                // delete from database table 
                self::where('id', $image->id)->delete();
            }

            return true;
        }
        return false;
    }

     public static function updateSiteCover($imageId, $siteId) {
        
        if ($imageId > 0 && $siteId > 0) {

            // remove mark from all images 
            self::where(['site_id' => $siteId])->update(['is_covered' => 0]);

            // now mark only one site image as default
            self::where(['id' => $imageId, 'site_id' => $siteId])->update(['is_covered' => 1]);

            return true;
        }
        return false;
    }
    
}
