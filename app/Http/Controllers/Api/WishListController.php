<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 use App\Models\Areas;
use App\Models\Cities; 
use App\Models\States;
use App\Models\Countries; 
use App\Models\Banks;
use App\Models\Sites;
use App\Models\SitesImages;
use App\Models\SiteMarketers;
use App\Models\SiteMetas;
use App\Models\SiteLoans;
use App\Models\SiteOffers;
use App\Models\FurnitureDetails;

use App\Models\Properties;
use App\Models\WishList;
use App\Models\PropertyCategory; 
use App\Models\PropertySubCategory;
use App\Models\Helpers;
use Response;
use Carbon\Carbon;

class WishListController extends Controller
{
    private $page_data;

    public function __construct() {
        $this->page_data['perpage'] = 10;
    }

    public function index(Request $request)
    { 

    	$wishListData = [];
        if($request->has('user_id'))
        {	 
            $wishListData = Properties::getWishListProperties($request);
            
             if(isset($wishListData) && $wishListData->count() > 0)
            {
                $wishlistArr = [];
                $i=0;
                foreach ($wishListData  as $k => $v)
                {	 
                    
                	$wishlistArr[$i]['wishListId'] = isset($v->wishList->id) ? (string)$v->wishList->id : '';
                    $wishlistArr[$i]['property_id'] = isset($v->id) ? (string)$v->id : '';
                    $wishlistArr[$i]['code'] = isset($v->code) ? (string)$v->code : null;
                    
                    if(count($v->propertyImages)>0){
                      $wishlistArr[$i]['property_image'] = $v->propertyImages[0]->image_name;    
                    } else 
                    { 
                        $wishlistArr[$i]['property_image'] =null; 

                    }
                    if(count($v->sites->siteImages)>0){

                      $wishlistArr[$i]['site_image'] = $v->sites->siteImages[0]->image_name;    
                    } else 
                    { 
                        $wishlistArr[$i]['site_image'] =null; 

                    }


					$wishlistArr[$i]['site_area'] = isset($v->sites->areas->name) ? $v->sites->areas->name: '';
                    $wishlistArr[$i]['site_area'] .= isset($v->sites->cities->name) ? ', '.$v->sites->cities->name : '';
					$wishlistArr[$i]['property_type'] = isset($v->propertyCategory->name) ? $v->propertyCategory->name : '';
                    $wishlistArr[$i]['property_type'] .= isset($v->propertySubCategory->name) ? ' => '.$v->propertySubCategory->name : '';
                    $wishlistArr[$i]['property_price'] = Helpers::getSitePrice($v->sites);
                    $wishlistArr[$i]['property_url'] = Helpers::getPropertyUrl($v->propertyCategory->name,  $v);
                    $i++;
                }
                 
                return Response::json(['status'=>1,'message'=>'Data found successfully','wishListData'=>$wishlistArr]);
            }
            return Response::json(['status'=>0,'message'=>'No Data found.']);
    	}   
    	return Response::json(['status'=>0,'message'=>'Invalid Request']);     
    }




    public function store(Request $request)
    {
    	 
    	if($request->has('user_id') && $request->has('property_id'))
        {
        	$matchCase = ['user_id'=>$request->user_id,'property_id'=>$request->property_id];
        	//check Already added or not
        	$checkWishlist = WishList::where($matchCase)->get();
        	count($checkWishlist);
        	 
        	if(count($checkWishlist)==0)
        	{
	            // Create wish list
	            $wishList = new WishList;
	            $wishList->user_id = $request->user_id;
	            $wishList->property_id = $request->property_id;
	            if($wishList->save())
	            {
	                return Response::json(['status'=>1,'message'=>'Property added successfully to your wish list','wishListId'=>(string)$wishList->id]);
	            } else {
	            	return Response::json(['status'=>0,'message'=>'Property did not added to your wish list. Please try again...']);
	            }
	       	} else {
	       			return Response::json(['status'=>0,'message'=>'Property is already added in wishlist...']);
	       	}
        	
        }
        return Response::json(['status'=>0,'message'=>'Invalid Request']);

    }


    public function destroy(Request $request)
    {
        if($request->has('user_id') && $request->has('wishListId'))
        {	

            if(WishList::destroy($request->wishListId))
            {
                return Response::json(['status'=>1,'message'=>'Site remove from your wish list successfully.']);
            }
            else
            {
                return Response::json(['status'=>0,'message'=>'Invalid Request']);
            }
        }
    }


}
