<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteOffers;
use App\Models\Sites;
use App\Models\Properties;
use App\Models\FurnitureDetails;

use App\Http\Requests\SiteOffersRequest;
class PropertiesOffersController extends Controller
{
    private $page_data;

    public function __construct() {
        $this->page_data['page_title'] = 'Site offers';
        $this->page_data['perpage'] = 10;
        $this->declareStaticVars();
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        $id = $request->id;

        
        $Properties =  Properties::where('id',$id)->first();
        
        $FurnitureDetails = FurnitureDetails::get();

        $Siteid = $Properties->id;
        return view('PropertiesOffers.manage', compact('Properties','FurnitureDetails','Siteid'))
                        ->with('i', ($request->input('SiteOffer', 1) - 1) * $this->page_data['perpage'])
                        ->with($this->page_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        
        $PropertiesOffers = SiteOffers::with('properties')->where('property_id',$id)->get();
         
        return view('PropertiesOffers.view', compact('PropertiesOffers'))
                        ->with('i', ($request->input('SiteOffer', 1) - 1) * $this->page_data['perpage'])
                        ->with($this->page_data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        $PropertiesOffers = SiteOffers::with('sites','properties')->where('id',$id)->first();

         
        $FurnitureDetails = FurnitureDetails::get();
        $Siteid = $PropertiesOffers->site_id;
        return view('PropertiesOffers.edit', compact('PropertiesOffers','FurnitureDetails','Siteid'))
                        ->with('i', ($request->input('SiteOffer', 1) - 1) * $this->page_data['perpage'])
                        ->with($this->page_data);
    }

     
    public function update(SiteOffersRequest $request, $id)
    {
        $propertyId = SiteOffers::where('id',$id)->first();
        $property_id = $propertyId->property_id;
        if($request->is_furniture==1){

            $furniture_component = json_encode($request->furniture_components);  
            $is_furniture= $request->is_furniture;
            $furniture_cost= $request->furniture_cost;
        } else {
            $furniture_component = "";  
            $is_furniture= 0;
            $furniture_cost= "";
        }
        if($request->is_registration==1){
            
            $is_registration  = $request->is_registration;
            $registration_cost       = $request->registration_cost;
            $stamp_cost       = $request->stamp_cost;
            $gst_cost         = $request->gst_cost;
            $maintainance_cost         = $request->maintainance_cost;
            $development_cost = $request->development_cost;
            $other_expense    = $request->other_expense;
        } else {
            $is_registration  = 0;
            $registration_cost = '';
            $stamp_cost       = '';
            $gst_cost         = '';
            $maintainance_cost         = '';
            $development_cost = '';
            $other_expense    = '';
        }

        if($request->kitchen_components==1)
        {
            
            $kitchen_components = $request->kitchen_components;
            $kitchen_cost = $request->kitchen_cost;
            $platform_cost    = $request->platform_cost;
        } else {
            $kitchen_components = 0;
            $kitchen_cost = '';
            $platform_cost    = '';
        }


        $updateArray = [
            'site_id' => $request->site_id,
             
            'option_name' => $request->option_name,
            'final_price' => $request->final_price,
            'govt_subcidy_price' => $request->govt_subcidy_price,
            'basic_cost' => $request->basic_cost,
            'reg_cost' => $request->reg_cost,
            'emi_cost' => $request->emi_cost,
            //'home_appliance_cost' => $request->home_appliance_cost,
            'unit_left' => $request->unit_left,
            'days_left' => $request->days_left,
            'interest_subvention' => $request->interest_subvention,
            'furniture_components' => $furniture_component,  
            'is_furniture' => $is_furniture,
            'furniture_cost'=> $furniture_cost,
            'is_registration'  => $is_registration,
            'registration_cost' => $registration_cost,
            'stamp_cost'        => $stamp_cost,
            'gst_cost'          => $gst_cost,
            'maintainance_cost' => $maintainance_cost,
            'development_cost'  => $development_cost,
            'other_expense'     => $other_expense,
            'kitchen_components' => $kitchen_components,
            'kitchen_cost' => $kitchen_cost,
            'platform_cost'    => $platform_cost


        ];

        
        $result = SiteOffers::where('id',$id)->update($updateArray);
        
        return redirect()->route('propertiesoffers.view',['id'=>$property_id])
                        ->with('success', 'Properties Offers Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $SiteOffers=SiteOffers::find($id);
        $SiteOffers->delete();
        return redirect()->back()->with('success','Properties Offers deleted successfully');
    }

    private function declareStaticVars() {
        $this->page_data['site_offer_type']     = \Helpers::getStaticValues('site_offer_type');
        $this->page_data['yn_boolean']          = \Helpers::getStaticValues('yn_boolean');
    }
}
