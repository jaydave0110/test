<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
class Sites extends Model
{
    use HasFactory;

    protected $table = 'tbl_builder_sites';

    public function escalations() {
        return $this->hasMany(ResidentEscalation::class, 'site_id', 'id');
    }

    public function users() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function bookings() {
        return $this->hasMany(Bookings::class, 'site_id', 'id');
    }

    public function properties() {
        return $this->hasMany(Properties::class, 'site_id', 'id');
    }

    public function siteLoans() {
        return $this->hasMany(SiteLoans::class, 'site_id', 'id');
    }

    public function siteMetas() {
        return $this->hasMany(SiteMetas::class, 'site_id', 'id');
    }
    
    public function siteImages() {
        return $this->hasMany(SitesImages::class, 'site_id', 'id');
    }

    public function areas() {
        return $this->belongsTo(Areas::class, 'area_id', 'id');
    }

    public function cities() {
        return $this->belongsTo(Cities::class, 'city_id', 'id');
    }

    public function states() {
        return $this->belongsTo(States::class, 'state_id', 'id');
    }

    public function sitemarketers() {
        return $this->hasMany(SiteMarketers::class, 'site_id', 'id');
    }

    public function metas() {
        return $this->hasMany(SiteMetas::class, 'site_id', 'id');
    }

    public function siteOffers() {
        return $this->hasMany(SiteOffers::class, 'site_id', 'id');
    }

    public function sitePayments() {
        return $this->hasMany(SitePayments::class, 'site_id', 'id');
    }

    public static function getSitesList($request = '', $perPage = 10, $propertyType = 1, $propertyStatus = 1) {

        $query = Sites::select(['id','status','user_id', 'site_name', 'address', 'area_id', 'city_id', 
            'possession_status', 'possession_date', 'price_status', 'latitude', 'longitude', 'created_at', 'updated_at'])
            ->selectRaw('ifnull((select id from tbl_site_payments where site_id = id and status = 1 and date(subscription_duration_to) >= "'.date("Y-m-d").'"),0) as membershipStatus')        
            ->where('property_type', $propertyType);
                

            if(defined('CURRENT_CITY') && !empty(CURRENT_CITY)){
                $query->where('city_id', CURRENT_CITY);
            }


            
                if(isset($request->status)  && $request->status=='0' )
                {  
                    $query->where('status',$request->status);
                } 
                else if(isset($request->status) && $request->status==1)
                {  
                     $query->where('status', $request->status);
                }
                else if(isset($request->status) && $request->status==5)
                {
                    $query->whereIn('status',['0','1']);
                }
                else {

                    $query->where('status', $propertyStatus);
                }
             


             
            if(isset($request->site_name))
            {
                $query->where('site_name', 'LIKE', '%' . $request->site_name .'%'); 
                    
            }

            /* search where clause */
            if (isset($request)) {

                if (isset($request->code)) {
                    $siteIds = Properties::where('code', 'like', '%'.$request->code.'%')->pluck('site_id');
                    $query->whereIn('id', ($siteIds ? $siteIds : []));
                }
                if (isset($request->transaction_type) && $request->transaction_type > 0) {
                    $siteIds = Properties::where('transaction_type', $request->transaction_type)->pluck('site_id');
                    $query->whereIn('id', ($siteIds ? $siteIds : []));
                }
                if (isset($request->cat_id) && $request->cat_id > 0) {
                    $siteIds = Properties::where('cat_id', $request->cat_id)->pluck('site_id');
                    $query->whereIn('id', ($siteIds ? $siteIds : []));
                }
                if (isset($request->sub_cat_id) && $request->sub_cat_id > 0) {
                    $siteIds = Properties::where('sub_cat_id', $request->sub_cat_id)->pluck('site_id');
                    $query->whereIn('id', ($siteIds ? $siteIds : []));
                }
                if (isset($request->is_featured) && $request->is_featured != '' && intval($request->is_featured) >= 0) {
                    $query->where('is_featured',$request->is_featured);
                }

               

                if (!empty($request->budget_from) || !empty($request->budget_to)) {    
                    if ($request->budget_from != '' && $request->budget_to != '') {
                        $query->whereIn('id', Properties::whereBetween('price', 
                                            array($request->budget_from, $request->budget_to))->pluck('site_id'));
                    } else if ($request->budget_from != '' && trim($request->budget_to) == '') {
                        $query->whereIn('id', Properties::where('price', '>', $request->budget_from)->pluck('site_id'));
                    } else if (trim($request->budget_from) == '' && $request->budget_to != '') {
                        $query->whereIn('id', Properties::where('price', '<', $request->budget_to)->pluck('site_id'));
                    }
                }

                // if (isset($request->budget_from)) {
                //     $query->where('budget_from', '>=', $request->budget_from);
                // }
                // if (isset($request->budget_to)) {
                //     $query->where('budget_to', '<=', $request->budget_to);
                // }
                if (isset($request->area_id) && count($request->area_id)) {
                    $query->whereIn('area_id',$request->area_id);
                }
                // if user filter set for city id
                if ($request->city_id > 0) {
                    $query->where('city_id', '<=', $request->city_id);
                }  
                
                if ($request->user_type) {
                    $query->whereRaw('user_id in (select id from TBL_USERS where user_type = '.$request->user_type.')');
                }  
                
                if ($request->duration) {
                    $query->whereRaw('date(created_at) BETWEEN DATE_SUB("'.date('Y-m-d').'", INTERVAL '.$request->duration.' DAY) AND "'.date('Y-m-d').'" ');
                }    
                
                  
                
                if (!empty($request->name_number)) {
                    $query->whereRaw('user_id in (select id from TBL_USERS where (fullname like "%'.$request->name_number.'%" or phone like "%'.$request->name_number.'%" or secondary_phone like "%'.$request->name_number.'%" ))');
                }     

                if ($request->package_id > 0) {
                    $site_ids = \App\Models\SitePayments::where('package_id',$request->package_id)->where('status',1)->get()->pluck('site_id');
                    $query->whereIn('id',$site_ids);                    

                    if(!empty($request->subscription_duration_from) && !empty($request->subscription_duration_to)){
                        $query->whereRaw('(id in (select site_id from tbl_site_payments where package_id = '.$request->package_id.' and status = 1 and ((subscription_duration_from between "'.$request->subscription_duration_from.'" and "'.$request->subscription_duration_to.'") OR (subscription_duration_to between "'.$request->subscription_duration_from.'" and "'.$request->subscription_duration_to.'"))))');
                    }else{
                        if (!empty($request->subscription_duration_from)) {
                            $query->whereRaw('(id in (select site_id from tbl_site_payments where package_id = '.$request->package_id.' and status = 1 and subscription_duration_from >= "'.$request->subscription_duration_from.' 23:59:59" and subscription_duration_to <= "'.$request->subscription_duration_from.' 23:59:59"))');
                        }

                        if (!empty($request->subscription_duration_to)) {
                            $query->whereRaw('(id in (select site_id from tbl_site_payments where package_id = '.$request->package_id.' and status = 1 and subscription_duration_from >= "'.$request->subscription_duration_to.' 23:59:59" and subscription_duration_to <= "'.$request->subscription_duration_to.' 23:59:59"))');
                        }
                    }
                }
            }

            $query->with(['areas', 'cities'])
                   ->with(['users' => function ($query) {
                $query->addSelect('id','name');
            }]);


            $query->with(['siteOffers'])
            ->with(['sitePayments' => function ($query) {
                $query->where('status',1);
            }])->with(['properties' => function ($query) use ($request) {
                
                $query->addSelect('id', 'code', 'site_id', 'name', 'sub_title', 'price', 
                    'cat_id', 'sub_cat_id', 'transaction_type', 
                    'status');
                
                $query->with(['propertyCategory' => function ($query) {
                    $query->addSelect('id', 'name', 'slug');
                }]);

                $query->with(['propertySubCategory' => function ($query) {
                    $query->addSelect('id', 'name');
                }]);

                $query->with(['propertiesUnitCategory' => function ($query) {
                    $query->addSelect('id', 'property_id', 'cat_id', 'sub_cat_id');
                }]);

                $query->with(['propertyFeatures' => function ($query) {
                    $query->addSelect(\Helpers::propertyFeaturesFields());
                }]);
                
                $query->with(['propertyMetas' => function ($query) {
                    $query->addSelect('property_id', 'meta_key', 'meta_value');
                }]);

            }]);

        // city wise access where clause
        
        // echo $query->toSql();
        // exit();
       
        if ($request->latest_updated_by) {
            $query->orderBy('updated_at','desc');
        } else{
            $query->orderBy('id', 'desc');
        }

        return $query->paginate($perPage);   
    }

    public function wishList() {
        return $this->hasMany(WishList::class, 'site_id', 'id');
    }


    public static function createSite(Request $request) {

            
        // add site basic details first to get site id for future reference
        $site = new Sites;
        $site = $site->setParameters($request, $site);
        $site->status = 1;
        $site->save();

        self::createUserForSiteHandle($request,$site);

        // Created Code
        $site->code = (!empty($site->code) ? $site->code.sprintf('%05d',$site->id) : sprintf('%05d',$site->id) );
        $site->save();

        return $site->id;
    }

    private static function createUserForSiteHandle($request,$site){
        if(isset($request->fullname) && isset($request->email) && isset($request->password)){
            $user = new \App\Models\Users;
            $user->fullname = $request->fullname;
            $user->email = $request->email;
            $user->username = $request->email;
            $user->user_type = 5;
            $user->password = \Hash::make($request->password);
            $user->sms_verified = '0';
            $user->save();
            $site->handler_user_id = $user->id;
            $site->save();
        }
    }   

    private static function setParameters($request, $site) {

        // get user form company
         
        $site->user_id          = auth()->user()->id;
        $site->site_name            = $request->site_name;
        $site->description          = $request->description != '' ? $request->description : '';
        $site->rera_no              = isset($request->rera_no) && !empty($request->rera_no) ? $request->rera_no : '';

        if(isset($request->rera_certificate) && !empty($request->rera_certificate))
        {
            //$site->rera_certificate = \Storage::put('images/siteImages', $request->rera_certificate, 'public');  
                $image = $request->file('rera_certificate');
                $rera_certificate = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/images/siteImages');
                $image->move($destinationPath, $rera_certificate); 
                $site->rera_certificate =$rera_certificate;


        }

        if(isset($request->brochure) && !empty($request->brochure))
        {
            //$site->brochure = \Storage::put('images/siteImages', $request->brochure, 'public');
                
                $image = $request->file('brochure');
                $brochure = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/images/siteImages');
                $image->move($destinationPath, $brochure); 
                $site->brochure =$brochure;

        }
        
        $site->video_link           = $request->video_link; 
         
        $site->state_id             = $request->state_id; 
        $site->city_id              = $request->city_id; 
        $site->area_id              = $request->area_id; 
        $site->address              = $request->address; 
        $site->latitude             = $request->latitude; 
        $site->longitude            = $request->longitude; 
        $site->loan_approval        = $request->loan_approval;
        $site->water_supply         = $request->water_supply;
        $site->power_backup         = $request->power_backup;
        $site->website_url         = $request->website_url;

        $site->possession_status     = $request->possession_status;
        if ($request->possession_status == 1) {
            $site->possession_date = date('Y-m-d', strtotime("$request->possession_year-$request->possession_month"));
        }

        $site->price_status             = $request->price_status;        
        $site->sample_house             = $request->sample_house;
        $site->sample_house_360_link    = $request->sample_house_360_link;
        $site->sample_house_video_link  = $request->sample_house_video_link;

        $site->whom_to_call  = $request->whom_to_call;
        $site->contact_person_name  = $request->contact_person_name;
        $site->contact_person_email  = $request->contact_person_email;
        $site->contact_person_phone  = $request->contact_person_phone;

        /*$site->brokrage_type  = $request->brokrage_type;
        if($request->brokrage_type==1){
            $brokrage_amount  = $request->brokrage_amount;
            $brokrage_percent  = 0;
        } else {
            $brokrage_amount  = $request->brokrage_percent_amount;
            $brokrage_percent  = $request->brokrage_percent;
        }

        $site->brokrage_amount  = $brokrage_amount;
        $site->brokrage_percent  = $brokrage_percent;*/

        $site->sample_house_date    = '';
        if ($request->possession_status == 0) {
            $site->sample_house_date = date('Y-m-d', 
                                        strtotime("$request->sample_house_year-$request->sample_house_month"));
        }

        $site->usp_one   = $request->specs['usp_one'] ;
        $site->usp_two   = $request->specs['usp_two'];
        $site->usp_three = $request->specs['usp_three'];
        $site->usp_four  = $request->specs['usp_four'];
        $site->usp_five  = $request->specs['usp_five'];
        $site->usp_six   = $request->specs['usp_six'];
       
        return $site;
    }

    public static function adminEditSite($id) {
        
        return Sites::select([
                    'id','site_name', 'description', 'display_units', 
                    'total_buildings', 'sold_buildings', 'unsold_buildings', 'lead_by', 'address', 
                    'country_id', 'state_id', 'city_id', 'area_id', 'latitude', 'longitude',
                    'possession_status', 'possession_date', 'price_status', 'sample_house', 'sample_house_date',
                    'sample_house_360_link', 'sample_house_video_link', 'video_link', 'loan_approval', 'power_backup', 'water_supply','rera_no','website_url','usp_one','usp_two','usp_three','usp_four','usp_five','usp_six','contact_person_name','contact_person_email','contact_person_phone','brokrage_type','brokrage_amount','brokrage_percent'
                    ])
                    ->with([
                        'siteImages' => function ($query) {
                            $query->addSelect('id', 'site_id', 'image_name', 'image_type', 
                                'is_featured', 'is_covered');
                        },
                         
                        'metas' => function ($query) {
                            $query->addSelect('id', 'site_id', 'meta_key', 'meta_value', 'meta_type');
                        },
                        'siteLoans' => function ($query) {
                            $query->addSelect('id', 'site_id', 'bank_id');
                        },
                        'sitemarketers' => function ($query) {
                            $query->addSelect('id', 'site_id', 'person_name', 'person_phone', 'person_email');
                        }
                    ])->find($id);
    }
 
    public static function editSite(Request $request, $id) {

        if ($id > 0) {
           
           $site = Sites::find($id);
           $site = $site->setParameters($request, $site);
           $site->save();

          // self::createUserForSiteHandle($request,$site);

           // Created Code
            $site->code = (!empty($site->code) ? $site->code.sprintf('%05d',$site->id) : sprintf('%05d',$site->id) );
            $site->save();

        }
        return false;  
    }

    public function searchFilteredProperties(Request $request){



        // check for any request
        if ($request->all()) {
            $requestData = (object) $request->all();
        } else {
            $requestData = [];
        }

        $requestData = (array) $requestData;
        
        // format searched data
        if (isset($requestData['searchterm'])) {
            
            $searchedData = array();
            
            // get searched data loop through
            foreach ($requestData['searchterm'] as $k => $v) {
                
                // it will get "A-" (Area), "B-" (Builder) 
                // in $tkey and $tVal will be value
                $tKey = substr($v, 0, 2);
                $tVal = substr($v, 2);

                if ($tKey == 'A-') {
                    $requestData['areas'][] = (int) $tVal;
                } else if ($tKey == 'S-') {
                    $requestData['sites'][] = (int) $tVal;
                }    
            }

            // remove unformatted data
            unset($requestData['searchterm']);
        }

        // send request data back
        $requestData = (object) $requestData;
        
        // search site from database
        $arrSite = Sites::frontSearchSiteList($requestData, $this->page_data['perpage']);

        // get count of sites new vs resale
        $siteCounts = Sites::frontSearchSiteCounts($requestData);
        
        // Login user Id
        $userId = isset($request->userId) && !empty($request->userId) ? $request->userId : ''; 

        // append variable for pagination
        $arrSite->appends($request->all());

        // define variable for sites
        $sites = [];

        // Request Data
        $sites['requestData'] = $requestData;

        // generate pagination related data
        $sites['pagination'] = Helpers::propertyPaginationMetadata($arrSite);
        
        // format search responce
        $sites['sites'] = Helpers::configureSearchResponse($arrSite,true,$userId,$request->userType);

        // send current selected property type
        if(!empty($requestData->propertytype)){
            $sites['propertytype'] = ($requestData->propertytype == 'resale' ? $requestData->propertytype : 'new');
        }else{
            $sites['propertytype'] = '';
        }

        
        // get counts property type wise
        $sites['count']['new_count'] = $sites['count']['resale_count'] = 0;
        if ($siteCounts) {
            foreach ($siteCounts as $count) {
                if ($count->property_type == 1) {
                    $sites['count']['new_count'] = $count->total;
                } else if ($count->property_type == 2) {
                    $sites['count']['resale_count'] = $count->total;
                }
            }
        }

        // send data to respond
        return response()->json([
            'status' => 'success',
            'data' => $sites
        ]);  
        
    }

     public static function frontSearchSiteList($request, $perPage) {
        
        //\DB::enableQueryLog();
        $query = self::select(['id', 'user_id', 'property_type', 'site_name', 'possession_status', 
            'possession_date', 'sample_house', 'sample_house_date', 'address', 'area_id', 'city_id', 'state_id', 
            'price_status', 'sort_number', 'is_featured','latitude','longitude','created_at'])
                
                ->with(['areas', 'cities', 'states', 'siteImages','wishList'])
                ->with(['siteOffers' => function ($query) {
                    $today = \Carbon::today()->toDateString();
                    $query->whereRaw('"'.$today.'" BETWEEN DATE(start_date) AND DATE(end_date)');
                    $query->where('is_verified', '1');
                }])
                ->with(['users' => function ($query) {
                    $query->addSelect('id', 'name','email');
                }])
                ->with(['siteMetas' => function ($query) {
                    $query->addSelect('site_id', 'meta_key', 'meta_value', 'meta_type');
                }])
                ->with(['siteImages' => function ($query) {
                        $query->addSelect('id', 'site_id', 'image_name', 'image_type', 'is_featured', 
                            'is_covered');
                    }])
                /*->with(['companies' => function ($query) {
                    $query->addSelect('id', 'company_name', 'company_logo');
                }])*/

                ->with(['properties' => function ($query) use ($request) {

                    /* select fileds from property */
                    $query->addSelect('id', 'site_id', 'name', 'sub_title', 'price', 'cat_id', 
                        'sub_cat_id', 'transaction_type', 'status');
                    
                    $query->with(['propertyImages' => function ($query) {
                        $query->addSelect('id', 'property_id', 'image_name', 'is_featured', 'is_covered', 'image_type');
                    }]);

                    $query->with(['propertyCategory' => function ($query) {
                        $query->addSelect('id', 'name', 'slug');
                    }]);

                    $query->with(['propertySubCategory' => function ($query) {
                        $query->addSelect('id', 'name', 'slug');
                    }]);

                    $query->with(['propertiesUnitCategory' => function ($query) {
                        $query->addSelect('id', 'property_id', 'cat_id', 'sub_cat_id');
                    }]);

                    $query->with(['propertyFeatures' => function ($query) use ($request) {
                        $query->addSelect(\Helpers::propertyFeaturesFields());
                    }]);

                    $query->with(['propertyMetas' => function ($query) {
                        $query->addSelect('property_id', 'meta_key', 'meta_value');
                    }]);

                    // only active property to select
                    $query->where('status', '1');

                    /* category filter */
                    if (isset($request->propertycategory) && count($request->propertycategory)>0) {

                        $arrCategory[] = $arrSubCategory = [];
                        foreach ($request->propertycategory as $key => $value) {
                            $tmpCategory = explode('|', $value);
                            $arrCategory[] = $tmpCategory[0];
                            $arrSubCategory[] = $tmpCategory[1];
                        }

                        // where or 
                        if (is_array($arrSubCategory) && count($arrSubCategory) > 0) {
                            if (in_array('2', $arrCategory) || in_array('3', $arrCategory)) {
                                $query->whereIn('id', PropertiesUnitCategory::whereIn('sub_cat_id', $arrSubCategory)->pluck('property_id'));
                            } else {
                                $query->whereIn('sub_cat_id', $arrSubCategory);
                            }
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

                    /*  */
                    if (isset($request->furnished_status) && !empty($request->furnished_status)) {
                        if (is_array($request->furnished_status)) {
                            
                            $furnished_status = \Helpers::getStaticValues('furnished_status');

                            $query->where(function ($query) use ($request, $furnished_status) {
                                $furnished_status_options = [];
                                foreach ($request->furnished_status as $value) {
                                    $static_key = array_search($value, $furnished_status);
                                    if ($static_key !== false) {
                                        $furnished_status_options[] = $static_key;
                                    }
                                }
                                
                                // for where or 
                                if (is_array($furnished_status_options) && count($furnished_status_options) > 0) {
                                    $query->orWhereIn('id', PropertyFeatures::whereIn('furnished_status', $furnished_status_options)
                                        ->pluck('property_id'));
                                }

                            });
                        }
                    }
                }]);
        
        /* select only active site */
        $query->where('status', 1)->where('is_suspended', '0')->where('is_soldout', '0');

        /* areas filter */
        if (isset($request->areas) && !empty($request->areas)) {
            if (is_array($request->areas)) {
                $query->whereIn('area_id', $request->areas);
            }
        }

        /* sites filter */
        if (isset($request->sites) && count($request->sites)>0) {
            if (is_array($request->sites)) {
                $query->whereIn('id', $request->sites);
            }    
        }

        /* Only Featured Proeprties */
        if (isset($request->featured) && $request->featured) {
            $query->where('is_featured',1);
        }
        
                
        //  if user is agent then fetch agent shares also
        if (Helpers::isAgent() !== false) {
            $agentPackage = AgentPayments::select(['package_id'])
                            ->where([
                                'user_id' => Helpers::getLoginUserId(),
                                'status' => 1
                            ])->whereRaw('"'.\Carbon::today()->toDateString().'" BETWEEN DATE(subscription_duration_from) AND DATE(subscription_duration_to)')
                            ->first();

            if (isset($agentPackage->package_id)) {
                $query->with(['agentShares' => function ($query) use ($agentPackage) {
                    $query->addSelect('id', 'site_id', 'agent_package', 'shares', 'start_date', 'end_date');
                    $query->whereRaw('"'.\Carbon::today()->toDateString().'" BETWEEN start_date AND end_date');
                    
                    // here this where clause will be according to agent current package
                    $query->where('agent_package', $agentPackage->package_id);
                }]);
            }
        }
        elseif(isset($request->userType) && !empty($request->userType) && $request->userType == '3')
        {   // if app user is agent then fetch agent shares also
            $agentPackage = AgentPayments::select(['package_id'])
                            ->where([
                                'user_id' => $request->userId,
                                'status' => 1
                            ])->whereRaw('"'.\Carbon::today()->toDateString().'" BETWEEN DATE(subscription_duration_from) AND DATE(subscription_duration_to)')
                            ->first();

            if (isset($agentPackage->package_id)) {
                $query->with(['agentShares' => function ($query) use ($agentPackage) {
                    $query->addSelect('id', 'site_id', 'agent_package', 'shares', 'start_date', 'end_date');
                    $query->whereRaw('"'.\Carbon::today()->toDateString().'" BETWEEN start_date AND end_date');
                    
                    // here this where clause will be according to agent current package
                    $query->where('agent_package', $agentPackage->package_id);
                }]);
            }
        }

        // /* property type filter */
        if (isset($request->propertytype)) {

            if(is_array($request->propertytype)){
                $query->whereIn('property_type',$request->propertytype);
            }else{                
                $query->where('property_type', ($request->propertytype == 'resale' ? 2 : 1));
            }
        }

        // /* Bathrooms */
        // if (isset($request->bathroom)) {
        //     $query->whereIn('id',\App\Models\Properties::whereIn('id',\App\Models\PropertyFeatures::whereIn('bathrooms',$request->bathroom)->get()->pluck('property_id'))->get()->pluck('site_id'));
        // }

        /* Min & Max Area */
        if (isset($request->min_area) && $request->min_area && isset($request->max_area) && $request->max_area) {
            $query->whereIn('id',\App\Models\Properties::whereIn('id',\App\Models\PropertyFeatures::whereBetween('carpet_area',[$request->min_area,$request->max_area])->where('carpet_area_unit',$request->are_unit)->get()->pluck('property_id'))->get()->pluck('site_id'));
        }

        /* Road approach */
        if (isset($request->road_approach) && count($request->road_approach) > 0) {
                $query->whereIn('id',\App\Models\Properties::whereIn('id',\App\Models\PropertyFeatures::whereIn('road_approach',$request->road_approach)->get()->pluck('property_id'))->get()->pluck('site_id'));
        }

        /* Price per sq ft */
        if (isset($request->price_per_sqft_min) && !empty($request->price_per_sqft_min) && $request->price_per_sqft_min > 0 && isset($request->price_per_sqft_max) && !empty($request->price_per_sqft_max) && $request->price_per_sqft_max > 0) {
            
            $pricesPerSqft[0] = $request->price_per_sqft_min;
            $pricesPerSqft[1] = $request->price_per_sqft_max;
            // where or 
               $query->whereIn('id',\App\Models\Properties::whereIn('id',\App\Models\PropertyFeatures::whereBetween('price_sq_ft',$pricesPerSqft)->get()->pluck('property_id'))->get()->pluck('site_id'));
        }

        /* Land ideal */
        if (isset($request->land_ideal) && count($request->land_ideal) > 0) {
            $query->whereIn('id',\App\Models\Properties::whereIn('id',\App\Models\PropertyMetas::whereIn('meta_value',$request->land_ideal)->where('meta_key','land_ideal')->get()->pluck('property_id'))->get()->pluck('site_id'));
        }

        /* posted since */
        if (isset($request->posted_since[0]) && !empty($request->posted_since[0])) {
            $posted_since = '';
            $allProp = '';
            switch ($request->posted_since[0]) {
                case 1:
                    //$posted_since = date('Y-m-d h:i:s');
                    $allProp = 'All';
                    break;
                case 2:
                    $posted_since = date('Y-m-d h:i:s',strtotime('-1 days'));
                    break;
                case 3:
                    $posted_since = date('Y-m-d h:i:s',strtotime('-7 days'));
                    break;
                case 4:
                    $posted_since = date('Y-m-d h:i:s',strtotime('-14 days'));
                    break;
                case 5:
                    $posted_since = date('Y-m-d h:i:s',strtotime('-21 days'));
                    break;
                case 6:
                    $posted_since = date('Y-m-d h:i:s',strtotime('-2 months'));
                    break;
                case 7:
                    $posted_since = date('Y-m-d h:i:s',strtotime('-4 months'));
                    break;
            }
            if(!empty($posted_since)){
                //$query->whereRaw('(date(created_at) >= "'.$posted_since.'" and date(created_at) <= "'.$posted_since.'")');
                $query->where([['created_at','>=',$posted_since],['created_at','<=',date('Y-m-d h:i:s')]]);
            }
            if(isset($allProp) && !empty($allProp))
            {
               $query->where([['created_at','<=',date('Y-m-d h:i:s')]]); 
            }
        }

        /* areas filter */
        if (isset($request->areas)) {
            if (is_array($request->areas)) {
                $query->whereIn('area_id', $request->areas);
            }    
        }

        /* sites filter */
        if (isset($request->sites)) {
            if (is_array($request->sites)) {
                $query->whereIn('id', $request->sites);
            }    
        }

        // /* sites filter */
        // if (isset($request->possession_status)) {
        //     if (is_array($request->possession_status)) {
        //         $query->whereIn('possession_status', $request->possession_status);
        //     }    
        // }

        /* Filter By */
        if (isset($request->filter_by) && !empty($request->filter_by)) {
            if(in_array('photos',$request->filter_by)){
                $query->whereIn('id',\App\Models\Properties::whereRaw('id',\App\Models\PropertyImages::get()->pluck('property_id'))->get()->pluck('site_id'));
            }
            if(in_array('videos',$request->filter_by)){
                $query->whereIn('id',\App\Models\Properties::whereRaw('(video_link is not null and video_link != "")')->get()->pluck('site_id'));
            }
            if(in_array('certified_agents',$request->filter_by)){
                $query->whereIn('user_id',\App\Models\AgentPayments::where('status','=','1')->get()->pluck('user_id'));
            }   
            if(in_array('verified_properties',$request->filter_by)){
                $query->where('is_featured',1);
            }     
            if(in_array('rera_properties',$request->filter_by)){
                $query->where(function ($query) {
                    $query->where([['rera_certificate','!=',null],['rera_certificate','!=','']]);
                });
            }   
            if(in_array('7/12 document',$request->filter_by)){
                $query->whereIn('id',\App\Models\Properties::whereIn('id',\App\Models\PropertyImages::where('image_type','7/12 document')->get()->pluck('property_id'))->get()->pluck('site_id'));
            } 
            if(in_array('properties_with_offers',$request->filter_by)){
                $query->whereIn('id',\App\Models\SiteOffers::where([['is_verified','=','1'],['start_date','<=',date('Y-m-d h:i:s')],['end_date','>=',date('Y-m-d h:i:s')]])->pluck('site_id'));
            }            
        }

        /* listed by filters - start */
        if (isset($request->listedby) && is_array($request->listedby)) {
            $arrListedby = [];
            foreach ($request->listedby as $value) {
                switch ($value) {
                    case 'owner':
                        $arrListedby[] = 1;
                        break;
                    case 'agent':
                        $arrListedby[] = 3;
                        break;
                    case 'builder':
                        $arrListedby[] = 2;
                        //$arrListedby[] = 4;
                        break;
                }
            }
            // where or 
            if (is_array($arrListedby) && count($arrListedby) > 0) {
                $query->whereIn('user_id', Users::where('user_type', $arrListedby)->pluck('id'));
            }
        }

        //  possession filters - start 
        if (isset($request->transaction)) {
            if (is_array($request->transaction)) {
                foreach ($request->transaction as $value) {
                    switch ($value) {   
                        case 'buy':
                            $query->whereIn('id', Properties::where('transaction_type', 1)->pluck('site_id'));
                            break;
                        case 'rent':
                            $query->whereIn('id', Properties::where('transaction_type', 2)->pluck('site_id'));
                            break;    
                    }
                }
            }
        }

        /* bhk filters - start */
        if (isset($request->bhk)) {
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
                        $query->orWhereIn('id', 
                            Properties::whereIn('id', 
                                PropertyFeatures::whereIn('bedrooms', $arrBedrooms)->pluck('property_id')
                            )->pluck('site_id')
                        );
                    }

                });
            }
        }

        /* possession filters - start */
        if (isset($request->possession)) {
            if (is_array($request->possession)) {
                
                // for where or 
                $query->where(function ($query) use ($request) {

                    foreach ($request->possession as $value) {
                        switch ($value) {
                            case 'ready_to_move':
                                $query->orWhere('possession_status', '1');
                                break;
                            case 'in_1_year':
                                $query->orWhereBetween('possession_date', 
                                        [date('Y-m-d H:m:s'), date('Y-m-d H:m:s', strtotime("+365 days"))]);
                                break;
                            case 'in_2_year':
                                $query->orWhereBetween('possession_date', 
                                        [date('Y-m-d H:m:s'), date('Y-m-d H:m:s', strtotime("+730 days"))]);
                                break;
                            case 'in_2_plus_year':
                                $query->orWhere('possession_date', '>', date('Y-m-d H:m:s', strtotime("+730 days")));
                                break;
                        }
                    }
                });
            }
        }

        /* property amenities filter */
        if (isset($request->amenities) && is_array($request->amenities)) {
            $arrAmenities = [];
            foreach ($request->amenities as $value) {
                $arrAmenities[] = $value;
            }

            // where or 
            if (is_array($arrAmenities) && count($arrAmenities) > 0) {
                $query->whereIn('id', SiteMetas::whereIn('meta_key', $arrAmenities)
                                        ->where('meta_type', 1)->pluck('site_id'));
            }
        }

        /* category filter */
        if (isset($request->propertycategory) && isset($request->propertycategory)) {
            
            $arrCategory[] = $arrSubCategory = [];
            foreach ($request->propertycategory as $key => $value) {
                $tmpCategory = explode('|', $value);
                $arrCategory[] = $tmpCategory[0];
                $arrSubCategory[] = $tmpCategory[1];
            }

            // where or 
            if (is_array($arrSubCategory) && count($arrSubCategory) > 0) {
                if (in_array('2', $arrCategory) || in_array('3', $arrCategory)) {
                    $query->whereIn('id', Properties::whereIn('id', PropertiesUnitCategory::whereIn('sub_cat_id', $arrSubCategory)->pluck('property_id'))->pluck('site_id'));
                } else {
                    $query->whereIn('id', Properties::whereIn('sub_cat_id', $arrSubCategory)->pluck('site_id'));
                }
            }
        }

        /* budget filter */
        if (isset($request->minbudget) && isset($request->maxbudget)) {    
            if ($request->minbudget != '' && $request->maxbudget != '') {
                $query->whereIn('id', Properties::whereBetween('price', 
                                    array($request->minbudget, $request->maxbudget))->pluck('site_id'));
            } else if ($request->minbudget != '' && trim($request->maxbudget) == '') {
                $query->whereIn('id', Properties::where('price', '>', $request->minbudget)->pluck('site_id'));
            } else if (trim($request->minbudget) == '' && $request->maxbudget != '') {
                $query->whereIn('id', Properties::where('price', '<', $request->maxbudget)->pluck('site_id'));
            }
        }

        // Property on which floor
        if (isset($request->property_on_floor)) {
            foreach ($request->property_on_floor as $value) 
            {
                switch ($value) {
                    case '0':
                        $query->whereIn('id', Properties::whereIn('id', PropertyFeatures::where('property_on_floor',0)->pluck('property_id'))->pluck('site_id'));
                    case '1-5':
                        $query->whereIn('id', Properties::whereIn('id', PropertyFeatures::whereBetween('property_on_floor',[1,5])->pluck('property_id'))->pluck('site_id'));
                        break;
                    case '6-10':
                        $query->whereIn('id', Properties::whereIn('id', PropertyFeatures::whereBetween('property_on_floor',[6,10])->pluck('property_id'))->pluck('site_id'));
                        break;
                    case '10+':
                        $query->whereIn('id', Properties::whereIn('id', PropertyFeatures::where('property_on_floor','>',10)->pluck('property_id'))->pluck('site_id'));
                        break;
                }
            }
        }
        
        /* Furnished status */
        if (isset($request->furnished_status)) {
            if (is_array($request->furnished_status)) {
                
                // $furnished_status = \Helpers::getStaticValues('furnished_status');
                // print_r($request->furnished_status);
                // exit;
                // $query->where(function ($query) use ($request, $furnished_status) {
                //     $furnished_status_options = [];
                //     foreach ($request->furnished_status as $value) {
                //         $static_key = array_search($value, $furnished_status);
                //         if ($static_key !== false) {
                //             $furnished_status_options[] = $static_key;
                //         }
                //     }
                    
                //     // for where or 
                //     if (is_array($furnished_status_options) && count($furnished_status_options) > 0) {
                //         $query->orWhereIn('id', PropertyFeatures::whereIn('furnished_status', $furnished_status_options)
                //             ->pluck('property_id'));
                //     }

                // });
                // print_r(Properties::whereIn('id', PropertyFeatures::whereIn('furnished_status',$request->furnished_status)->pluck('property_id'))->pluck('site_id'));
                // exit;
                $query->whereIn('id', Properties::whereIn('id', PropertyFeatures::whereIn('furnished_status',$request->furnished_status)->pluck('property_id'))->pluck('site_id'));
            }
        }

        /* global city selection filter */
       /* if ($request->city_id) {
            $query->where('city_id',$request->city_id);
        }*/

        if (isset($request->cityId) && !empty($request->cityId)) {
            $query->where('city_id',$request->cityId);
        }

        if(isset($request->siteId) && !empty($request->siteId))
        {
            $query->where('id',$request->siteId);
        }

        $sites = $query->orderBy('sort_number', 'asc')->paginate($perPage);
        
        return $sites;
    }


    public static function frontSearchSiteCounts($request) {
        
        $query = self::select(['property_type', \DB::raw('count(*) as total')])
                ->with(['siteOffers' => function ($query) {
                    $today = \Carbon::today()->toDateString();
                    $query->whereRaw('"'.$today.'" BETWEEN DATE(start_date) AND DATE(end_date)');
                    $query->where('is_verified', '1');
                }]);

        /* select only active site */
        $query->where('status', 1)->where('is_suspended', '0')->where('is_soldout', '0');

        // Get only active properties
        $query->whereIn('id', Properties::where('status','1')->pluck('site_id'));

        
        /* areas filter */
        if (isset($request->areas)) {
            if (is_array($request->areas)) {
                $query->whereIn('area_id', $request->areas);
            }
        }

        /* sites filter */
        if (isset($request->sites)) {
            if (is_array($request->sites)) {
                $query->whereIn('id', $request->sites);
            }    
        }

        /* Sale Or Rent Properties */
        if (isset($request->transaction)) {
            if (is_array($request->transaction)) {
                foreach ($request->transaction as $value) {
                    switch ($value) {   
                        case 'buy':
                            $query->whereIn('id', Properties::where('transaction_type', 1)->pluck('site_id'));
                            break;
                        case 'rent':
                            $query->whereIn('id', Properties::where('transaction_type', 2)->pluck('site_id'));
                            break;    
                    }
                }
            }
        }

        /* bhk filters - start */
        if (isset($request->bhk)) {
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
                        $query->orWhereIn('id', 
                            Properties::whereIn('id', 
                                PropertyFeatures::whereIn('bedrooms', $arrBedrooms)->pluck('property_id')
                            )->pluck('site_id')
                        );
                    }

                });
            }
        }

        /* Furnished status */
        if (isset($request->furnished_status)) {
            if (is_array($request->furnished_status)) {
                
                // $furnished_status = \Helpers::getStaticValues('furnished_status');
                // print_r($request->furnished_status);
                // exit;
                // $query->where(function ($query) use ($request, $furnished_status) {
                //     $furnished_status_options = [];
                //     foreach ($request->furnished_status as $value) {
                //         $static_key = array_search($value, $furnished_status);
                //         if ($static_key !== false) {
                //             $furnished_status_options[] = $static_key;
                //         }
                //     }
                    
                //     // for where or 
                //     if (is_array($furnished_status_options) && count($furnished_status_options) > 0) {
                //         $query->orWhereIn('id', PropertyFeatures::whereIn('furnished_status', $furnished_status_options)
                //             ->pluck('property_id'));
                //     }

                // });
                // print_r(Properties::whereIn('id', PropertyFeatures::whereIn('furnished_status',$request->furnished_status)->pluck('property_id'))->pluck('site_id'));
                // exit;
                $query->whereIn('id', Properties::whereIn('id', PropertyFeatures::whereIn('furnished_status',$request->furnished_status)->pluck('property_id'))->pluck('site_id'));
            }
        }

        /* listed by filters - start */
        if (isset($request->listedby) && is_array($request->listedby)) {
            $arrListedby = [];
            foreach ($request->listedby as $value) {
                switch ($value) {
                    case 'owner':
                        $arrListedby[] = 1;
                        break;
                    case 'agent':
                        $arrListedby[] = 3;
                        break;
                    case 'builder':
                        $arrListedby[] = 2;
                        break;
                }
            }

            // where or 
            if (is_array($arrListedby) && count($arrListedby) > 0) {
                $query->whereIn('user_id', Users::where('user_type', $arrListedby)->pluck('id'));
            }
        }

        /* possession filters - start */
        if (isset($request->possession)) {
            if (is_array($request->possession)) {
                
                // for where or 
                $query->where(function ($query) use ($request) {

                    foreach ($request->possession as $value) {
                        switch ($value) {
                            case 'ready_to_move':
                                $query->orWhere('possession_status', '1');
                                break;
                            case 'in_1_year':
                                $query->orWhereBetween('possession_date', 
                                        [date('Y-m-d H:m:s'), date('Y-m-d H:m:s', strtotime("+365 days"))]);
                                break;
                            case 'in_2_year':
                                $query->orWhereBetween('possession_date', 
                                        [date('Y-m-d H:m:s'), date('Y-m-d H:m:s', strtotime("+730 days"))]);
                                break;
                            case 'in_2_plus_year':
                                $query->orWhere('possession_date', '>', date('Y-m-d H:m:s', strtotime("+730 days")));
                                break;
                            case 'in_3_year':
                                $query->orWhereBetween('possession_date', 
                                        [date('Y-m-d H:m:s'), date('Y-m-d H:m:s', strtotime("+1095 days"))]);
                                break;
                            case 'in_3_plus_year':
                                $query->orWhere('possession_date', '>', date('Y-m-d H:m:s', strtotime("+1095 days")));
                                break;
                        }
                    }
                });
            }
        }

        /* property amenities filter */
        if (isset($request->amenities) && is_array($request->amenities)) {
            $arrAmenities = [];
            foreach ($request->amenities as $value) {
                $arrAmenities[] = $value;
            }

            // where or 
            if (is_array($arrAmenities)) {
                $query->whereIn('id', SiteMetas::whereIn('meta_key', $arrAmenities)
                                                ->where('meta_type', 1)->pluck('site_id'));
            }
        }

         /* Filter By */
        if (isset($request->filter_by) && !empty($request->filter_by)) {
            if(in_array('photos',$request->filter_by)){
                $query->whereIn('id',\App\Models\Properties::whereRaw('id',\App\Models\PropertyImages::get()->pluck('property_id'))->get()->pluck('site_id'));
            }
            if(in_array('videos',$request->filter_by)){
                $query->whereIn('id',\App\Models\Properties::whereRaw('(video_link is not null and video_link != "")')->get()->pluck('site_id'));
            }
            if(in_array('certified_agents',$request->filter_by)){
                $query->whereIn('user_id',\App\Models\AgentPayments::where('status',1)->get()->pluck('user_id'));
            }   
            if(in_array('verified_properties',$request->filter_by)){
                $query->where('is_featured',1);
            }     
            if(in_array('rera_properties',$request->filter_by)){
                $query->where(function ($query) {
                    $query->whereNotNull('rera_certificate');
                    $query->orWhere('rera_certificate',"");
                });
            }   
            if(in_array('7/12 document',$request->filter_by)){
                $query->whereIn('id',\App\Models\Properties::whereIn('id',\App\Models\PropertyImages::where('image_type','7/12 document')->get()->pluck('property_id'))->get()->pluck('site_id'));
            } 
            if(in_array('properties_with_offers',$request->filter_by)){
                $query->whereIn('id',\App\Models\SiteOffers::where([['is_verified','=','1'],['start_date','<=',date('Y-m-d h:i:s')],['end_date','>=',date('Y-m-d h:i:s')]])->pluck('site_id'));
            }            
        }

        /* category filter */
        if (isset($request->propertycategory)) {
         
            $arrCategory[] = $arrSubCategory = [];
            foreach ($request->propertycategory as $key => $value) {
                $tmpCategory = explode('|', $value);
                $arrCategory[] = $tmpCategory[0];
                $arrSubCategory[] = $tmpCategory[1];
            }

            // where or 
            if (is_array($arrSubCategory)) {
                if (in_array('2', $arrCategory) || in_array('3', $arrCategory)) {
                    $query->whereIn('id', Properties::whereIn('id', PropertiesUnitCategory::whereIn('sub_cat_id', $arrSubCategory)->pluck('property_id'))->pluck('site_id'));
                } else {
                    $query->whereIn('id', Properties::whereIn('sub_cat_id', $arrSubCategory)->pluck('site_id'));
                }
            }
        }

        /* budget filter */
        if (isset($request->minbudget) && isset($request->maxbudget)) {    
            if ($request->minbudget != '' && $request->maxbudget != '') {
                $query->whereIn('id', Properties::whereBetween('price', 
                                    array($request->minbudget, $request->maxbudget))->pluck('site_id'));
            } else if ($request->minbudget != '' && trim($request->maxbudget) == '') {
                $query->whereIn('id', Properties::where('price', '>', $request->minbudget)->pluck('site_id'));
            } else if (trim($request->minbudget) == '' && $request->maxbudget != '') {
                $query->whereIn('id', Properties::where('price', '<', $request->maxbudget)->pluck('site_id'));
            }
        }

        // /* property type filter */
        // if (isset($request->propertytype)) {

        //     if(is_array($request->propertytype)){
        //         $query->whereIn('property_type',$request->propertytype);
        //     }else{                
        //         $query->where('property_type', ($request->propertytype == 'resale' ? 2 : 1));
        //     }
        // }

        // /* Bathrooms */
        // if (isset($request->bathroom)) {
        //     $query->whereIn('id',\App\Models\Properties::whereIn('id',\App\Models\PropertyFeatures::whereIn('bathrooms',$request->bathroom)->get()->pluck('property_id'))->get()->pluck('site_id'));
        // }

        /* Road approach */
        if (isset($request->road_approach)) {
            
                $query->whereIn('id',\App\Models\Properties::whereIn('id',\App\Models\PropertyFeatures::whereIn('road_approach',$request->road_approach)->get()->pluck('property_id'))->get()->pluck('site_id'));
        }

        // /* Price per sq ft */
        if (isset($request->price_per_sqft_min) && !empty($request->price_per_sqft_min) && $request->price_per_sqft_min > 0 && isset($request->price_per_sqft_max) && !empty($request->price_per_sqft_max) && $request->price_per_sqft_max > 0) {
            $pricesPerSqft[0] = $request->price_per_sqft_min;
            $pricesPerSqft[1] = $request->price_per_sqft_max;
            // where or 
               $query->whereIn('id',\App\Models\Properties::whereIn('id',\App\Models\PropertyFeatures::whereBetween('price_sq_ft',$pricesPerSqft)->get()->pluck('property_id'))->get()->pluck('site_id'));
        }

        /* Land ideal */
        if (isset($request->land_ideal)) {

            $query->whereIn('id',\App\Models\Properties::whereIn('id',\App\Models\PropertyMetas::whereIn('meta_value',$request->land_ideal)->where('meta_key','land_ideal')->get()->pluck('property_id'))->get()->pluck('site_id'));
        }

        // /* posted since */
        if (isset($request->posted_since[0]) && !empty($request->posted_since[0])) {
            $posted_since = '';
            $allProp = '';
            switch ($request->posted_since[0]) {
                case 1:
                    //$posted_since = date('Y-m-d h:i:s');
                    $allProp = 'All';
                    break;
                case 2:
                    $posted_since = date('Y-m-d h:i:s',strtotime('-1 days'));
                    break;
                case 3:
                    $posted_since = date('Y-m-d h:i:s',strtotime('-7 days'));
                    break;
                case 4:
                    $posted_since = date('Y-m-d h:i:s',strtotime('-14 days'));
                    break;
                case 5:
                    $posted_since = date('Y-m-d h:i:s',strtotime('-21 days'));
                    break;
                case 6:
                    $posted_since = date('Y-m-d h:i:s',strtotime('-2 months'));
                    break;
                case 7:
                    $posted_since = date('Y-m-d h:i:s',strtotime('-4 months'));
                    break;
            }

            if(isset($posted_since) && !empty($posted_since)){
                //$query->whereRaw('(date(created_at) >= "'.$posted_since.'" and date(created_at) <= "'.$posted_since.'")');

                $query->where([['created_at','>=',$posted_since],['created_at','<=',date('Y-m-d h:i:s')]]);
            }

            if(isset($allProp) && !empty($allProp))
            {
                $query->where([['created_at','<=',date('Y-m-d h:i:s')]]);   
            }
        }

        // Property on which floor
        if (isset($request->property_on_floor)) {
            foreach ($request->property_on_floor as $value) 
            {
                switch ($value) {
                    case '0':
                        $query->whereIn('id', Properties::whereIn('id', PropertyFeatures::where('property_on_floor',0)->pluck('property_id'))->pluck('site_id'));
                    case '1-5':
                        $query->whereIn('id', Properties::whereIn('id', PropertyFeatures::whereBetween('property_on_floor',[1,5])->pluck('property_id'))->pluck('site_id'));
                        break;
                    case '6-10':
                        $query->whereIn('id', Properties::whereIn('id', PropertyFeatures::whereBetween('property_on_floor',[6,10])->pluck('property_id'))->pluck('site_id'));
                        break;
                    case '10+':
                        $query->whereIn('id', Properties::whereIn('id', PropertyFeatures::where('property_on_floor','>',10)->pluck('property_id'))->pluck('site_id'));
                        break;
                }
            }
        }

        /* Min & Max Area */
        if (isset($request->min_area) && $request->min_area && isset($request->max_area) && $request->max_area) {
            $query->whereIn('id',\App\Models\Properties::whereIn('id',\App\Models\PropertyFeatures::whereBetween('carpet_area',[$request->min_area,$request->max_area])->where('carpet_area_unit',$request->are_unit)->get()->pluck('property_id'))->get()->pluck('site_id'));
        }

        // // /* sites filter */
        // if (isset($request->possession_status)) {
        //     if (is_array($request->possession_status)) {
        //         $query->whereIn('possession_status', $request->possession_status);
        //     }    
        // }


        /* global city selection filter */
        if (\session('cityId') > 0) {
            $query->where('city_id', \session('cityId'));
        }

        if (isset($request->cityId) && !empty($request->cityId)) {
            $query->where('city_id',$request->cityId);
        }

        if(isset($request->siteId) && !empty($request->siteId))
        {
            $query->where('id',$request->siteId);
        }

        $sites = $query->groupBy('property_type')->get();
    
        return $sites;

    }
}
