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
use App\Models\PropertyCategory; 
use App\Models\PropertySubCategory;
use App\Models\PropertyFeatures;
use App\Models\Helpers;
use Response;
use Carbon\Carbon;
use App\Models\WishList;
class SitesController extends Controller
{
    private $page_data;

    public function __construct() {
        $this->page_data['perpage'] = 10;
    }

    


    public function listProperties(Request $request)
    {
        $data  = [];
        $arr  = [];
        $query = Properties::select(['id', 'site_id','code','city_id','cat_id', 'sub_cat_id', 
                    'sub_title', 'description', 'video_link', 'transaction_type', 'price', 'status', 'sort_num','number','is_featured','is_popular','is_commission','commission_percent','commission_basic_price','commission_amount','package','brokrage_type','fix_pay_type','fix_pay_amount','is_escalation','is_garden_facing','garden_facing_amount','is_club_house_facing','club_house_facing_amount','id_road_facing','road_facing_amount','is_croner_flat','corner_flat_amount','is_others','other_amount',\DB::raw('sub_title  AS title')])
                    
                    ->with(['sites'])
                    ->with(['sites.siteImages'])
                    ->with(['sites.areas'=>function($query){
                        $query->addSelect('id','name');
                    }])
                    ->with(['sites.cities'=>function($query){
                        $query->addSelect('id','name');
                    }])
                    ->with(['sites.states'=>function($query){
                        $query->addSelect('id','name');
                    }])
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
                        }]);
                    
                    $query->where('status', 1);
                   
                    if (isset($request->propertycategory)) {

                        $arrCategory[] = $arrSubCategory = [];
                        foreach ($request->propertycategory as $key => $value) {
                            $tmpCategory = explode('|', $value);
                            $arrCategory[] = $tmpCategory[0];
                        }
                        $query->whereIn('tbl_properties.cat_id',$tmpCategory);
                    
                        if (isset($request->propertysubcategory)) {

                            $arrSubCategory = [];
                            foreach ($request->propertysubcategory as $key => $value) {
                                $arrSubCategory = explode('|', $value);
                            }
                        $query->whereIn('tbl_properties.sub_cat_id',$arrSubCategory);
                        }

                    }

                     /* budget filter */
                    if (isset($request->minbudget) && !empty($request->minbudget) && isset($request->maxbudget) && !empty($request->maxbudget)) { 
                         
                        if ($request->minbudget != '' && $request->maxbudget != '') {
                            $query->whereBetween('price', array($request->minbudget, $request->maxbudget));
                        } else if ($request->minbudget != '' && trim($request->maxbudget) == '') {
                            $query->where('price', '>', $request->minbudget);
                        } else if (trim($request->minbudget) == '' && $request->maxbudget != '') {
                            $query->where('price', '<', $request->maxbudget);
                        }
                    }

                    /* bhk filters - start */
                    
                    if (isset($request->bhk) && !empty($request->bhk)) {
                        if (is_array($request->bhk)) {
                            
                            $arrBHKValues = Helpers::filterStaticValues('filter_bhk');
                            $arrbhk = [];
                            foreach ($request->bhk as $key => $bhk) {
                               
                                $arrbhk[] = $bhk;
                            }
                            if (is_array($arrbhk) && count($arrbhk) > 0) {
                                    $query->WhereIn('id', PropertyFeatures::whereIn('bedrooms', $arrbhk)
                                        ->pluck('property_id'));
                                }
                            //dd($arrbhk);

                           /* $query->where(function ($query) use ($request, $arrBHKValues) {
                                $arrBedrooms = [];
                                foreach ($request->bhk as $value) {
                                    $static_key = array_search($value, $arrBHKValues);
                                    if ($static_key !== false) {
                                        $arrBedrooms[] = $static_key;
                                    }
                                }
                                
                                // for where or 
                                if (is_array($arrBedrooms) && count($arrBedrooms) > 0) {
                                    $query->orWhereIn('id', PropertyFeatures::whereIn('bedrooms', $arrBedrooms)
                                        ->pluck('property_id'));
                                }

                            });*/
                        }
                    }

                     
                    if (isset($request->areas) && !empty($request->areas)) {
                         
                        if (is_array($request->areas)) {

                            $query->whereHas('sites', function($query) use($request) {
                                 $query->whereIn('area_id',$request->areas);
                            });
                        }
                    }

                    if (isset($request->site_name) && !empty($request->site_name)) {
                        
                        $query->whereHas('sites', function($query) use($request) {
                            $query->where('site_name',$request->site_name);
                        });
                        
                    }

                    if (isset($request->possession_status) && !empty($request->possession_status))
                    {
                          
                        $query->whereHas('sites', function($query) use($request) {
                            $query->where('possession_status','=',$request->possession_status);
                        });
                        
                    }

                    if (isset($request->possession_date) && !empty($request->possession_date))
                    {   $year = date('Y', strtotime($request->possession_date));
                        $month = date('m', strtotime($request->possession_date));
                       // $year = $request->possession_date->year;
                        //$month = $request->possession_date->month;
                          
                          $query->whereHas('sites', function($query) use($year,$month) {
                            $query->whereYear('possession_date',$year);
                            $query->whereMonth('possession_date',$month);
                        }); 
                        
                    }

                    if(isset($request->user_id) && !empty($request->user_id))
                    {
                        $user_id = $request->user_id;
                        $query->with(['wishList'=>function($query) use($user_id){
                            $query->where('user_id',$user_id);
                        }]);
                    }

                    if (isset($request->cityId) && !empty($request->cityId)) {
                        
                        $query->where('city_id',$request->cityId);
                         
                    }   

                    if(isset($request->is_featured))
                    {
                        $query->where('is_featured',1);
                    }
                    
                    if(isset($request->is_popular))
                    {
                        $query->where('is_popular',1);
                    }

                    if(isset($request->sort_by_name) && $request->sort_by_name=="1")
                    {   
                        $query->orderBy('title','DESC');
                    }

                    if(isset($request->sort_by_name) && $request->sort_by_name==0)
                    {
                        $query->orderBy('title','ASC');
                    }

                    if(isset($request->sort_by_price) && $request->sort_by_price==1)
                    {
                     $query->orderBy('tbl_properties.price','DESC');
                    }


                    if(isset($request->sort_by_price) && $request->sort_by_price==0)
                    {
                     $query->orderBy('tbl_properties.price', 'ASC');
                    }
                    




        $properties=$query->paginate(10);  
        $data['properties'] = $properties;
        $data['furniture'] = FurnitureDetails::get(['id','name','cost']);


       


        $data['pagination'] = Helpers::propertyPaginationMetadata($properties);
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);

    }   

    public function filterListProperties(Request $request)
    {
         
        $query = Properties::select(['id', 'site_id', 'cat_id', 'sub_cat_id', 
                    'sub_title', 'description', 'video_link', 'transaction_type', 'price', 'status', 'sort_num','number'])
                    ->with(['sites' => function ($query) {
                            $query->addSelect('id');
                        }])
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
                        }]);
      
                    $query->where('status', '1');
                   
                    if (isset($request->propertycategory)) {

                        $arrCategory[] = $arrSubCategory = [];
                        foreach ($request->propertycategory as $key => $value) {
                            $tmpCategory = explode('|', $value);
                            $arrCategory[] = $tmpCategory[0];
                        }
                        $query->whereIn('tbl_properties.cat_id',$tmpCategory);
                    
                        if (isset($request->propertysubcategory)) {

                            $arrSubCategory = [];
                            foreach ($request->propertysubcategory as $key => $value) {
                                $arrSubCategory = explode('|', $value);
                            }
                        $query->whereIn('tbl_properties.sub_cat_id',$arrSubCategory);
                        }

                    }

                     /* budget filter */
                    if (isset($request->minbudget) && !empty($request->minbudget) && isset($request->maxbudget) && !empty($request->maxbudget)) { 
                         
                        if ($request->minbudget != '' && $request->maxbudget != '') {
                            $query->whereBetween('price', array($request->minbudget, $request->maxbudget));
                        } else if ($request->minbudget != '' && trim($request->maxbudget) == '') {
                            $query->where('price', '>', $request->minbudget);
                        } else if (trim($request->minbudget) == '' && $request->maxbudget != '') {
                            $query->where('price', '<', $request->maxbudget);
                        }
                    }

                    /* bhk filters - start */
                    
                    if (isset($request->bhk) && !empty($request->bhk)) {
                        if (is_array($request->bhk)) {
                            
                            $arrBHKValues = Helpers::filterStaticValues('filter_bhk');

                            $query->where(function ($query) use ($request, $arrBHKValues) {
                                $arrBedrooms = [];
                                foreach ($request->bhk as $value) {
                                    $static_key = array_search($value, $arrBHKValues);
                                    if ($static_key !== false) {
                                        $arrBedrooms[] = $static_key;
                                    }
                                }
                                
                                // for where or 
                                if (is_array($arrBedrooms) && count($arrBedrooms) > 0) {
                                    $query->orWhereIn('id', PropertyFeatures::whereIn('bedrooms', $arrBedrooms)
                                        ->pluck('property_id'));
                                }

                            });
                        }
                    }


                    if (isset($request->areas) && !empty($request->areas)) {
                        if (is_array($request->areas)) {
                            $query->whereHas('sites', function($query) use($request) {
                                 $query->whereIn('area_id',$request->areas);
                            });
                        }
                    }

                    if (isset($request->site_name) && !empty($request->site_name)) {
                        
                        $query->whereHas('sites', function($query) use($request) {
                            $query->where('site_name',$request->site_name);
                        });
                        
                    }
        $properties=$query->paginate(10);  
        $data['properties'] = $properties;

                $offerImageData = [];
                $offerImageData['furniture_center_table'] ="public/images/FurnitureComponents/center_table.jpg";
                $offerImageData['furniture_dining_table'] ="public/images/FurnitureComponents/dining_table.jpg";
                $offerImageData['furniture_kingsize_bad'] ="public/images/FurnitureComponents/kingsize_bad.jpg";
                $offerImageData['furniture_sofa'] ="public/images/FurnitureComponents/sofa.jpg";
                $offerImageData['furniture_tv'] ="public/images/FurnitureComponents/tv.jpg";
                $offerImageData['furniture_wardrobe_table'] ="public/images/FurnitureComponents/wardrobe_table.jpg";
                $offerImageData['furniture_kitchen_furniture'] ="public/images/FurnitureComponents/kitchen_furniture.jpg";
                $offerImageData['furniture_platform_setup'] ="public/images/FurnitureComponents/platform_setup.jpg";
                $offerImageData['furniture_matresses'] ="public/images/FurnitureComponents/matresses.jpg";



                $offerImageData['kitchen_kitchen_overhead'] = "public/images/KitchenComponents/kitchen_overhead.jpg";
                $offerImageData['kitchen_kitchen_platform'] = "public/images/KitchenComponents/kitchen_platform.jpg";
                $offerImageData['kitchen_loft_work'] = "public/images/KitchenComponents/loft_work.jpg";
                $offerImageData['kitchen_service_cabinet'] = "public/images/KitchenComponents/service_cabinet.jpg";
                $offerImageData['kitchen_service_overhead'] = "public/images/KitchenComponents/service_overhead.jpg";


                $offerImageData['home_AC'] = "public/images/HomeAppliances/AC.jpg";
                $offerImageData['home_fridge'] = "public/images/HomeAppliances/fridge.jpg";
                $offerImageData['home_Tv'] = "public/images/HomeAppliances/Tv.jpg";
                $offerImageData['home_washing_machine'] = "public/images/HomeAppliances/washing_machine.jpg";
                $offerImageData['home_other'] = "public/images/HomeAppliances/other.jpg";

        $data['pagination'] = Helpers::propertyPaginationMetadata($properties);
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'offerImageData'=>$offerImageData
        ]);  
    }

    public function propertiesDetails(Request $request) 
    {

        $data       = [];
        $propertyId = $request->property_id;
        $area_unit = Helpers::getStaticValues('area_unit');  
     
        $details = Properties::select(['id', 'site_id','code' ,'cat_id', 'sub_cat_id', 
                    'sub_title', 'description', 'video_link', 'transaction_type', 'price', 'status', 'sort_num','number','is_featured','is_popular','is_commission','commission_percent','commission_basic_price','commission_amount','package','brokrage_type','fix_pay_type','fix_pay_amount','is_escalation','is_garden_facing','garden_facing_amount','is_club_house_facing','club_house_facing_amount','id_road_facing','road_facing_amount','is_croner_flat','corner_flat_amount','is_others','other_amount'])
                    ->with(['sites'])
                    ->with(['sites.siteImages'])
                    ->with(['siteOffers']) 
                    ->with(['sites.siteLoans']) 
                    ->with(['sites.siteMetas']) 
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
                        }])->find($propertyId);
 
        if ($details ) {
              
            // fetch site categories to check for automatic redirection
            //$propertyCategories = Helpers::getSitePropertyCategory($details->propertyCategory );
           $propertyCategories = $details->propertyCategory->name;
            
            // if site has single category property then redirect to details page
            if (!empty($propertyCategories) && !empty($propertyCategories) > 0) {
                    
                // get property url
                $url = Helpers::getPropertyUrl($propertyCategories,$details);
                $propertyDetail = explode('/',$url);
                
                 
                // Get property details
                 
                

                $propertyDetails = [];

                if(isset($details->sites->contact_person_phone) && $details->sites->contact_person_phone != "")
                {
                    $propertiesDetails['contact_person_phone']= isset($details->sites->contact_person_phone) ? $details->sites->contact_person_phone : '';
                }

                $propertyDetails['property_image'] = null;

                if(isset($details->propertyImages) && $details->propertyImages != "")
                {
                     $i = 0;
                    foreach ($details->propertyImages as $key => $value) {
                        $propertyDetails['property_image'][$i]['id'] = $value->id;
                        $propertyDetails['property_image'][$i]['image_name'] = $value->image_name;
                        $propertyDetails['property_image'][$i]['is_featured'] = $value->is_featured;
                        $propertyDetails['property_image'][$i]['is_covered'] = $value->is_covered;
                        $propertyDetails['property_image'][$i]['image_type'] = $value->image_type;
                        $i++;
                    }
                }


                if(isset($details->sites->siteImages) && $details->sites->siteImages != "")
                {
                     $i = 0;
                    foreach ($details->sites->siteImages as $key => $value) {
                        $propertyDetails['site_image'][$i]['id'] = $value->id;
                        $propertyDetails['site_image'][$i]['image_name'] = $value->image_name;
                        $propertyDetails['site_image'][$i]['is_featured'] = $value->is_featured;
                        $propertyDetails['site_image'][$i]['is_covered'] = $value->is_covered;
                     
                        $i++;
                    }
                }

                 




                

                $propertyDetails['residential_resale'] = null;

                if(isset($details->cat_id) && $details->cat_id == '1')
                {   
                    // Construction details
                    $cd = 0;
                    if(isset($details->propertyFeatures->carpet_area) && isset($details->propertyFeatures->carpet_area_unit))
                    {
                        $propertyDetails['residential_resale']['construction_details'][$cd]['title'] = 'Carpet Area';
                        $propertyDetails['residential_resale']['construction_details'][$cd]['value'] = $details->propertyFeatures->carpet_area.' '.$area_unit[$details->propertyFeatures->carpet_area_unit];
                        $cd++;
                    }
                     
                    if(isset($details->propertyFeatures->sb_area) && isset($details->propertyFeatures->sb_area_unit))
                    {
                        $propertyDetails['residential_resale']['construction_details'][$cd]['title'] = 'SB Area';
                        $propertyDetails['residential_resale']['construction_details'][$cd]['value'] = $details->propertyFeatures->sb_area.' '.$area_unit[$details->propertyFeatures->sb_area_unit];
                        $cd++;
                    }


                   
                    if(isset($details->propertyFeatures->bedrooms) && !empty($details->propertyFeatures->bedrooms))
                    {
                        $propertyDetails['residential_resale']['construction_details'][$cd]['title'] = 'Bedrooms';
                        $propertyDetails['residential_resale']['construction_details'][$cd]['value'] = $details->propertyFeatures->bedrooms;
                        $cd++;
                    }
                    
                    if(isset($details->propertyFeatures->bathrooms) && !empty($details->propertyFeatures->bathrooms))
                    {
                        $propertyDetails['residential_resale']['construction_details'][$cd]['title'] = 'Bathrooms';
                        $propertyDetails['residential_resale']['construction_details'][$cd]['value'] = $details->propertyFeatures->bathrooms;
                        $cd++;
                    }


                    if(isset($details->propertyFeatures->balconies) && !empty($details->propertyFeatures->balconies))
                    {
                        $propertyDetails['residential_resale']['construction_details'][$cd]['title'] = 'Balconies';
                        $propertyDetails['residential_resale']['construction_details'][$cd]['value'] = $details->propertyFeatures->balconies;
                        $cd++;
                    }

                    if(isset($details->propertyFeatures->no_of_parking) && !empty($details->propertyFeatures->no_of_parking))
                    {
                        $propertyDetails['residential_resale']['construction_details'][$cd]['title'] = 'No Of Parking';
                        $propertyDetails['residential_resale']['construction_details'][$cd]['value'] = (string)$details->propertyFeatures->no_of_parking;
                        $cd++;
                    }

                    if(isset($details->propertyFeatures->total_floors) && !empty($details->propertyFeatures->property_on_floor))
                    {
                        $propertyDetails['residential_resale']['construction_details'][$cd]['title'] = 'Property On Floor';
                        $propertyDetails['residential_resale']['construction_details'][$cd]['value'] = $details->propertyFeatures->property_on_floor.' Out Of '.$details->propertyFeatures->total_floors;
                        $cd++;
                    }
                     


                    if(isset($details->propertyFeatures->society_name) && !empty($details->propertyFeatures->society_name))
                    {
                        $propertyDetails['residential_resale']['construction_details'][$cd]['title'] = 'Society Name';
                        $propertyDetails['residential_resale']['construction_details'][$cd]['value'] = $details->propertyFeatures->society_name;
                        $cd++;
                    }

                    // Interior details
                    $propertyDetails['interior_details'] = null;
                    $rid = 0;
                    if(isset($details->propertyFeatures->interior_details) && !empty($details->propertyFeatures->interior_details))
                    {
                        $propertyDetails['residential_resale']['interior_details'][$rid]['title'] = 'Interior Detail';
                        $propertyDetails['residential_resale']['interior_details'][$rid]['value'] = $details->propertyFeatures->interior_details;
                        $rid++;
                    }


                     
                    if(isset($details->propertyFeatures->furnished_status) && !empty($details->propertyFeatures->furnished_status))
                    {
                        $propertyDetails['residential_resale']['interior_details'][$rid]['title'] = 'Furnished Status';
                        $propertyDetails['residential_resale']['interior_details'][$rid]['value'] = $details->propertyFeatures->furnished_status;
                        $rid++;
                    }

                    // Price details
                    $propertyDetails['residential_resale']['price_details'] = \Helpers::getResalePriceDetails($details);


                }
               
                
                // Commercial resale data
                $propertyDetails['commercial_resale'] = null;
                if(isset($details->cat_id) && $details->cat_id == '2')
                { 
                    // Construction Details 
                    $cd = 0;

                    if(isset($details->propertyFeatures->carpet_area) && isset($details->propertyFeatures->carpet_area_unit))
                    {
                        $propertyDetails['commercial_resale']['construction_details'][$cd]['title'] = 'Carpet Area';
                        $propertyDetails['commercial_resale']['construction_details'][$cd]['value'] = $details->propertyFeatures->carpet_area.' '.$area_unit[$details->propertyFeatures->carpet_area_unit];
                        $cd++;
                    }
                         
                    if(isset($details->propertyFeatures->sb_area) && isset($details->propertyFeatures->sb_area_unit))
                    {
                        $propertyDetails['commercial_resale']['construction_details'][$cd]['title'] = 'SB Area';
                        $propertyDetails['commercial_resale']['construction_details'][$cd]['value'] = $details->propertyFeatures->sb_area.' '.$area_unit[$details->propertyFeatures->sb_area_unit];
                        $cd++;
                    }
                    

                    if(isset($details->propertyFeatures->no_of_parking) && !empty($details->propertyFeatures->no_of_parking))
                    {
                        $propertyDetails['commercial_resale']['construction_details'][$cd]['title'] = 'No Of Parking';
                        $propertyDetails['commercial_resale']['construction_details'][$cd]['value'] = (string)$details->propertyFeatures->no_of_parking;
                        $cd++;
                    }
                   
                    if(isset($details->propertyFeatures->total_floors) && !empty($details->propertyFeatures->property_on_floor))
                    {
                        $propertyDetails['commercial_resale']['construction_details'][$cd]['title'] = 'Property On Floor';
                        $propertyDetails['commercial_resale']['construction_details'][$cd]['value'] = $details->propertyFeatures->property_on_floor.' Out Of '.$details->propertyFeatures->total_floors;
                        $cd++;
                    }

                    $cid=0;
                    if(isset($details->propertyFeatures->cabins) && !empty($details->propertyFeatures->cabins))
                    {
                        $propertyDetails['commercial_resale']['interior_details'][$cid]['title'] = 'No. Of Cabins';
                        $propertyDetails['commercial_resale']['interior_details'][$cid]['value'] = $details->propertyFeatures->cabins;
                        $cid++;
                    }
                    
                    if(isset($details->propertyFeatures->workstation) && !empty($details->propertyFeatures->workstation))
                    {
                        $propertyDetails['commercial_resale']['interior_details'][$cid]['title'] = 'No. Of Workstation';
                        $propertyDetails['commercial_resale']['interior_details'][$cid]['value'] = $details->propertyFeatures->workstation;
                        $cid++;
                    }
                    
                    if(isset($details->propertyFeatures->acs) && !empty($details->propertyFeatures->acs))
                    {
                        $propertyDetails['commercial_resale']['interior_details'][$cid]['title'] = 'No. Of Acs';
                        $propertyDetails['commercial_resale']['interior_details'][$cid]['value'] = $details->propertyFeatures->acs;
                        $cid++;
                    }

                    if(isset($details->propertyFeatures->interior_details) && !empty($details->propertyFeatures->interior_details))
                    {
                        $propertyDetails['commercial_resale']['interior_details'][$cid]['title'] = 'Interior Detail';
                        $propertyDetails['commercial_resale']['interior_details'][$cid]['value'] = $details->propertyFeatures->interior_details;
                        $cid++;
                    }
                    if(isset($details->propertyFeatures->furnished_status) && !empty($details->propertyFeatures->furnished_status))
                    {
                        $propertyDetails['commercial_resale']['interior_details'][$cid]['title'] = 'Furnished Status';
                        $propertyDetails['commercial_resale']['interior_details'][$cid]['value'] = $details->propertyFeatures->furnished_status;
                        $cid++;
                    }

                    // Price details
                    $propertyDetails['commercial_resale']['price_details'] = \Helpers::getResalePriceDetails($details);
                }

                // Industrial resale data
                $propertyDetails['industrial_resale'] = null;
                if(isset($details->cat_id) && $details->cat_id == '3')
                {   
                    // Construction details
                    $cd = 0;
                    if(isset($details->propertyFeatures->shed_height) && isset($details->propertyFeatures->shed_height_unit))
                    {
                        $propertyDetails['industrial_resale']['construction_details'][$cd]['title'] = 'Shed Height';
                        $propertyDetails['industrial_resale']['construction_details'][$cd]['value'] = $details->propertyFeatures->shed_height.' '.$area_unit[$details->propertyFeatures->shed_height_unit];
                        $cd++;
                    }
                    
                    if(isset($details->propertyFeatures->shed_area) && isset($details->propertyFeatures->shed_area_unit))
                    {
                        $propertyDetails['industrial_resale']['construction_details'][$cd]['title'] = 'Shed Area';
                        $propertyDetails['industrial_resale']['construction_details'][$cd]['value'] = $details->propertyFeatures->shed_area.' '.$area_unit[$details->propertyFeatures->shed_area_unit];
                        $cd++;
                    }

                    if(isset($details->propertyFeatures->plot_area) && isset($details->propertyFeatures->plot_area_unit))
                    {
                        $propertyDetails['industrial_resale']['construction_details'][$cd]['title'] = 'Plot Area';
                        $propertyDetails['industrial_resale']['construction_details'][$cd]['value'] = $details->propertyFeatures->plot_area.' '.$area_unit[$details->propertyFeatures->plot_area_unit];
                        $cd++;
                    }

                    // Approach details
                    $propertyDetails['industrial_resale']['approach_details'] = \Helpers::getRoadApproachDetails($details);


                    // Price details
                    $propertyDetails['industrial_resale']['price_details'] = \Helpers::getResalePriceDetails($details);

                    // Other Details
                    $od  = 0;
                    if(isset($details->sites->water_supply) && !empty($details->sites->water_supply))
                    {
                        $propertyDetails['industrial_resale']['other_details'][$od]['title'] = 'Water Facility';
                        $propertyDetails['industrial_resale']['other_details'][$od]['value'] = $details->sites->water_supply == '1' ? 'Yes' : 'No';
                        $od++;
                    }

                    if(isset($details->propertyFeatures->electricity_connection) && !empty($details->propertyFeatures->electricity_connection))
                    {
                        $propertyDetails['industrial_resale']['other_details'][$od]['title'] = 'Electricity connection';
                        $propertyDetails['industrial_resale']['other_details'][$od]['value'] = ucfirst($details->propertyFeatures->electricity_connection);
                        $od++;
                    }

                    if(isset($details->propertyFeatures->power_capacity) && !empty($details->propertyFeatures->power_capacity))
                    {
                        $propertyDetails['industrial_resale']['other_details'][$od]['title'] = 'Power Capacity';
                        $propertyDetails['industrial_resale']['other_details'][$od]['value'] = $details->propertyFeatures->power_capacity.' hp';
                        $od++;
                    }

                    if(isset($details->propertyFeatures->crane_facility) && !empty($details->propertyFeatures->crane_facility))
                    {
                        $propertyDetails['industrial_resale']['other_details'][$od]['title'] = 'Crane Facility';
                        $propertyDetails['industrial_resale']['other_details'][$od]['value'] = ucfirst($details->propertyFeatures->crane_facility);
                        $od++;
                    }

                    if(isset($details->propertyFeatures->etp) && !empty($details->propertyFeatures->etp))
                    {
                        $propertyDetails['industrial_resale']['other_details'][$od]['title'] = 'ETP';
                        $propertyDetails['industrial_resale']['other_details'][$od]['value'] = ucfirst($details->propertyFeatures->etp);
                        $od++;
                    }
                }

                // Land new resale data
                $propertyDetails['land_new_resale'] = null;
                if(isset($details->cat_id) && $details->cat_id == '4')
                {
                    // Layout details
                    $a = 0;
                    if(isset($details->propertyFeatures->length) && isset($details->propertyFeatures->width))
                    {
                        $propertyDetails['land_new_resale']['layout_details'][$a]['title'] = 'Size';
                        $propertyDetails['land_new_resale']['layout_details'][$a]['value'] = $details->propertyFeatures->length.' * '.$details->propertyFeatures->width.' '.$area_unit[$details->propertyFeatures->length_width_unit];
                        $a++;
                    }

                    if(isset($details->propertyFeatures->frontage) && !empty($details->propertyFeatures->frontage))
                    {
                        $propertyDetails['land_new_resale']['layout_details'][$a]['title'] = 'Frontage';
                        $propertyDetails['land_new_resale']['layout_details'][$a]['value'] = $details->propertyFeatures->frontage.' '.$area_unit[$details->propertyFeatures->frontage_unit];
                        $a++;
                    }

                    if(isset($details->propertyFeatures->facing) && !empty($details->propertyFeatures->facing))
                    {
                        $propertyDetails['land_new_resale']['layout_details'][$a]['title'] = 'Facing';
                        $propertyDetails['land_new_resale']['layout_details'][$a]['value'] = $details->propertyFeatures->facing;
                        $a++;
                    }

                    if(isset($details->propertyFeatures->area_covered) && !empty($details->propertyFeatures->area_covered))
                    {
                        $propertyDetails['land_new_resale']['layout_details'][$a]['title'] = 'Total Size';
                        $propertyDetails['land_new_resale']['layout_details'][$a]['value'] =  $details->propertyFeatures->area_covered.' '.$area_unit[$details->propertyFeatures->area_covered_unit];
                        $a++;   
                    }

                    // Location details
                    $b = 0;
                    if(isset($details->village) && !empty($details->village))
                    {
                        $propertyDetails['land_new_resale']['location_details'][$b]['title'] = 'Village Name';
                        $propertyDetails['land_new_resale']['location_details'][$b]['value'] = $details->village;
                        $b++;   
                    }

                    if(isset($details->fp_no) && !empty($details->fp_no))
                    {
                        $propertyDetails['land_new_resale']['location_details'][$b]['title'] = 'Fp No';
                        $propertyDetails['land_new_resale']['location_details'][$b]['value'] = $details->fp_no;
                        $b++;   
                    }
                    
                    if(isset($details->survey_no) && !empty($details->survey_no))
                    {
                        $propertyDetails['land_new_resale']['location_details'][$b]['title'] = 'Survey No';
                        $propertyDetails['land_new_resale']['location_details'][$b]['value'] = $details->survey_no;
                        $b++;   
                    }
                    
                    if(isset($details->tp_scheme_no) && !empty($details->tp_scheme_no)) 
                    {
                        $propertyDetails['land_new_resale']['location_details'][$b]['title'] = 'Tp Scheme No';
                        $propertyDetails['land_new_resale']['location_details'][$b]['value'] = isset($details->tp_scheme_no) ? $details->tp_scheme_no : '';
                        $b++;
                    }
                     
                    // Approach details
                    $propertyDetails['land_new_resale']['approach_details'] = \Helpers::getRoadApproachDetails($propertyDetailsData);

                    // Price details
                    $propertyDetails['land_new_resale']['price_details'] = \Helpers::getResalePriceDetails($propertyDetailsData);

                    // Other details
                    $d=0;

                    if(isset($details->propertyMetas) && $details->propertyMetas->count()>0)
                    {
                        foreach ($details->propertyMetas as $metaKey => $metaVal) 
                        {
                            if($metaVal->meta_key == 'land_zone')
                            {
                                $propertyDetails['land_new_resale']['other_details'][$d]['title'] = 'Declared Zone'; 
                                $propertyDetails['land_new_resale']['other_details'][$d]['value'] = isset($metaVal->meta_value) ? ucfirst($metaVal->meta_value) : '';
                                $d++;
                            }

                            if($metaVal->meta_key == 'land_ideal')
                            {
                                $propertyDetails['land_new_resale']['other_details'][$d]['title'] = 'Land Ideal'; 
                                $propertyDetails['land_new_resale']['other_details'][$d]['value'] = isset($metaVal->meta_value) ? ucfirst($metaVal->meta_value) : '';
                                $d++;
                            }

                            if($metaVal->meta_key == 'no_of_owners')
                            {
                                $propertyDetails['land_new_resale']['other_details'][$d]['title'] = 'No Of Owners'; 
                                $propertyDetails['land_new_resale']['other_details'][$d]['value'] = isset($metaVal->meta_value) ? ucfirst($metaVal->meta_value) : '';
                                $d++;
                            }
                        }
                    }
                    
                    if(isset($details->propertyFeatures->usp) && !empty($details->propertyFeatures->usp))
                    {
                        $propertyDetails['land_new_resale']['other_details'][$d]['title'] = 'USP';
                        $propertyDetails['land_new_resale']['other_details'][$d]['value'] = $details->propertyFeatures->usp;
                        $d++;   
                    }
                }
                
                $propertyDetails['property_id'] = isset($details->id) && !empty($details->id) ? (string)$details->id : '';

                $propertyDetails['code'] = isset($details->code) && !empty($details->code) ? (string)$details->code : null;
               
                $propertyDetails['property_category'] = isset($details->cat_id) && !empty($details->cat_id) ? (string)$details->cat_id : '';
                
                $propertyDetails['property_sub_category'] = isset($details->sub_cat_id) ? (string)$details->sub_cat_id : '';

                $propertyDetails['property_cover_image'] = Helpers::getCoveredImage($details->sites);

                
                $propertyDetails['is_commission'] = isset($details->is_commission) && !empty($details->is_commission) ? (string)$details->is_commission : '';

                $propertyDetails['commission_percent'] = isset($details->commission_percent) && !empty($details->commission_percent) ? (string)$details->commission_percent : '';
                $propertyDetails['commission_basic_price'] = isset($details->commission_basic_price) && !empty($details->commission_basic_price) ? (string)$details->commission_basic_price : '';

                $propertyDetails['commission_amount'] = isset($details->commission_amount) && !empty($details->commission_amount) ? (string)$details->commission_amount : '';
                
                $propertyDetails['package'] = isset($details->package) && !empty($details->package) ? (string)$details->package : '';
                
                $propertyDetails['brokrage_type'] = isset($details->brokrage_type) && !empty($details->brokrage_type) ? (string)$details->brokrage_type : '';
               
                $propertyDetails['fix_pay_type'] = isset($details->fix_pay_type) && !empty($details->fix_pay_type) ? (string)$details->fix_pay_type : '';
                
                $propertyDetails['fix_pay_amount'] = isset($details->fix_pay_amount) && !empty($details->fix_pay_amount) ? (string)$details->fix_pay_amount : '';
                

                $propertyDetails['is_escalation'] = isset($details->is_escalation) && !empty($details->is_escalation) ? (string)$details->is_escalation : '';
                $propertyDetails['is_garden_facing'] = isset($details->is_garden_facing) && !empty($details->is_escalation) ? (string)$details->is_garden_facing : '';
                $propertyDetails['garden_facing_amount'] = isset($details->garden_facing_amount) && !empty($details->garden_facing_amount) ? (string)$details->garden_facing_amount : '';
                
                $propertyDetails['is_club_house_facing'] = isset($details->is_club_house_facing) && !empty($details->is_club_house_facing) ? (string)$details->is_club_house_facing : '';
                
                $propertyDetails['club_house_facing_amount'] = isset($details->club_house_facing_amount) && !empty($details->club_house_facing_amount) ? (string)$details->club_house_facing_amount : '';
                
                $propertyDetails['id_road_facing'] = isset($details->id_road_facing) && !empty($details->id_road_facing) ? (string)$details->id_road_facing : '';
                
                $propertyDetails['road_facing_amount'] = isset($details->road_facing_amount) && !empty($details->road_facing_amount) ? (string)$details->road_facing_amount : '';
                
                $propertyDetails['is_croner_flat'] = isset($details->is_croner_flat) && !empty($details->is_croner_flat) ? (string)$details->is_croner_flat : '';
                
                $propertyDetails['corner_flat_amount'] = isset($details->corner_flat_amount) && !empty($details->corner_flat_amount) ? (string)$details->corner_flat_amount : '';
                $propertyDetails['is_others'] = isset($details->is_others) && !empty($details->is_others) ? (string)$details->is_others : '';
                
                $propertyDetails['other_amount'] = isset($details->other_amount) && !empty($details->other_amount) ? (string)$details->other_amount : '';



                $propertyDetails['transaction_type'] = isset($details->transaction_type) && $details->transaction_type == '1' ? 'Sale' : 'Rent';
                
                $propertyDetails['site_name'] = isset($details->sites->site_name) ? $details->sites->site_name : '';
               

                $propertyDetails['site_description'] = isset($details->sites->description) ? $details->sites->description : '';
                $propertyDetails['site_address'] = isset($details->sites->address) ? $details->sites->address : '';
                $propertyDetails['site_area'] = isset($details->sites->areas) ? $details->sites->areas->name : '';
                $propertyDetails['site_city'] = isset($details->sites->cities) ? $details->sites->cities->name : '';
                $propertyDetails['site_state'] = isset($details->sites->states) ? $details->sites->states->name : '';
                //$propertyDetails['site_price'] = Helpers::getSitePriceApi($details->sites,$request->userType);
                $propertyDetails['site_price'] = isset($details->price) ? $details->price : ''; 

                $propertyDetails['usp_one'] = isset($details->usp_one) ? $details->usp_one : '';
                $propertyDetails['usp_two'] = isset($details->usp_two) ? $details->usp_two : '';
                $propertyDetails['usp_three'] = isset($details->usp_three) ? $details->usp_three : '';
                $propertyDetails['usp_four'] = isset($details->usp_four) ? $details->usp_four : '';
                $propertyDetails['usp_five'] = isset($details->usp_five) ? $details->usp_five : '';
                $propertyDetails['usp_six'] = isset($details->usp_six) ? $details->usp_six : '';
                $propertyDetails['usp_seven'] = isset($details->usp_seven) ? $details->usp_seven : '';
                $propertyDetails['usp_eight'] = isset($details->usp_eight) ? $details->usp_eight : ''; 

                
                
                $propertyDetails['rera_details']['rere_no'] = isset($details->sites->rera_no) && !empty($details->sites->rera_no) ? $details->sites->rera_no : '';
                

                $propertyDetails['rera_details']['rera_certificate'] = isset($details->sites->rera_certificate) && !empty($details->sites->rera_certificate) ? 'http://demo.coupon4you.us/public/'.$details->sites->rera_certificate : '';
                
                $propertyDetails['site_brochure'] = isset($details->sites->brochure) && !empty($details->sites->brochure) ? 'http://demo.coupon4you.us/public/'.$details->sites->brochure : '';

                


                $propertyDetails['possession'] = Helpers::getSitePossesionDetails($details->sites);


                
                $propertyDetails['no_of_towers'] = isset($details->propertyFeatures->no_of_towers) && !empty($details->propertyFeatures->no_of_towers) ? (string)$details->propertyFeatures->no_of_towers : '';
                
                $propertyDetails['no_of_flats'] = isset($details->propertyFeatures->no_of_flats) && !empty($details->propertyFeatures->no_of_flats) ? (string)$details->propertyFeatures->no_of_flats : '';

                $propertyDetails['total_units'] = isset($details->propertyFeatures->total_unit) && !empty($details->propertyFeatures->total_unit) ? (string)$details->propertyFeatures->total_unit : '';
                 
                $propertyDetails['open_sides'] = isset($details->propertyFeatures->open_sides) && !empty($details->propertyFeatures->open_sides) ? $details->propertyFeatures->open_sides : '';

                
                $propertyDetails['age_of_construction'] = isset($details->sites->age_of_construction) && !empty($details->sites->age_of_construction) ? $details->sites->age_of_construction : '';
                

                $propertyDetails['land_size'] = isset($details->propertyFeatures->area_covered) && isset($details->propertyFeatures->area_covered_unit) ? $details->propertyFeatures->area_covered.' '.$area_unit[$details->propertyFeatures->area_covered_unit] : '';

                $propertyDetails['commercial_ideal_for'] = isset($details->propertyFeatures->ideal_for) && !empty($details->propertyFeatures->ideal_for) ? $details->propertyFeatures->ideal_for : '';

                $propertyDetails['land_price_per_unit_title'] = '';
                $propertyDetails['land_price_per_unit_value'] = '';
                if(isset($details->propertyFeatures->price_sq_ft) && !empty($details->propertyFeatures->price_sq_ft))
                {
                    $propertyDetails['land_price_per_unit_title'] = isset($details->propertyFeatures->area_covered_unit) && !empty($details->propertyFeatures->area_covered_unit) ? 'Price Per '.strtoupper($details->propertyFeatures->area_covered_unit) : 'Price Per Sq. Ft.';
                    $propertyDetails['land_price_per_unit_value'] = Helpers::getPrettyNumber($details->propertyFeatures->price_sq_ft).'/'.strtoupper($details->propertyFeatures->area_covered_unit);
                }

                
                $propertyDetails['land_type'] = '';
                foreach ($details->propertyMetas as $metaKey => $metaVal) 
                {
                    if($metaVal->meta_key == 'land_ideal')
                    {
                        $propertyDetails['land_type'] = isset($metaVal->meta_value) ? ucfirst($metaVal->meta_value) : '';
                    }
                }

                $propertyDetails['plot_area'] = isset($details->propertyFeatures->plot_area) && !empty($details->propertyFeatures->plot_area_unit) ? $details->propertyFeatures->plot_area.' '.$area_unit[$details->propertyFeatures->plot_area_unit] : '';



                $propertyDetails['property_code'] = isset($details->code) && !empty($details->code) ? $details->code : ''; 
               
                if(isset($details->propertyCategory->name) && $details->PropertyCategory->id == '1')
                { 
                    
                    $config = Helpers::getResidentialInfoBar($details);
                    
                    

                    $propertyDetails['configurations'] = isset($config[$propertyDetail[5]]['bhk']) ? $config[$propertyDetail[5]]['bhk'] : '';
                    $propertyDetails['area'] = isset($config[$propertyDetail[5]]['area']) && isset($config[$propertyDetail[5]]['area_unit']) ? $config[$propertyDetail[5]]['area'].' '.$config[$propertyDetail[5]]['area_unit'] : '';
                    $propertyDetails['area_type'] = isset($config[$propertyDetail[5]]['area_type']) && !empty($config[$propertyDetail[5]]['area_type']) ? $config[$propertyDetail[5]]['area_type'] : 'Area';

                     
                }
              
                if(isset($details->propertyCategory->name) && $details->PropertyCategory->id == '2')
                {       
                    $propertyDetails['area'] = isset($details->propertyFeatures->carpet_area) && !empty($details->propertyFeatures->carpet_area) && isset($details->propertyFeatures->carpet_area_unit) && !empty($details->propertyFeatures->carpet_area_unit) ? $details->propertyFeatures->carpet_area.' '.$details->propertyFeatures->carpet_area_unit : 'Not Available';
                    $propertyDetails['area_type'] = 'Carpet Area';
                }
                
                // Get all site images for edit
                
                
                $arrPhotoThumb = Helpers::getSiteGalleryImages($details);


                $propertyDetails['gallery'] = [];
                $propertyDetails['image_type'] =[];
                if(isset($arrPhotoThumb['images']) && count($arrPhotoThumb['images']) > 0)
                {
                    $i = 0; 
                    krsort($arrPhotoThumb['images']);

                    foreach ($arrPhotoThumb['images'] as $key => $propertyThumbs)
                    {   
                        $propertyDetails['image_type'][$i] = $arrPhotoThumb['image_type'][$key];
                        
                        $i++;
                    }

                    $k = 0;
                    foreach ($arrPhotoThumb['images'] as $key => $propertyThumbs)
                    {   $j = 0;

                        foreach ($propertyThumbs as $images)
                        {   //echo $images['thumb'];
                            $propertyDetails['gallery'][$k][$j] = $images['thumb'];
                            $j++;
                        }$k++;

                    }
                }

                /*$propertyDetails['site_images'] = [];
                if(isset($arrPhotoThumb['images']) && count($arrPhotoThumb['images']) > 0)
                {
                    $i = 0; 
                    krsort($arrPhotoThumb['images']);

                    foreach ($arrPhotoThumb['images'] as $key => $propertyThumbs)
                    {
                        $propertyDetails['image_type'][$i] = $arrPhotoThumb['image_type'][$key];
                        $i++;
                    }

                    $k = 0;
                    foreach ($arrPhotoThumb['images'] as $key => $propertyThumbs)
                    {   $j = 0;
                        foreach ($propertyThumbs as $images)
                        {   //echo $images['thumb'];
                            $propertyDetails['site_images'][$k][$j] = $images['thumb'];
                            $j++;
                        }$k++;
                    }
                }*/

                // 360 Image View
                $propertyDetails['sample_house_360_link'] = "";
                if(isset($details->sites->sample_house_360_link) && $details->sites->sample_house_360_link != '')
                {
                    $propertyDetails['sample_house_360_link'] = $details->sites->sample_house_360_link;
                }
                 
                // Sample house video
                $propertyDetails['sample_house_video_link'] = "";
                if(isset($details->sites->sample_house_video_link) && $details->sites->sample_house_video_link != '')
                {
                    $propertyDetails['sample_house_video_link'] = $details->sites->sample_house_video_link;
                }   


                // Property Video
                $propertyDetails['property_video_link'] = "";
                if(isset($propertyDetailsData->video_link) && $propertyDetailsData->video_link != '')
                {
                    $propertyDetails['property_video_link'] = $propertyDetailsData->video_link;
                }

               
                 
                // Commertial property data
                if(isset($details->propertyCategory->id) && $details->propertyCategory->id == '2')
                {
                    
                    $propertyDetails['water_supply'] = isset($details->water_supply) && $details->water_supply == '0' ? 'No' : 'Yes';
                    $propertyDetails['power_backup'] = isset($details->power_backup) && $details->power_backup == '0' ? 'No' : 'Yes';
                    $propertyDetails['total_floors'] = isset($details->propertyFeatures->total_floors) ? (string)$details->propertyFeatures->total_floors : '';
                    $propertyDetails['parking_area'] = isset($details->propertyFeatures->parking_area) ? $details->propertyFeatures->parking_area : '';
                }
                
                // Property Configuration
                $propertyDetails['property_config'] = null;
                         
                $propertyConfig = Helpers::newPropertyConfigApi($details,$request);

                if(isset($propertyConfig) && !empty($propertyConfig))
                {
                    $propertyDetails['property_config'] = $propertyConfig;
                }
                 
                // Set empty array for other category  
                /*if(isset($propertyDetail[5]) && !empty($propertyDetail[5]))
                {   
                    if($propertyDetail[5] != 'residential')
                    {
                        $propertyDetails['property_config']['residential'] = [];
                    }
                    
                    if($propertyDetail[5] != 'commercial')
                    {
                        $propertyDetails['property_config']['commercial'] = [];
                    }   
                    
                    if($propertyDetail[5] != 'industrial')
                    {
                        $propertyDetails['property_config']['industrial'] = [];
                    }

                    if($propertyDetail[5] != 'land')
                    {
                        $propertyDetails['property_config']['land'] = [];
                    }
                }*/
                
                // Bank offers
                $propertyDetails['bank_offers'] = [];
                if(isset($details->sites->siteLoans) && count($details->sites->siteLoans) > 0)
                {   
                    $bankData = [];$i=0;
                    foreach($details->sites->siteLoans as $loan)
                    {
                        
                        $loanData =  Helpers::getBankDetails($loan->bank_id);
                        
                        if($loanData)
                        {    
                            $bankData[$i]['bank_image'] = 'http://demo.coupon4you.us/public/'.$loanData->bank_logo;
                            $bankData[$i]['bank_interest_rate'] = 'Interest Rate '.$loanData->interest_rate.' %';
                        }
                        $i++;
                    }

                    // Add bank data into property details
                    $propertyDetails['bank_offers'] = $bankData;
                }
                
                // Get aminities
                $propertyDetails['amenities'] = [];
                $amenities = null;
                if(isset($details->sites->siteMetas))
                {
                    if( Helpers::chkAmenity($details->sites->siteMetas, 'water_supply', '1',true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'power_backup', '1', true) == true ||
                        Helpers::chkAmenity($details->sites->siteMetas, 'lift', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'garden', '1', true) == true ||
                        Helpers::chkAmenity($details->sites->siteMetas, 'security_facility', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'parking_area', '1', true) == true ||
                        Helpers::chkAmenity($details->sites->siteMetas, 'children_play_area', '1', true) == true ||
                        Helpers::chkAmenity($details->sites->siteMetas, 'restaurant', '1', true) == true ||
                        Helpers::chkAmenity($details->sites->siteMetas, 'gas_line', '1', true) == true ||
                        Helpers::chkAmenity($details->sites->siteMetas, 'cctv', '1', true) == true ||
                        Helpers::chkAmenity($details->sites->siteMetas, 'internal_road', '1', true) == true ||
                        Helpers::chkAmenity($details->sites->siteMetas, 'video_door_phone', '1', true) == true ||
                        Helpers::chkAmenity($details->sites->siteMetas, 'washing_machine_area', '1', true) == true ||
                        Helpers::chkAmenity($details->sites->siteMetas, 'library', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'internet', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'intercom', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'rainwater_harvest', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'unity_stores', '1', true) == true ||
                        Helpers::chkAmenity($details->sites->siteMetas, 'swimming_pool', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'infinity_swimming_pool', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'volleyball', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'badminton', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'golf', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'tennis', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'squash', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'yoga', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'gazebo', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'banquet_hall', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'amphi_theatre', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'gymasium', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'indoor_game_court', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'outdoor_game_court', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'joggers_park', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'butterfly_park', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'temple', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'senior_citizen_garden', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'wifi', '1', true) == true || 
                        Helpers::chkAmenity($details->sites->siteMetas, 'relaxation_room', '1', true) == true)
                    {
                        // Create array to store amenities which is added
                        $propertyDetails['amenities'] = [];
                        $amenities['display_amenities'] = [];
                        $amenities['dropdown_amenities'] = [];

                        // Create array store amenities which is not added 
                        $notAddedAmenities = [];

                        $waterSupply = [];
                        //if($propertyDetailsData->water_supply == 1)   
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'water_supply', '1', true) == true)
                        {
                            $waterSupply['amenity_key'] = "water_supply";
                            $waterSupply['amenity_value'] = "Water supply";
                            $amenities['display_amenities'][] = $waterSupply;
                        }
                        else
                        {
                            $waterSupply['amenity_key'] = "water_supply";
                            $waterSupply['amenity_value'] = "Water supply";
                            $amenities['dropdown_amenities'][] = $waterSupply;
                        }

                        $powerBackup = [];
                        //if($propertyDetailsData->power_backup == 1)
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'power_backup', '1', true) == true)
                        {
                            $powerBackup['amenity_key'] = "power_backup";
                            $powerBackup['amenity_value'] = "Power backup";
                            $amenities['display_amenities'][] = $powerBackup;
                        }
                        else
                        {
                            $powerBackup['amenity_key'] = "power_backup";
                            $powerBackup['amenity_value'] = "Power backup";
                            $amenities['dropdown_amenities'][] = $powerBackup;
                        }

                        $lift = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'lift', '1', true) == true)
                        {
                            $lift['amenity_key'] = "lift";
                            $lift['amenity_value'] = "Elevator/Lift";
                            $amenities['display_amenities'][] = $lift;
                        }
                        else
                        {
                            $lift['amenity_key'] = "lift";
                            $lift['amenity_value'] = "Elevator/Lift";
                            $amenities['dropdown_amenities'][] = $lift;
                        }

                        $garden = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'garden', '1', true) == true)
                        {
                            $garden['amenity_key'] = "garden";
                            $garden['amenity_value'] = "Garden";
                            $amenities['display_amenities'][] = $garden;
                        }
                        else
                        {
                            $garden['amenity_key'] = "garden";
                            $garden['amenity_value'] = "Garden";
                            $amenities['dropdown_amenities'][] = $garden;
                        }

                        $securityFacility = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'security_facility', '1', true) == true)
                        {
                            $securityFacility['amenity_key'] = "security_facility";
                            $securityFacility['amenity_value'] = "Security facility";
                            $amenities['display_amenities'][] = $securityFacility;
                        }
                        else
                        {
                            $securityFacility['amenity_key'] = "security_facility";
                            $securityFacility['amenity_value'] = "Security facility";
                            $amenities['dropdown_amenities'][] = $securityFacility;
                        }

                        $parkingArea = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'parking_area', '1', true) == true)
                        {
                            $parkingArea['amenity_key'] = "parking_area";
                            $parkingArea['amenity_value'] = "Parking Area";
                            $amenities['display_amenities'][] = $parkingArea;
                        }
                        else
                        {
                            $parkingArea['amenity_key'] = "parking_area";
                            $parkingArea['amenity_value'] = "Parking Area";
                            $amenities['dropdown_amenities'][] = $parkingArea;
                        }

                        $childrenPlayArea = [];
                        if (Helpers::chkAmenity($details->sites->siteMetas, 'children_play_area', '1', true) == true)
                        {
                            $childrenPlayArea['amenity_key'] = "children_play_area";
                            $childrenPlayArea['amenity_value'] = "Children play area";
                            $amenities['display_amenities'][] = $childrenPlayArea;
                        }
                        else
                        {
                            $childrenPlayArea['amenity_key'] = "children_play_area";
                            $childrenPlayArea['amenity_value'] = "Children play area";
                            $amenities['dropdown_amenities'][] = $childrenPlayArea;
                        }

                        $restaurant = [];
                        if (Helpers::chkAmenity($details->sites->siteMetas, 'restaurant', '1', true) == true)
                        {
                            $restaurant['amenity_key'] = "restaurant";
                            $restaurant['amenity_value'] = "Restaurant";
                            $amenities['display_amenities'][] = $restaurant;
                        }
                        else
                        {
                            $restaurant['amenity_key'] = "restaurant";
                            $restaurant['amenity_value'] = "Restaurant";
                            $amenities['dropdown_amenities'][] = $restaurant;
                        }

                        $gasLine = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'gas_line', '1', true) == true)
                        {
                            $gasLine['amenity_key'] = "gas_line";
                            $gasLine['amenity_value'] = "Gas Line";
                            $amenities['display_amenities'][] = $gasLine;
                        }
                        else
                        {
                            $gasLine['amenity_key'] = "gas_line";
                            $gasLine['amenity_value'] = "Gas Line";
                            $amenities['dropdown_amenities'][] = $gasLine;
                        }

                        $cctv = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'cctv', '1', true) == true)
                        {
                            $cctv['amenity_key'] = "cctv";
                            $cctv['amenity_value'] = "CCTV";
                            $amenities['display_amenities'][] = $cctv;
                        }
                        else
                        {
                            $cctv['amenity_key'] = "cctv";
                            $cctv['amenity_value'] = "CCTV";
                            $amenities['dropdown_amenities'][] = $cctv;
                        }

                        $internalRoad = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'internal_road', '1', true) == true)
                        {
                            $internalRoad['amenity_key'] = "internal_road";
                            $internalRoad['amenity_value'] = "Internal Road";
                            $amenities['display_amenities'][] = $internalRoad;
                        }
                        else
                        {
                            $internalRoad['amenity_key'] = "internal_road";
                            $internalRoad['amenity_value'] = "Internal Road";
                            $amenities['dropdown_amenities'][] = $internalRoad;
                        }

                        $videoDoorPhone = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'video_door_phone', '1', true) == true)
                        {
                            $videoDoorPhone['amenity_key'] = "video_door_phone";
                            $videoDoorPhone['amenity_value'] = "Video door phone";
                            $amenities['display_amenities'][] = $videoDoorPhone;
                        }
                        else
                        {
                            $videoDoorPhone['amenity_key'] = "video_door_phone";
                            $videoDoorPhone['amenity_value'] = "Video door phone";
                            $amenities['dropdown_amenities'][] = $videoDoorPhone;
                        }

                        $washingMachineArea = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'washing_machine_area', '1', true) == true)
                        {
                            $washingMachineArea['amenity_key'] = "washing_machine_area";
                            $washingMachineArea['amenity_value'] = "Washing machine area";
                            $amenities['display_amenities'][] = $washingMachineArea;
                        }
                        else
                        {
                            $washingMachineArea['amenity_key'] = "washing_machine_area";
                            $washingMachineArea['amenity_value'] = "Washing machine area";
                            $amenities['dropdown_amenities'][] = $washingMachineArea;
                        }

                        $library = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'library', '1', true) == true)
                        {
                            $library['amenity_key'] = "library";
                            $library['amenity_value'] = "Library";
                            $amenities['display_amenities'][] = $library;
                        }
                        else
                        {
                            $library['amenity_key'] = "library";
                            $library['amenity_value'] = "Library";
                            $amenities['dropdown_amenities'][] = $library;
                        }                   

                        $internet = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'internet', '1', true) == true)
                        {
                            $internet['amenity_key'] = "internet";
                            $internet['amenity_value'] = "Internet";
                            $amenities['display_amenities'][] = $internet;
                        }
                        else
                        {
                            $internet['amenity_key'] = "internet";
                            $internet['amenity_value'] = "Internet";
                            $amenities['dropdown_amenities'][] = $internet;
                        }

                        $intercom = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'intercom', '1', true) == true)
                        {
                            $intercom['amenity_key'] = "intercom";
                            $intercom['amenity_value'] = "Intercom";
                            $amenities['display_amenities'][] = $intercom;
                        }
                        else
                        {
                            $intercom['amenity_key'] = "intercom";
                            $intercom['amenity_value'] = "Intercom";
                            $amenities['dropdown_amenities'][] = $intercom;
                        }       

                        $rainwaterHarvest = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'rainwater_harvest', '1', true) == true)
                        {
                            $rainwaterHarvest['amenity_key'] = "rainwater_harvest";
                            $rainwaterHarvest['amenity_value'] = "Intercom";
                            $amenities['display_amenities'][] = $rainwaterHarvest;
                        }
                        else
                        {
                            $rainwaterHarvest['amenity_key'] = "rainwater_harvest";
                            $rainwaterHarvest['amenity_value'] = "Intercom";
                            $amenities['dropdown_amenities'][] = $rainwaterHarvest;
                        }

                        $unityStores = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'unity_stores', '1', true) == true)
                        {
                            $unityStores['amenity_key'] = "unity_stores";
                            $unityStores['amenity_value'] = "Unity stores";
                            $amenities['display_amenities'][] = $unityStores;
                        }
                        else
                        {
                            $unityStores['amenity_key'] = "unity_stores";
                            $unityStores['amenity_value'] = "Unity stores";
                            $amenities['dropdown_amenities'][] = $unityStores;
                        }

                        $swimmingPool = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'swimming_pool', '1', true) == true)
                        {
                            $swimmingPool['amenity_key'] = "swimming_pool";
                            $swimmingPool['amenity_value'] = "Swimming Pool";
                            $amenities['display_amenities'][] = $swimmingPool;
                        }
                        else
                        {
                            $swimmingPool['amenity_key'] = "swimming_pool";
                            $swimmingPool['amenity_value'] = "Swimming Pool";
                            $amenities['dropdown_amenities'][] = $swimmingPool;
                        }

                        $infinitySwimmingPool = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'infinity_swimming_pool', '1', true) == true)
                        {
                            $infinitySwimmingPool['amenity_key'] = "infinity_swimming_pool";
                            $infinitySwimmingPool['amenity_value'] = "Infinity Swimming Pool";
                            $amenities['display_amenities'][] = $infinitySwimmingPool;
                        }
                        else
                        {
                            $infinitySwimmingPool['amenity_key'] = "infinity_swimming_pool";
                            $infinitySwimmingPool['amenity_value'] = "Infinity Swimming Pool";
                            $amenities['dropdown_amenities'][] = $infinitySwimmingPool;
                        }

                        $volleyball = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'volleyball', '1', true) == true)
                        {
                            $volleyball['amenity_key'] = "volleyball";
                            $volleyball['amenity_value'] = "Volleyball";
                            $amenities['display_amenities'][] = $volleyball;
                        }
                        else
                        {
                            $volleyball['amenity_key'] = "volleyball";
                            $volleyball['amenity_value'] = "Volleyball";
                            $amenities['dropdown_amenities'][] = $volleyball;
                        }

                        $badminton = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'badminton', '1', true) == true)
                        {
                            $badminton['amenity_key'] = "badminton";
                            $badminton['amenity_value'] = "Badminton";
                            $amenities['display_amenities'][] = $badminton;
                        }
                        else
                        {
                            $badminton['amenity_key'] = "badminton";
                            $badminton['amenity_value'] = "Badminton";
                            $amenities['dropdown_amenities'][] = $badminton;
                        }   

                        $golf = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'golf', '1', true) == true)
                        {
                            $golf['amenity_key'] = "golf";
                            $golf['amenity_value'] = "Golf";
                            $amenities['display_amenities'][] = $golf;
                        }
                        else
                        {
                            $golf['amenity_key'] = "golf";
                            $golf['amenity_value'] = "Golf";
                            $amenities['dropdown_amenities'][] = $golf;
                        }

                        $tennis = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'tennis', '1', true) == true)
                        {
                            $tennis['amenity_key'] = "tennis";
                            $tennis['amenity_value'] = "Tennis";
                            $amenities['display_amenities'][] = $tennis;
                        }
                        else
                        {
                            $tennis['amenity_key'] = "tennis";
                            $tennis['amenity_value'] = "Tennis";
                            $amenities['dropdown_amenities'][] = $tennis;
                        }

                        $squash = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'squash', '1', true) == true)
                        {
                            $squash['amenity_key'] = "squash";
                            $squash['amenity_value'] = "Squash";
                            $amenities['display_amenities'][] = $squash;
                        }
                        else
                        {
                            $squash['amenity_key'] = "squash";
                            $squash['amenity_value'] = "Squash";
                            $amenities['dropdown_amenities'][] = $squash;
                        }

                        $yoga = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'yoga', '1', true) == true)
                        {
                            $yoga['amenity_key'] = "yoga";
                            $yoga['amenity_value'] = "Yoga";
                            $amenities['display_amenities'][] = $yoga;
                        }
                        else
                        {
                            $yoga['amenity_key'] = "yoga";
                            $yoga['amenity_value'] = "Yoga";
                            $amenities['dropdown_amenities'][] = $yoga;
                        }

                        $gazebo = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'gazebo', '1', true) == true)
                        {
                            $gazebo['amenity_key'] = "gazebo";
                            $gazebo['amenity_value'] = "Gazebo";
                            $amenities['display_amenities'][] = $gazebo;
                        }
                        else
                        {
                            $gazebo['amenity_key'] = "gazebo";
                            $gazebo['amenity_value'] = "Gazebo";
                            $amenities['dropdown_amenities'][] = $gazebo;
                        }

                        $banquetHall = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'banquet_hall', '1', true) == true)
                        {
                            $banquetHall['amenity_key'] = "banquet_hall";
                            $banquetHall['amenity_value'] = "Banquet Hall";
                            $amenities['display_amenities'][] = $banquetHall;
                        }
                        else
                        {
                            $banquetHall['amenity_key'] = "banquet_hall";
                            $banquetHall['amenity_value'] = "Banquet Hall";
                            $amenities['dropdown_amenities'][] = $banquetHall;
                        }

                        $amphiTheatre = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'amphi_theatre', '1', true) == true)
                        {
                            $amphiTheatre['amenity_key'] = "amphi_theatre";
                            $amphiTheatre['amenity_value'] = "Amphi Theatre";
                            $amenities['display_amenities'][] = $amphiTheatre;
                        }
                        else
                        {
                            $amphiTheatre['amenity_key'] = "amphi_theatre";
                            $amphiTheatre['amenity_value'] = "Amphi Theatre";
                            $amenities['dropdown_amenities'][] = $amphiTheatre;
                        }

                        $gymasium = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'gymasium', '1', true) == true)
                        {
                            $gymasium['amenity_key'] = "gymasium";
                            $gymasium['amenity_value'] = "Gymnasium";
                            $amenities['display_amenities'][] = $gymasium;
                        }
                        else
                        {
                            $gymasium['amenity_key'] = "gymasium";
                            $gymasium['amenity_value'] = "Gymnasium";
                            $amenities['dropdown_amenities'][] = $gymasium;
                        }

                        $indoorGameCourt = []; 
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'indoor_game_court', '1', true) == true)
                        {
                            $indoorGameCourt['amenity_key'] = "indoor_game_court";
                            $indoorGameCourt['amenity_value'] = "Indoor Games Court";
                            $amenities['display_amenities'][] = $indoorGameCourt;
                        }
                        else
                        {
                            $indoorGameCourt['amenity_key'] = "indoor_game_court";
                            $indoorGameCourt['amenity_value'] = "Indoor Games Court";
                            $amenities['dropdown_amenities'][] = $indoorGameCourt;
                        }

                        $outdoorGameCourt = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'outdoor_game_court', '1', true) == true)
                        {
                            $outdoorGameCourt['amenity_key'] = "outdoor_game_court";
                            $outdoorGameCourt['amenity_value'] = "Outdoor Games Court";
                            $amenities['display_amenities'][] = $outdoorGameCourt;
                        }
                        else
                        {
                            $outdoorGameCourt['amenity_key'] = "outdoor_game_court";
                            $outdoorGameCourt['amenity_value'] = "Outdoor Games Court";
                            $amenities['dropdown_amenities'][] = $outdoorGameCourt;
                        }

                        $joggersPark = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'joggers_park', '1', true) == true)
                        {
                            $joggersPark['amenity_key'] = "joggers_park";
                            $joggersPark['amenity_value'] = "Jogger's Park";
                            $amenities['display_amenities'][] = $joggersPark;
                        }
                        else
                        {
                            $joggersPark['amenity_key'] = "joggers_park";
                            $joggersPark['amenity_value'] = "Jogger's Park";
                            $amenities['dropdown_amenities'][] = $joggersPark;
                        }   

                        $butterflyPark = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'butterfly_park', '1', true) == true)
                        {
                            $butterflyPark['amenity_key'] = "butterfly_park";
                            $butterflyPark['amenity_value'] = "Butterfly Park";
                            $amenities['display_amenities'][] = $butterflyPark;
                        }
                        else
                        {
                            $butterflyPark['amenity_key'] = "butterfly_park";
                            $butterflyPark['amenity_value'] = "Butterfly Park";
                            $amenities['dropdown_amenities'][] = $butterflyPark;
                        }

                        $temple = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'temple', '1', true) == true) 
                        {
                            $temple['amenity_key'] = "temple";
                            $temple['amenity_value'] = "Temple";
                            $amenities['display_amenities'][] = $temple;
                        }
                        else
                        {
                            $temple['amenity_key'] = "temple";
                            $temple['amenity_value'] = "Temple";
                            $amenities['dropdown_amenities'][] = $temple;
                        }   

                        $seniorCitizenGarden = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'senior_citizen_garden', '1', true) == true)
                        {
                            $seniorCitizenGarden['amenity_key'] = "senior_citizen_garden";
                            $seniorCitizenGarden['amenity_value'] = "Senior Citizen Garden";
                            $amenities['display_amenities'][] = $seniorCitizenGarden;
                        }
                        else
                        {
                            $seniorCitizenGarden['amenity_key'] = "senior_citizen_garden";
                            $seniorCitizenGarden['amenity_value'] = "Senior Citizen Garden";
                            $amenities['dropdown_amenities'][] = $seniorCitizenGarden;
                        }

                        $wifi = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'wifi', '1', true) == true)
                        {
                            $wifi['amenity_key'] = "wifi";
                            $wifi['amenity_value'] = "Wi-Fi";
                            $amenities['display_amenities'][] = $wifi;
                        }
                        else
                        {
                            $wifi['amenity_key'] = "wifi";
                            $wifi['amenity_value'] = "Wi-Fi";
                            $amenities['dropdown_amenities'][] = $wifi;
                        }

                        $relaxationRoom = [];
                        if(Helpers::chkAmenity($details->sites->siteMetas, 'relaxation_room', '1', true) == true) 
                        {
                            $relaxationRoom['amenity_key'] = "relaxation_room";
                            $relaxationRoom['amenity_value'] = "Relaxation Room";
                            $amenities['display_amenities'][] = $relaxationRoom;
                        }
                        else
                        {
                            $relaxationRoom['amenity_key'] = "relaxation_room";
                            $relaxationRoom['amenity_value'] = "Relaxation Room";
                            $amenities['dropdown_amenities'][] = $relaxationRoom;
                        }   

                    }
                    $propertyDetails['amenities'] = $amenities;
                }

                
                // Get specifications
                $propertyDetails['specifications'] = null;
                if(isset($details->sites->siteMetas))
                {
                    $flooring_balcony = Helpers::chkSpecs($details->sites->siteMetas, 'specification_flooring_balcony', 'Standard');
                    $propertyDetails['specifications']['flooring_balcony'] = Helpers::subString($flooring_balcony, 500);

                    $flooring_kitchen = Helpers::chkSpecs($details->sites->siteMetas, 'specification_flooring_kitchen', 'Standard');
                    $propertyDetails['specifications']['flooring_kitchen'] = Helpers::subString($flooring_kitchen, 500);

                    $flooring_bathroom = Helpers::chkSpecs($details->sites->siteMetas, 'specification_flooring_bathroom', 'Standard');
                    $propertyDetails['specifications']['flooring_bathroom'] = Helpers::subString($flooring_bathroom, 500);

                    $flooring_bedroom = Helpers::chkSpecs($details->sites->siteMetas, 'specification_flooring_bedroom', 'Standard');
                    $propertyDetails['specifications']['flooring_bedroom'] = Helpers::subString($flooring_bedroom, 500);

                    $flooring_livingroom = Helpers::chkSpecs($details->sites->siteMetas, 'specification_flooring_livingroom', 'Standard');
                    $propertyDetails['specifications']['flooring_livingroom'] = Helpers::subString($flooring_livingroom, 500);

                    $flooring_terrace = Helpers::chkSpecs($details->sites->siteMetas, 'specification_flooring_terrace', 'Standard');
                    $propertyDetails['specifications']['flooring_terrace'] = Helpers::subString($flooring_terrace, 500);

                    $flooring_master_bedroom = Helpers::chkSpecs($details->sites->siteMetas, 'specification_flooring_master_bedroom', 'Standard');
                    $propertyDetails['specifications']['flooring_master_bedroom'] = Helpers::subString($flooring_master_bedroom, 500);

                    $fitting_doors = Helpers::chkSpecs($details->sites->siteMetas, 'specification_fitting_doors', 'Standard');
                    $propertyDetails['specifications']['fitting_doors'] = Helpers::subString($fitting_doors, 500);

                    $fitting_windows = Helpers::chkSpecs($details->sites->siteMetas, 'specification_fitting_windows', 'Standard');
                    $propertyDetails['specifications']['fitting_windows'] = Helpers::subString($fitting_windows, 500);

                    $fitting_electrical = Helpers::chkSpecs($details->sites->siteMetas, 'specification_fitting_electrical', 'Standard');
                    $propertyDetails['specifications']['fitting_electrical'] = Helpers::subString($fitting_electrical, 500);

                    $fitting_kitchen_platform = Helpers::chkSpecs($details->sites->siteMetas, 'specification_fitting_kitchen_platform', 
                    'Standard');
                    $propertyDetails['specifications']['fitting_kitchen_platform'] = Helpers::subString($fitting_kitchen_platform, 500);

                    $fitting_bathroom = Helpers::chkSpecs($details->sites->siteMetas, 'specification_fitting_bathroom', 'Standard');
                    $propertyDetails['specifications']['fitting_bathroom'] = Helpers::subString($fitting_bathroom, 500);

                    $fitting_toilet = Helpers::chkSpecs($details->sites->siteMetas, 'specification_fitting_toilet', 'Standard');
                    $propertyDetails['specifications']['fitting_toilet'] = Helpers::subString($fitting_toilet, 500);

                    $fitting_sink = Helpers::chkSpecs($details->sites->siteMetas, 'specification_fitting_sink', 'Standard');
                    $propertyDetails['specifications']['fitting_sink'] = Helpers::subString($fitting_sink, 500);
    
                    $walls_exterior = Helpers::chkSpecs($details->sites->siteMetas, 'specification_walls_exterior', 'Standard');
                    $propertyDetails['specifications']['walls_exterior'] = Helpers::subString($walls_exterior, 500);

                    $walls_interior = Helpers::chkSpecs($details->sites->siteMetas, 'specification_walls_interior', 'Standard');
                    $propertyDetails['specifications']['walls_interior'] = Helpers::subString($walls_interior, 500);

                    $walls_kitchen = Helpers::chkSpecs($details->sites->siteMetas, 'specification_walls_kitchen', 'Standard');
                    $propertyDetails['specifications']['walls_kitchen'] = Helpers::subString($walls_kitchen, 500);

                    $walls_toilet = Helpers::chkSpecs($details->sites->siteMetas, 'specification_walls_toilet', 'Standard');
                    $propertyDetails['specifications']['walls_toilet'] = Helpers::subString($walls_toilet, 500);

                    $walls_balcony = Helpers::chkSpecs($details->sites->siteMetas, 'specification_walls_balcony', 'Standard');
                    $propertyDetails['specifications']['walls_balcony'] = Helpers::subString($walls_balcony, 500);
                }

                // Get specifications
                $propertyDetails['siteLocation'] = [];
                if(isset($details->sites->latitude) && $details->sites->latitude != '' && isset($details->sites->longitude) && $details->sites->longitude != '')
                {
                    $propertyDetails['siteLocation']['latitude'] = $details->sites->latitude;
                    $propertyDetails['siteLocation']['longitude'] = $details->sites->longitude;
                }

                
                // Get developer's information
                $propertyDetails['developerInformation'] = null;
                /*if(isset($propertyDetailsData->companies) && !empty($propertyDetailsData->companies))
                {
                    if(isset($propertyDetailsData->companies->company_logo) && $propertyDetailsData->companies->company_logo != '')
                    {
                        $propertyDetails['developerInformation']['company_logo'] = Helpers::cdnurl($details->companies->company_logo);
                    }
                    else
                    {
                        $propertyDetails['developerInformation']['company_logo'] = Helpers::cdnurl('images/placeholder.svg');
                    }
                    
                    $propertyDetails['developerInformation']['company_name'] = isset($propertyDetailsData->companies->company_name) && !empty($propertyDetailsData->companies->company_name) ? $propertyDetailsData->companies->company_name : "";
                    $propertyDetails['developerInformation']['no_of_projects_done'] = isset($propertyDetailsData->companies->no_of_projects_done) && !empty($propertyDetailsData->companies->no_of_projects_done) ? $propertyDetailsData->companies->no_of_projects_done : "-";
                    $propertyDetails['developerInformation']['eshtablishment'] = isset($propertyDetailsData->companies->year_eshtablishment) && !empty($propertyDetailsData->companies->year_eshtablishment) ? $propertyDetailsData->companies->year_eshtablishment : "-";
                    $propertyDetails['developerInformation']['description'] = isset($propertyDetailsData->companies->description) && !empty($propertyDetailsData->companies->description) ? $propertyDetailsData->companies->description : "";
                }*/



                // Get other projects
                /*$propertyDetails['otherProject'] = [];
                if(isset($propertyDetailsData->companies) && !empty($propertyDetailsData->companies))
                {
                    // get this developers other sites also
                    $otherProjects = Sites::getDeveloperOtherProjects($propertyDetailsData->company_id, $propertyDetailsData->id, $propertyDetail[5]);

                    if(isset($otherProjects) && !empty($otherProjects))
                    {
                        foreach($otherProjects as $k => $v)
                        {
                            $propertyDetails['otherProject'][] = Helpers::siteGridViewApi($v,$request);
                        }
                    }
                }*/


                
                // Get site offer
                $propertyDetails['siteOffer'] = [];
                if (isset($details->siteOffers) && count($details->siteOffers) > 0)
                {   $s = 0;

                    foreach ($details->siteOffers as $offer) 
                    {
                        if(isset($offer->option_name) && !empty($offer->option_name))
                        {
                            $propertyDetails['siteOffer'][$s]['option_name'] = $offer->option_name;
                            
                        }
                        if(isset($offer->final_price) && !empty($offer->final_price))
                        {
                            $propertyDetails['siteOffer'][$s]['final_price'] = $offer->final_price;
                            
                        } 
                        if(isset($offer->govt_subcidy_price) && !empty($offer->govt_subcidy_price))
                        {
                            $propertyDetails['siteOffer'][$s]['govt_subcidy_price'] = $offer->govt_subcidy_price;
                            
                        }
                        if(isset($offer->basic_cost) && !empty($offer->basic_cost))
                        {
                            $propertyDetails['siteOffer'][$s]['basic_cost'] = $offer->basic_cost;
                            
                        }
                        if(isset($offer->reg_cost) && !empty($offer->reg_cost))
                        {
                            $propertyDetails['siteOffer'][$s]['reg_cost'] = $offer->reg_cost;
                            
                        }
                        if(isset($offer->emi_cost) && !empty($offer->emi_cost))
                        {
                            $propertyDetails['siteOffer'][$s]['emi_cost'] = $offer->emi_cost;
                            
                        }
                        if(isset($offer->is_furniture) && !empty($offer->is_furniture))
                        {
                            $propertyDetails['siteOffer'][$s]['is_furniture'] = $offer->is_furniture;
                            
                        }
                        if(isset($offer->furniture_components) && !empty($offer->furniture_components))
                        {
                            $propertyDetails['siteOffer'][$s]['furniture_components'] = $offer->furniture_components;
                            
                        }
                        if(isset($offer->is_registration) && !empty($offer->is_registration))
                        {
                            $propertyDetails['siteOffer'][$s]['is_registration'] = $offer->is_registration;
                            
                        }
                        if(isset($offer->registration_cost) && !empty($offer->registration_cost))
                        {
                            $propertyDetails['siteOffer'][$s]['registration_cost'] = $offer->registration_cost;
                            
                        }
                        if(isset($offer->stamp_cost) && !empty($offer->stamp_cost))
                        {
                            $propertyDetails['siteOffer'][$s]['stamp_cost'] = $offer->stamp_cost;
                            
                        }
                        if(isset($offer->gst_cost) && !empty($offer->gst_cost))
                        {
                            $propertyDetails['siteOffer'][$s]['gst_cost'] = $offer->gst_cost;
                            
                        }
                        if(isset($offer->maintainance_cost) && !empty($offer->maintainance_cost))
                        {
                            $propertyDetails['siteOffer'][$s]['maintainance_cost'] = $offer->maintainance_cost;
                            
                        }
                        if(isset($offer->development_cost) && !empty($offer->development_cost))
                        {
                            $propertyDetails['siteOffer'][$s]['development_cost'] = $offer->development_cost;
                            
                        }
                        if(isset($offer->other_expense) && !empty($offer->other_expense))
                        {
                            $propertyDetails['siteOffer'][$s]['other_expense'] = $offer->other_expense;
                            
                        }
                        if(isset($offer->kitchen_components) && !empty($offer->kitchen_components))
                        {
                            $propertyDetails['siteOffer'][$s]['kitchen_components'] = $offer->kitchen_components;
                            
                        }
                        if(isset($offer->kitchen_cost) && !empty($offer->kitchen_cost))
                        {
                            $propertyDetails['siteOffer'][$s]['kitchen_cost'] = $offer->kitchen_cost;
                            
                        }
                        if(isset($offer->platform_cost) && !empty($offer->platform_cost))
                        {
                            $propertyDetails['siteOffer'][$s]['platform_cost'] = $offer->platform_cost;
                            
                        }
                        if(isset($offer->kitchen_overhead) && !empty($offer->kitchen_overhead))
                        {
                            $propertyDetails['siteOffer'][$s]['kitchen_overhead'] = $offer->kitchen_overhead;
                            
                        }
                        if(isset($offer->kitchen_loft_work) && !empty($offer->kitchen_loft_work))
                        {
                            $propertyDetails['siteOffer'][$s]['kitchen_loft_work'] = $offer->kitchen_loft_work;
                            
                        }
                        if(isset($offer->kitchen_service_cabinet) && !empty($offer->kitchen_service_cabinet))
                        {
                            $propertyDetails['siteOffer'][$s]['kitchen_service_cabinet'] = $offer->kitchen_service_cabinet;
                            
                        }
                        if(isset($offer->kitchen_service_overhead) && !empty($offer->kitchen_service_overhead))
                        {
                            $propertyDetails['siteOffer'][$s]['kitchen_service_overhead'] = $offer->kitchen_service_overhead;
                            
                        }
                        if(isset($offer->furniture_cost) && !empty($offer->furniture_cost))
                        {
                            $propertyDetails['siteOffer'][$s]['furniture_cost'] = $offer->furniture_cost;
                            
                        }
                        if(isset($offer->unit_left) && !empty($offer->unit_left))
                        {
                            $propertyDetails['siteOffer'][$s]['unit_left'] = $offer->unit_left;
                            
                        }
                        if(isset($offer->days_left) && !empty($offer->days_left))
                        {
                            $propertyDetails['siteOffer'][$s]['days_left'] = $offer->days_left;
                            
                        }
                        if(isset($offer->interest_subvention) && !empty($offer->interest_subvention))
                        {
                            $propertyDetails['siteOffer'][$s]['interest_subvention'] = $offer->interest_subvention;
                            
                        }
                        if(isset($offer->is_home_appliances) && !empty($offer->is_home_appliances))
                        {
                            $propertyDetails['siteOffer'][$s]['is_home_appliances'] = $offer->is_home_appliances;
                            
                        }
                        if(isset($offer->home_appliances_cost) && !empty($offer->home_appliances_cost))
                        {
                            $propertyDetails['siteOffer'][$s]['home_appliances_cost'] = $offer->home_appliances_cost;
                            
                        }
                        if(isset($offer->is_ac) && !empty($offer->is_ac))
                        {
                            $propertyDetails['siteOffer'][$s]['is_ac'] = $offer->is_ac;
                            
                        }
                        if(isset($offer->is_tv) && !empty($offer->is_tv))
                        {
                            $propertyDetails['siteOffer'][$s]['is_tv'] = $offer->is_tv;
                            
                        }
                        if(isset($offer->is_refrigeration) && !empty($offer->is_refrigeration))
                        {
                            $propertyDetails['siteOffer'][$s]['is_refrigeration'] = $offer->is_refrigeration;
                            
                        }
                        if(isset($offer->is_washing_machine) && !empty($offer->is_washing_machine))
                        {
                            $propertyDetails['siteOffer'][$s]['is_washing_machine'] = $offer->is_washing_machine;
                            
                        }
                        if(isset($offer->is_others) && !empty($offer->is_others))
                        {
                            $propertyDetails['siteOffer'][$s]['is_others'] = $offer->is_others;
                            
                        }
                        $s++;
                    }
                }
                
                // Completed project done
                /*$propertyDetails['completedProjects'] = [];
                if(isset($details->completedProjects) && $details->completedProjects->count() > 0)
                {
                    $cp = 0;
                    foreach ($propertyDetailsData->completedProjects as $k => $v) {
                        $propertyDetails['completedProjects'][$cp]['company_name'] = isset($propertyDetailsData->companies->company_name) ? $propertyDetailsData->companies->company_name : '';
                        $propertyDetails['completedProjects'][$cp]['site_name'] = isset($v->site_name) ? $v->site_name : '';
                        $propertyDetails['completedProjects'][$cp]['site_image'] = isset($v->site_image) && !empty($v->site_image) ? \Helpers::cdnurl($v->site_image) : '';
                        $cp++;
                    }   
                }*/


                     // Mascot Details
            $mascotDetails = Helpers::getMascotDetails();
            $propertyDetails['wishlist'] = null;
                    if(isset($request->user_id) && !empty($request->user_id))
                    {   
                        $user_id = $request->user_id;
                        $property_id = $request->property_id;
                        $propertyDetails['wishlist']  = WishList::where(['user_id'=>$user_id,'property_id'=>$property_id])->first();
                    }
                    
            /*$userPhone   = Helpers::isLandSite($propertyDetail[7]) && isset($mascotDetails->phone) ? $mascotDetails->phone : (isset($details->users->phone) ? $details->users->phone : '');*/
                $data['furniture_center_table'] ="public/images/FurnitureComponents/center_table.jpg";
                $data['furniture_dining_table'] ="public/images/FurnitureComponents/dining_table.jpg";
                $data['furniture_kingsize_bad'] ="public/images/FurnitureComponents/kingsize_bad.jpg";
                $data['furniture_sofa'] ="public/images/FurnitureComponents/sofa.jpg";
                $data['furniture_tv'] ="public/images/FurnitureComponents/tv.jpg";
                $data['furniture_wardrobe_table'] ="public/images/FurnitureComponents/wardrobe_table.jpg";
                $data['furniture_kitchen_furniture'] ="public/images/FurnitureComponents/kitchen_furniture.jpg";
                $data['furniture_platform_setup'] ="public/images/FurnitureComponents/platform_setup.jpg";
                $data['furniture_matresses'] ="public/images/FurnitureComponents/matresses.jpg";



                $data['kitchen_kitchen_overhead'] = "public/images/KitchenComponents/kitchen_overhead.jpg";
                $data['kitchen_kitchen_platform'] = "public/images/KitchenComponents/kitchen_platform.jpg";
                $data['kitchen_loft_work'] = "public/images/KitchenComponents/loft_work.jpg";
                $data['kitchen_service_cabinet'] = "public/images/KitchenComponents/service_cabinet.jpg";
                $data['kitchen_service_overhead'] = "public/images/KitchenComponents/service_overhead.jpg";


                $data['home_AC'] = "public/images/HomeAppliances/AC.jpg";
                $data['home_fridge'] = "public/images/HomeAppliances/fridge.jpg";
                $data['home_Tv'] = "public/images/HomeAppliances/Tv.jpg";
                $data['home_washing_machine'] = "public/images/HomeAppliances/washing_machine.jpg";
                $data['home_other'] = "public/images/HomeAppliances/other.jpg";

                $data['furniture'] = FurnitureDetails::get(['id','name','cost']);
                return Response::json(['status'=>1,'message'=>'Property details found successfully','new_resale'=>(string)$details->property_type,'image_cdn_url'=>config('app.cdnurl'),'property_detail_url'=>(isset($url) ? $url : ''),'propertyDetails'=>$propertyDetails,'data'=>$data]);
        
            }
            else
            {
                

                // if multiple category then give user a option to choose category
                // return view('Front.PropertyDetails.shortDetails')->with(compact('details'));
            }
        }
        else
        {
            return redirect()->route('homepage');
        }                

        $properties=$query->paginate(10);  
        
        $data['pagination'] = Helpers::propertyPaginationMetadata($properties);
        $data['properties'] = $properties;
        

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }  

    
    public function getPropertyCategory()
    {
        $category = PropertyCategory::select('id','name')->where('status','=','1')->orderBy('id','ASC')->get()->toArray();
        
        if(isset($category) && count($category)>0)
        {   
            $propertyCategory = [];
            $i = 0;
            foreach($category as $k => $v)
            {
                $propertyCategory[$i]['id'] = (string)$v['id'];
                $propertyCategory[$i]['name'] = $v['name'];
                $i++;
            }

            return Response::json(['status'=>1,'propertyCategory'=>$propertyCategory]);
        }
        else
        {
            return Response::json(['status'=>0,'message'=>'Data not found']);
        }
    }

    // Get property sub category
    public function getPropertySubCategory(Request $request)
    {
        if(isset($request->propertyCategoryId))
        {   
            $subCategory = PropertySubCategory::select('id','name','cat_id')
                            ->whereIn('cat_id',$request->propertyCategoryId)->where('status','=','1')
                            ->orderBy('id','ASC')->get()->toArray();
            
            if(isset($subCategory) && count($subCategory)>0)
            {   
                $propertySubCategory = [];
                $i = 0;
                foreach($subCategory as $k => $v)
                {
                    $propertySubCategory[$i]['categoryId'] = (string)$v['cat_id'];
                    $propertySubCategory[$i]['id'] = (string)$v['id'];
                    $propertySubCategory[$i]['name'] = $v['name'];
                    $i++;
                }
                return Response::json(['status'=>1,'propertySubCategory'=>$propertySubCategory]);
            }
        }
        else
        {
            return Response::json(['status'=>0,'message'=>'Data not found']);
        }
    }


     // Get state data
    public function getStates(Request $request)
    {
        if(isset($request->country_id) && $request->country_id > 0)
        {
            $states = States::select(['id', 'name'])
                            ->where("country_id", $request->country_id)
                            ->orderBy('name')
                            ->get();
        }
        else
        {
            $states = States::select(['id', 'name'])->orderBy('name')->get();
        }

        if(isset($states) && count($states) > 0)
        {
            $state = [];$i = 0;
            foreach ($states as $k => $v)
            {
                $state[$i]['id'] = (string)$v->id;
                $state[$i]['name'] = $v->name;
                $i++;
            }
            return Response::json(['status' => 1, 'states' => $state]);
        }
        else
        {
            return Response::json(['status' => 0, 'message' => 'States Not found']);    
        }
    }

    // Get city data
    public function getCities(Request $request) 
    {
        if(isset($request->state_id) && $request->state_id > 0)
        {
            $cities = Cities::select(['id','name','image'])
                            ->where("state_id", $request->state_id)
                            ->orderBy('name')
                            ->get();
        }
        else
        {
            $cities = Cities::select(['id','name'])->orderBy('name')->get();
        }

        if(isset($cities) && count($cities)>0)
        {   $city = [];$i = 0;
            foreach ($cities as $k => $v)
            {
                $city[$i]['id'] = (string)$v->id;
                $city[$i]['name'] = $v->name;
                $i++;
            }
            return Response::json(['status' => 1, 'cities' => $city]);
        }
        else
        {
            return Response::json(['status' => 0, 'message' => 'Cities not found']);
        }
    }

    // Get area data
    public function getAreas(Request $request)
    {
        if(isset($request->city_id) && $request->city_id > 0)
        {
            $areas = Areas::select(['id','name'])
                                ->where("city_id", $request->city_id)
                                ->orderBy('name')
                                ->get();
        }
        else
        {
            $areas = Areas::select(['id','name'])->orderBy('name')->get();
        }

        if(isset($areas) && count($areas)>0)
        {
            $area = [];$i = 0;
            foreach ($areas as $k => $v)
            {
                $area[$i]['id'] = (string)$v->id;
                $area[$i]['name'] = $v->name;
                $i++;
            }
            return Response::json(['status' => 1, 'areas' => $area]);
        }
        else
        {
            return Response::json(['status' => 0, 'message' => 'Areas not found']);
        }
    }


    public function getSiteNames(Request $request)
    {   
        $data = $request->get('site_name');
        $sitesList = Sites::select('id','site_name')->where('site_name', 'like', "%{$data}%")->orderBy('id','ASC')->get()->toArray();
        
        if(isset($sitesList) && count($sitesList)>0)
        {   
            $sites = [];
            $i = 0;
            foreach($sitesList as $k => $v)
            {
                $sites[$i]['id'] = (string)$v['id'];
                $sites[$i]['site_name'] = $v['site_name'];
                $i++;
            }

            return Response::json(['status'=>1,'sites'=>$sites]);
        }
        else
        {
            return Response::json(['status'=>0,'message'=>'Data not found']);
        }
    }



     
}
