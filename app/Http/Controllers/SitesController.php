<?php

namespace App\Http\Controllers;

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
use App\Http\Requests\SitesRequest;

class SitesController extends Controller
{   
    
    private $page_data;

    public function __construct() {

        $this->page_data['page_title'] = 'Active - New properties';
        $this->page_data['perpage'] = 10;
        $this->page_data['mode'] = '';
        $this->declareStaticVars();
        $this->middleware('auth');
    }

    public function search(Request $request)
    {
        $cities = Cities::all()->pluck('name', 'id');
        
        if($request->city_id && !empty($request->city_id)){
            $areas = Areas::with(['cities'])->where('city_id',$request->city_id)->pluck('name', 'id');
        }else{
            $areas = Areas::with(['cities'])->get()->pluck('name', 'id');
        }
         

        /* Builder Packages */
        $packages = \App\Models\MembershipPackages::where('package_for',1)->get();

        $this->page_data['newsite_counts'] = \Helpers::getNewSiteCounts();
        $propertyCategory = PropertyCategory::pluck('name', 'id');
        if (isset($request->cat_id)) {
            $propertyType = PropertySubCategory::where('cat_id', $request->cat_id)
                                    ->where('status', '1')
                                    ->orderBy('name', 'asc')
                                    ->pluck('name', 'id');
        } else {
            $propertyType = PropertySubCategory::pluck('name', 'id')->where('status', '1');
        }

        // get list of sites
        $Sites = Sites::getSitesList($request, $this->page_data['perpage'], 1, 1);

        $package_id = $request->package_id;
        
        // append variable for pagination
        $Sites->appends($request->all());

        return view('sites.manage', compact('Sites','package_id','packages','cities','areas'))
                        ->with('i', ($request->input('site', 1) - 1) * $this->page_data['perpage'])
                        ->with(compact('propertyCategory'))
                        ->with(compact('propertyType'))
                        ->with($this->page_data);
    }

   
    public function index(Request $request)
    {
        $this->page_data['newsite_counts'] = \Helpers::getNewSiteCounts();
        $propertyCategory = PropertyCategory::pluck('name', 'id');
        if(defined('CURRENT_CITY') && !empty(CURRENT_CITY)){
            $cities = Cities::where('id',CURRENT_CITY)->pluck('name', 'id');
        }else{
            $cities = Cities::all()->pluck('name', 'id');
        }
        
        if(defined('CURRENT_CITY') && !empty(CURRENT_CITY)){
            $areas = Areas::with(['cities'])->where('city_id',CURRENT_CITY)->pluck('name', 'id');
        }else{
            $areas = Areas::with(['cities'])->get()->pluck('name', 'id');
        }

        /* Builder Packages */
        $packages = \App\Models\MembershipPackages::where('package_for',1)->get();


        // get list of sites
        $Sites = Sites::getSitesList($request, $this->page_data['perpage'], 1, 1);


        return view('sites.manage', compact('Sites','packages','cities','areas'))
                        ->with('i', ($request->input('site', 1) - 1) * $this->page_data['perpage'])
                        ->with(compact('propertyCategory'))
                        ->with($this->page_data);
    }

   
    public function create()
    {
        $this->page_data['mode'] = 'add';

        $Banks = Banks::pluck('bank_name', 'id');  
        
       
        $state = States::pluck('name', 'id');  
       
        $city = ['' => 'Select City'];
        $area = ['' => 'Select Area'];
        return view('sites.create')
                    ->with(compact('Banks'))
                 
                    ->with(compact('state'))
                    ->with(compact('city'))
                    ->with(compact('area'))
                    ->with($this->page_data);
    }

   
    public function store(SitesRequest $request)
    {
        $this->validate($request,[
           'rera_certificate'=>'mimes:jpeg,jpg,png,pdf,doc|max:4000',
           'brochure'=>'mimes:jpeg,jpg,png,gif,png,pdf,doc|max:4000'
        ]);
        if($request->email){
            $emailExist = \App\Models\Users::where('email',$request->email)->first();
            if($emailExist){
                return back()->withErrors(['email.required', 'Email already exists.']);
            }
        }
         /** add new site details **/

        $siteId = Sites::createSite($request);

        
        /** add site marketing related contact details **/
        //SiteMarketers::processSiteMarketers($request, $siteId, 'add');
        
        /** site amenities **/
        SiteMetas::processSiteMetas($request, $siteId, 'add');

        /** add property loan sponser banks **/
        SiteLoans::processSiteLoanDetails($request, $siteId, 'add');

        /** upload property images **/
        if ($request->temp_images != null && is_array($request->temp_images)) {
            SitesImages::uploadSiteImages($request->temp_images, $siteId);
        }

        return redirect()->route('sites.index')->with('success', 'Builder sites added successfully');
    }

    
    public function show($id)
    {
        //
    }

   
    
    public function edit($id)
    {
        $this->page_data['mode'] = 'edit';
        $Sites = Sites::adminEditSite($id);

        if (!isset($Sites)) {
            return redirect()->route('sites.index')->withErrors('Requested site is not available or removed');
        }

        $Banks = Banks::pluck('bank_name', 'id');  
         
        $state = States::pluck('name', 'id');
        $city = Cities::pluck('name', 'id');
        $area = Areas::pluck('name', 'id');        
         

        /** possession month and year seperate **/
        if (isset($Sites->possession_date) && $Sites->possession_date != '') {
            $Sites->possession_month = date('n', strtotime($Sites->possession_date));
            $Sites->possession_year = date('Y', strtotime($Sites->possession_date));    
        }

        /** sample house future date **/
        if (isset($Sites->sample_house_date) && $Sites->sample_house_date != '') {
            $Sites->sample_house_month = date('n', strtotime($Sites->sample_house_date));
            $Sites->sample_house_year = date('Y', strtotime($Sites->sample_house_date));    
        }

        return view('sites.edit', compact('Sites'))
                        ->with(compact('id', 'Banks'))
                       
                        ->with(compact('id', 'state'))
                        ->with(compact('id', 'city'))
                        ->with(compact('id', 'area'))
                    
                        ->with($this->page_data);

    }

    
    public function update(SitesRequest $request, $id)
    {
        Sites::editSite($request, $id);

        /** add site marketing related contact details **/
        //SiteMarketers::processSiteMarketers($request, $id, 'edit');

        /** site amenities **/
        SiteMetas::processSiteMetas($request, $id, 'edit');

        /** update property loan sponser banks **/
        SiteLoans::processSiteLoanDetails($request, $id, 'edit');

        /** upload site images **/
        if ($request->temp_images != null && is_array($request->temp_images)) {
            SitesImages::uploadSiteImages($request->temp_images, $id);
        }

        return redirect()->route('sites.index')
                        ->with('success', 'Builders site updated successfully');
    }

    
    public function destroy($id)
    {
        //
    }


    public function getPropertyType(Request $request)
    {
        $cat_id = (int) $request->cat_id;
        if ($cat_id > 0) {
            $property_type = PropertySubCategory::select(['id', 'name'])
                            ->where("cat_id", $cat_id)
                            ->where('status', '1')
                            ->orderBy('name')
                            ->get();
        
            return response()->json(array('status' => 'success', 'data' => $property_type));
        }
        return response()->json(array('status' => 'error', 'data' => 'invalid request'));
    }


    public function getAreas(Request $request)
    {
       
        $city_id = (int) $request->city_id;
        if ($city_id > 0) {
            $areas = Areas::select(['id', 'name'])
                            ->where("city_id", $city_id)
                             
                            ->orderBy('name')
                            ->get();
        
            return response()->json(array('status' => 'success', 'data' => $areas));
        }
        return response()->json(array('status' => 'error', 'data' => 'invalid request'));

    }
    public function getCities(Request $request)
    {   
         
       
        $stateid = (int) $request->state_id;
        if ($stateid > 0) {
            $cities = Cities::select(['id', 'name'])
                            ->where("state_id", $stateid)
                            ->orderBy('name')
                            ->get();
        
            return response()->json(array('status' => 'success', 'data' => $cities));
        }
        return response()->json(array('status' => 'error', 'data' => 'invalid request'));

    }




    /** declare static vars **/
    private function declareStaticVars() {

        $this->page_data['sitePhotoType']       = \Helpers::getStaticValues('site_photo_type');
        $this->page_data['price_status']        = \Helpers::getStaticValues('price_status');
        $this->page_data['sample_house']        = \Helpers::getStaticValues('sample_house');
        $this->page_data['transaction_type']    = \Helpers::getStaticValues('transaction_type');

        $this->page_data['sample_house_month'] = 
        $this->page_data['sample_house_year'] = 
        $this->page_data['possesion_year'] = 
        $this->page_data['possesion_month'] = array(
            '' => 'Select option',
        );

        for ($i = 1; $i <= 12; $i++) {
            $this->page_data['sample_house_month'][$i] = 
            $this->page_data['possesion_month'][$i] = date('F', mktime(0, 0, 0, $i, 1));
        }

        for ($i=date('Y', strtotime('-10 years')); $i<=date('Y', strtotime('+10 years')); $i++) {
            $this->page_data['sample_house_year'][$i] = 
            $this->page_data['possesion_year'][$i] = $i;
        }    
    }


    public function quickUpload(Request $request) {

        $imageUrl = array();
        $errors = '';

        // chck site images and site id available
        if (isset($request->siteImage) && isset($request->site_id) && $request->site_id > 0) {
            
            // builder site quick image upload
            $result = SitesImages::quickUpload($request);
            
            if ($result !== false) {
                return response()->json(array(
                        'status' => 'success', 
                        'images' => $result['imageUrl'], 
                        'errors' => $result['errors']
                    )); 
            }
        }
        return response()->json(array('status' => 'error', 'errors' => $errors));
    }

    public function updateSiteCover($imageId = '', $siteId = '') {

        
        if ($imageId > 0 && $siteId > 0) {
            $isUpdated = SitesImages::updateSiteCover($imageId, $siteId);
            if ($isUpdated === true) {
                return response()->json([
                    'status' => 'success',
                    'msg' => 'Image marked as cover image.'
                ]);
            }
        }

        return response()->json([
            'status' => 'error',
            'msg' => 'Unable to change site cover image, please try again.'
        ]);
    }


    public function getPropertiesOfSites(Request $request)
    {
         $siteid = (int) $request->site_id;
        if ($siteid > 0) {
            $property = Properties::select(['id', 'sub_title'])
                            ->where("site_id", $siteid)
                            ->orderBy('sub_title')
                            ->get();
        
            return response()->json(array('status' => 'success', 'data' => $property));
        }
        return response()->json(array('status' => 'error', 'data' => 'invalid request'));   
    }

 

    public function getOffersOfProperty(Request $request)
    {   
         $property_id = (int) $request->property;
        if ($property_id > 0) {
            $property = SiteOffers::select(['id', 'option_name'])
                            ->where("property_id", $property_id)
                            ->orderBy('option_name')
                            ->get();
        
            return response()->json(array('status' => 'success', 'data' => $property));
        }
        return response()->json(array('status' => 'error', 'data' => 'invalid request'));   
    }


    public function deleteSiteSingleImage($id) {
        
        // check id is integer else ignore
        if (isset($id) && $id > 0) {

            // delete site single image 
            $isDeleted = SitesImages::deleteSiteSingleImage($id);    
            
            if ($isDeleted === true) {
                return response()->json([
                    'status' => 'success',
                    'msg' => 'Site image deleted'
                ]);
            }
        }
        
        return response()->json([
            'status' => 'error',
            'msg' => 'Problem deleting site image, please try again.'
        ]);

    }


    public function changeImageType(Request $request) {
            
        if (isset($request->image_id) && $request->image_id > 0 && 
            isset($request->site_id) && $request->site_id > 0) {

            $id = SitesImages::where(['id' => $request->image_id, 'site_id' => $request->site_id])
                    ->update(['image_type' => $request->image_category]);

            return response()->json(array('status' => 'success'));

        } else {
            return response()->json(array('status' => 'error'));
        }
    }

    
    public function changeSiteStatus(Request $request)
    {
        if ($request->site_id > 0) {
            $site_data = Sites::where('id', $request->site_id)->first();
            $status = $site_data->status;
            
            if($status==1)
            {
                $upstatus=0;
            } 
            if($status==0)
            {
                $upstatus=1;
            }
            $isUpdated = Sites::where('id', $request->site_id)->update(['status' => $upstatus]);
            if ($isUpdated) {

                return response()->json(array(
                    'status' => 'success',
                    'message' => 'Site status changed sucessfully'
                ));
            }
        }    
    }
    
}
