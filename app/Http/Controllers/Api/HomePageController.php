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
use App\Models\Properties;
use App\Models\PropertyCategory;
use App\Models\PropertySubCategory;
use App\Models\SiteOffers;
use App\Models\User;
use App\Models\Slider;
use App\Models\Testimonials;
use App\Models\Helpers;
use App\Models\Banners;



class HomePageController extends Controller
{
    private $page_data;

    public function __construct() {

        $this->page_data['page_title'] = 'Active - New properties';
        $this->page_data['perpage'] = 10;
        $this->page_data['mode'] = '';
         
     
    }

    public function homepage(Request $request)
    {
    	$data = [];
    	$data['banner_image'] = Banners::orderBy('id', 'desc')->take(1)->get();
    	$data['slider'] = Slider::orderBy('id', 'desc')->take(5)->get();
		$data['popular_projects']  = Properties::select(['id', 'site_id', 'cat_id', 'sub_cat_id','sub_title', 'description', 'video_link', 'transaction_type', 'price', 'status', 'sort_num','number'])
		                    ->with(['sites'])
		                    ->with(['sites.siteImages'])
		                    ->with(['siteOffers'])
		                    ->with(['propertyImages' => function ($query) {
		                            $query->addSelect('id', 'property_id', 'image_name', 'is_featured', 'is_covered', 'image_type');
		                        }])
		                    ->with(['propertyCategory' => function ($query) {
		                            $query->addSelect('id', 'name', 'slug');
		                        }])
		                    ->with(['propertySubCategory' => function ($query) {
		                            $query->addSelect('id', 'name', 'slug');
		                        }])
		                    ->with(['propertyFeatures' => function ($query) {
		                            $query->addSelect(\Helpers::propertyFeaturesFields());
		                        }])
		                    ->with(['propertyMetas' => function ($query) {
		                            $query->addSelect('property_id', 'meta_key', 'meta_value');
		                        }])->where('is_popular','1')->orderBy('id', 'desc')->take(10)->get();
		$data['featured_projects']  = Properties::select(['id', 'site_id', 'cat_id', 'sub_cat_id', 
		                    'sub_title', 'description', 'video_link', 'transaction_type', 'price', 'status', 'sort_num','number'])
		                    ->with(['sites'])
		                    ->with(['sites.siteImages'])
		                    ->with(['siteOffers'])
		                    ->with(['propertyImages' => function ($query) {
		                            $query->addSelect('id', 'property_id', 'image_name', 'is_featured', 'is_covered', 'image_type');
		                        }])
		                    ->with(['propertyCategory' => function ($query) {
		                            $query->addSelect('id', 'name', 'slug');
		                        }])
		                    ->with(['propertySubCategory' => function ($query) {
		                            $query->addSelect('id', 'name', 'slug');
		                        }])
		                    ->with(['propertyFeatures' => function ($query) {
		                            $query->addSelect(\Helpers::propertyFeaturesFields());
		                        }])
		                    ->with(['propertyMetas' => function ($query) {
		                            $query->addSelect('property_id', 'meta_key', 'meta_value');
		                        }])->where('is_featured', '1')->take(10)->get();
		$data['testimonials'] = Testimonials::orderBy('id','desc')->take(10)->get();   
		
		 
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);                 


    }


}
