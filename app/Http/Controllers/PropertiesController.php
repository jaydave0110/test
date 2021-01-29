<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Properties;
use App\Models\PropertyMetas;
use App\Models\PropertyImages;
use App\Models\PropertyCategory;
use App\Models\PropertySubCategory;
use App\Models\PropertyFeatures;
use App\Models\PropertiesUnitCategory;
use App\Models\Companies; 
use App\Models\Sites;
use App\Models\ResidentEscalation;
use App\Http\Requests\PropertyRequest;
class PropertiesController extends Controller
{
    private $page_data;

    public function __construct() {
        $this->page_data['page_title'] = 'Properties';
        $this->page_data['perpage'] = 10;

        $this->declareStaticVars();
         $this->middleware('auth');
    }
    public function index()
    {
       //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Request()->site_id > 0) {
            $Sites = Sites::find(Request()->site_id);
        } else {
            $Sites = Sites::pluck('site_name', 'id');
        }
        
        $propertyCategory = PropertyCategory::pluck('name', 'id');
        $propertySubCategory =[]; 
        return view('Properties.create')
                        ->with(compact('Sites','propertyCategory','propertySubCategory'))
                        ->with($this->page_data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PropertyRequest $request)
    {
       
       $propertyId = Properties::createProperty($request);

        /* Floor Wise Escalation Price */
        ResidentEscalation::processEscaltion($request,$propertyId,'add');

        /** add property unit category */
        //PropertiesUnitCategory::processPropertyCategory($request, $propertyId, 'add');

        /** add property features details **/
        PropertyFeatures::processPropertyFeatures($request, $propertyId, 'add');

        /** add property meta details **/
        PropertyMetas::processPropertyMetas($request->meta, $propertyId, 'add');

        /** upload property images **/
        if ($request->temp_images != null && is_array($request->temp_images)) {
            PropertyImages::uploadPropertyImages($request->temp_images, $propertyId);
        }

        return redirect()->route('sites.index')
                    ->with('success', 'Property created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $properties = Properties::select(['id', 'company_id', 'site_id', 'name', 'sub_title', 'description', 
                        'video_link', 'transaction_type', 'price', 'cat_id', 'sub_cat_id','is_commission','commission_percent','commission_basic_price','commission_amount','package','brokrage_type','fix_pay_type','fix_pay_amount','is_escalation','is_garden_facing','garden_facing_amount','garden_facing_amount','is_club_house_facing','club_house_facing_amount','id_road_facing','road_facing_amount','is_croner_flat','corner_flat_amount','is_others','other_amount','is_popular','is_featured','status','brokrage_type','total_escalation_floors'])
                     
                    ->with(['propertyCategory' => function ($query) {
                        $query->addSelect('id', 'name');
                    }])
                    ->with(['escalations'])
                    ->with(['propertySubCategory' => function ($query) {
                        $query->addSelect('id', 'name');
                    }])
                    ->with(['propertyImages' => function ($query) {
                        $query->addSelect('id', 'property_id', 'image_name', 'image_type', 'is_featured', 'is_covered');
                    }])
                    ->with(['propertyFeatures' => function ($query) {
                        $query->addSelect('id', 'property_id', 'bedrooms', 'bathrooms', 'balconies', 'foyer_area', 'store_room', 'pooja_room',
                            'study_room', 'parking_area', 'open_sides', 'servant_room', 'area_covered', 'area_covered_unit', 'sb_area', 'sb_area_unit',
                            'carpet_area', 'carpet_area_unit', 'built_area', 'built_area_unit', 'plot_area', 'plot_area_unit', 'plot_area_project',
                            'plot_area_project_unit', 'commencement', 'vastu', 'furnished_status', 'interior_details', 'shed_area',
                            'shed_area_unit', 'electricity_connection', 'crane_facility', 'shed_height', 'shed_height_unit', 'no_of_towers',
                            'no_of_houses', 'no_of_flats', 'total_floors', 'plot_size_range', 'plot_size_range_unit', 'price_sq_ft','total_unit');
                    }])
                    ->with(['propertyMetas' => function ($query) {
                        $query->addSelect('property_id', 'meta_key', 'meta_value');
                    }])
                    ->find($id);

        if ($properties == null) {
            return redirect()->route('sites.index');
        }

         
        $propertyCategory = PropertyCategory::pluck('name', 'id');

        /* select company */
        
         $Sites = Sites::where('id',$properties->site_id)->first();
          

        /* select property type category */
        if (isset($properties->propertyCategory->id)) {
            $propertySubCategory = PropertySubCategory::where('cat_id', $properties->propertyCategory->id)
                                    ->where('status', '1')
                                    ->orderBy('name', 'asc')
                                    ->pluck('name', 'id');
        } else {
            $propertySubCategory = PropertySubCategory::pluck('name', 'id')->where('status', '1');
        }   
        

        return view('Properties.edit', compact('properties')) 
                        
                        ->with(compact('id', 'Sites'))
                        ->with(compact('propertyCategory'))
                        ->with(compact('propertySubCategory'))
                        ->with($this->page_data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PropertyRequest $request, $id)
    {
        Properties::editProperty($request, $id);
        ResidentEscalation::processEscaltion($request,$id,'edit');
        /** add property unit category */
       // PropertiesUnitCategory::processPropertyCategory($request, $id, 'edit');
        /** add property fetures details **/
        PropertyFeatures::processPropertyFeatures($request, $id, 'edit');
        /** add property meta details **/
        PropertyMetas::processPropertyMetas($request->meta, $id, 'edit');
        /** upload property images **/
        if ($request->temp_images != null && is_array($request->temp_images)) {
            PropertyImages::uploadPropertyImages($request->temp_images, $id);
        }

        return redirect()->route('sites.index')
                        ->with('success', 'Property Details updated successfully'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id > 0) {
            // Delete property by id 
            // other property related information table has foreignn key 
            // so that rows get deleted automatic on delete of property
            Properties::where('id', $id)->delete();
        }

        return redirect()->route('sites.index')->with('success', 'Property deleted successfully');

    }

    private function declareStaticVars() {

        $this->page_data['photo_type']          = \Helpers::getStaticValues('photo_type');
        $this->page_data['transaction_type']    = \Helpers::getStaticValues('transaction_type');
        $this->page_data['area_unit']           = \Helpers::getStaticValues('area_unit');
        $this->page_data['bedrooms']            = \Helpers::getStaticValues('bedrooms');
        $this->page_data['bathrooms']           = \Helpers::getStaticValues('bathrooms');
        $this->page_data['balconies']           = \Helpers::getStaticValues('balconies');
        $this->page_data['price_status']        = \Helpers::getStaticValues('price_status');
        $this->page_data['total_floors']        = array('' => 'Select Option');
        $this->page_data['land_zone']           = \Helpers::getStaticValues('land_zone');
        $this->page_data['land_type']           = \Helpers::getStaticValues('land_type');
        $this->page_data['land_location']       = \Helpers::getStaticValues('land_location');
        
        for ($i = 0; $i<=128; $i++) {
            $this->page_data['total_floors'][$i] = $i;
        }

    }

    public function deletePropertySingleImage($id) {
        
        // check id is integer else ignore
        if (isset($id) && $id > 0) {
            $isDeleted = PropertyImages::deletePropertySingleImage($id);
            if ($isDeleted == true) {
                return response()->json([
                    'status' => 'success',
                    'msg' => 'Property image deleted'
                ]);     
            }
        }
        return response()->json(['status' => 'error']);
    }
}
