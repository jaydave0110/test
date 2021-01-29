<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteOffers;
use App\Models\Sites;
use App\Models\Properties;
use App\Models\FurnitureDetails;

use App\Http\Requests\SiteOffersRequest;

class SiteOffersController extends Controller
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
        
        //$SiteOffers = SiteOffers::getSiteOffersForAdmin($request, $this->page_data['perpage']);
        
        $Siteid = $request->id;

        $SiteData = Sites::where('id',$Siteid)->first();
        $Properties =  Properties::where('site_id',$Siteid)->pluck('sub_title','id');
        
        $FurnitureDetails = FurnitureDetails::get();

        return view('SiteOffers.manage', compact('Siteid','SiteData','Properties','FurnitureDetails'))
                        ->with('i', ($request->input('SiteOffer', 1) - 1) * $this->page_data['perpage'])
                        ->with($this->page_data);
    }

    
    public function create()
    {
        $Sites = Sites::pluck('site_name', 'id');
        $Properties = ['' => 'Select Sites'];
        return view('SiteOffers.create')->with(compact('Sites','Properties'))->with($this->page_data);
    }

    
    public function store(SiteOffersRequest $request)
    {
        //dd($request->all());
        $siteOffer = new SiteOffers;
        $siteOffer->site_id = $request->site_id;
        $siteOffer->property_id = $request->property_id;
        $siteOffer->option_name = $request->option_name;
        $siteOffer->final_price = $request->final_price;
        $siteOffer->govt_subcidy_price = $request->govt_subcidy_price;
        $siteOffer->basic_cost = $request->basic_cost;
        $siteOffer->reg_cost = $request->reg_cost;
        $siteOffer->emi_cost = $request->emi_cost;
        //$siteOffer->home_appliance_cost = $request->home_appliance_cost;
        $siteOffer->unit_left = $request->unit_left;
        $siteOffer->days_left = $request->days_left;
        $siteOffer->interest_subvention = $request->interest_subvention;
       
        if($request->is_furniture==1){
            $furniture_component = json_encode($request->furniture_components);  
                
            $siteOffer->is_furniture= $request->is_furniture;
            $siteOffer->furniture_cost= $request->furniture_cost;
            $siteOffer->furniture_components= $furniture_component;

        }
        if($request->is_registration==1){
            
            $siteOffer->is_registration  = $request->is_registration;

            $siteOffer->registration_cost       = $request->registration_cost;
            $siteOffer->stamp_cost       = $request->stamp_cost;
            $siteOffer->gst_cost         = $request->gst_cost;
            $siteOffer->development_cost = $request->development_cost;
            $siteOffer->other_expense    = $request->other_expense;
            $siteOffer->maintainance_cost    = $request->maintainance_cost;
        } 

        if($request->kitchen_components==1)
        {
            
            $siteOffer->kitchen_components = $request->kitchen_components;
            $siteOffer->kitchen_cost = $request->kitchen_cost;
            $siteOffer->platform_cost    = $request->platform_cost;
            $siteOffer->kitchen_overhead    = $request->kitchen_overhead;
            $siteOffer->kitchen_loft_work    = $request->kitchen_loft_work;
            $siteOffer->kitchen_service_cabinet    = $request->kitchen_service_cabinet;
            $siteOffer->kitchen_service_overhead    = $request->kitchen_service_overhead;
        } 


        if($request->is_home_appliances==1)
        {
            
            $siteOffer->is_home_appliances = $request->is_home_appliances;
            $siteOffer->home_appliances_cost = $request->home_appliances_cost;
            $siteOffer->is_ac    = $request->is_ac;
            $siteOffer->is_tv    = $request->is_tv;
            $siteOffer->is_refrigeration    = $request->is_refrigeration;
            $siteOffer->is_washing_machine  = $request->is_washing_machine;
            $siteOffer->is_others    = $request->is_others;
        }  
        


          
       //dd($request->all(),$furniture_component);
        $result = $siteOffer->save();
        return redirect()->back()
                        ->with('success', 'Site offer added successfully');

    }

    
    public function show(Request $request,$id)
    {
        

        $SiteOffers = SiteOffers::with('properties')->where('site_id',$id)->get();
        $Siteid = $id;
        return view('SiteOffers.view', compact('SiteOffers','Siteid'))
                        ->with('i', ($request->input('SiteOffer', 1) - 1) * $this->page_data['perpage'])
                        ->with($this->page_data);
        
    }

    
    public function edit(Request $request,$id)
    {
        $SiteOffers = SiteOffers::with('sites','properties')->where('id',$id)->first();

        $Properties =  Properties::where('site_id',$SiteOffers->sites->id)->pluck('sub_title','id');
        $FurnitureDetails = FurnitureDetails::get();
        return view('SiteOffers.edit', compact('SiteOffers','Properties','FurnitureDetails'))
                        ->with('i', ($request->input('SiteOffer', 1) - 1) * $this->page_data['perpage'])
                        ->with($this->page_data);
    }

    
    public function update(SiteOffersRequest $request, $id)
    {
        $getsiteId = SiteOffers::with('sites','properties')->where('id',$id)->first();
        
        $site_id = $getsiteId->sites->id;


        if($request->is_furniture==1){

            $furniture_component = json_encode($request->furniture_components);  
            $is_furniture= $request->is_furniture;
            $furniture_cost= $request->furniture_cost;
        } else {
            $furniture_component = $getsiteId->furniture_component;  
            $is_furniture=$getsiteId->is_furniture;
            $furniture_cost= $getsiteId->furniture_cost;
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
            $is_registration  = $getsiteId->is_registration;
            $registration_cost = $getsiteId->registration_cost;
            $stamp_cost       = $getsiteId->stamp_cost;
            $gst_cost         = $getsiteId->gst_cost;
            $maintainance_cost         = $getsiteId->maintainance_cost;
            $development_cost = $getsiteId->development_cost;
            $other_expense    = $getsiteId->other_expense;
        }

        if($request->kitchen_components==1)
        {
            
            $kitchen_components = $request->kitchen_components;
            $kitchen_cost = $request->kitchen_cost;
            $platform_cost    = $request->platform_cost;
            $kitchen_overhead    = $request->kitchen_overhead;
            $kitchen_loft_work    = $request->kitchen_loft_work;
            $kitchen_service_cabinet    = $request->kitchen_service_cabinet;
            $kitchen_service_overhead    = $request->kitchen_service_overhead;

        } else {
            $kitchen_components = $getsiteId->kitchen_components;
            $kitchen_cost =  $getsiteId->kitchen_cost;
            $platform_cost    =  $getsiteId->platform_cost;
            $kitchen_overhead    =  $getsiteId->kitchen_overhead;
            $kitchen_loft_work    =  $getsiteId->kitchen_loft_work;
            $kitchen_service_cabinet =   $getsiteId->kitchen_service_cabinet;
            $kitchen_service_overhead    =  $getsiteId->kitchen_service_overhead;
        }


        if($request->is_home_appliances==1)
        {
            
            $is_home_appliances = $request->is_home_appliances;
            $home_appliances_cost = $request->home_appliances_cost;
            $is_ac    = $request->is_ac;
            $is_tv    = $request->is_tv;
            $is_refrigeration    = $request->is_refrigeration;
            $is_washing_machine  = $request->is_washing_machine;
            $is_others    = $request->is_others;

        } else {
            $is_home_appliances = $getsiteId->is_home_appliances;
            $home_appliances_cost = $getsiteId->home_appliances_cost;
            $is_ac    = $getsiteId->is_ac;
            $is_tv    = $getsiteId->is_tv;
            $is_refrigeration    = $getsiteId->is_refrigeration;
            $is_washing_machine  = $getsiteId->is_washing_machine;
            $is_others    = $getsiteId->is_others;

        }





        $updateArray = [
            'site_id' => $request->site_id,
            'property_id' => $request->property_id,
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
            'platform_cost'    => $platform_cost,
            'kitchen_overhead'    => $kitchen_overhead,
            'kitchen_loft_work'    => $kitchen_loft_work,
            'kitchen_service_cabinet'    => $kitchen_service_cabinet,
            'kitchen_service_overhead'    => $kitchen_service_overhead,

            'is_home_appliances'    => $is_home_appliances,
            'home_appliances_cost'    => $home_appliances_cost,
            'is_ac'    => $is_ac,
            'is_tv'    => $is_tv,
            'is_refrigeration'    => $is_refrigeration,
            'is_washing_machine'    => $is_washing_machine,
             
            'is_others'    => $is_others


        ];

        $result = SiteOffers::where('id',$id)->update($updateArray);
        
        return redirect()->route('siteoffers.view',['id'=>$site_id])
                        ->with('success', 'Site offer Updated successfully');
    }

    
    public function destroy($id)
    {
        
        $SiteOffers=SiteOffers::find($id);
        $SiteOffers->delete();
        return redirect()->back()->with('success','Site Offer deleted successfully');
    }


    private function declareStaticVars() {
        $this->page_data['site_offer_type']     = \Helpers::getStaticValues('site_offer_type');
        $this->page_data['yn_boolean']          = \Helpers::getStaticValues('yn_boolean');
    }
}
