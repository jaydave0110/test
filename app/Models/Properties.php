<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
class Properties extends Model
{
	protected $table = 'tbl_properties';
    use HasFactory;

    public function escalations() {
        return $this->hasMany(ResidentEscalation::class,'property_id','id');
    }

    public function wishList() {
        return $this->belongsTo(WishList::class, 'id', 'property_id');
    }

     

    public function bookings() {
        return $this->belongsTo(Bookings::class, 'site_id', 'site_id');
    }

    public function propertyCategory() {
        return $this->belongsTo(PropertyCategory::class, 'cat_id', 'id');
    }

    public function propertySubCategory() {
        return $this->belongsTo(PropertySubCategory::class, 'sub_cat_id', 'id');
    }

    public function sites() {
        return $this->belongsTo(Sites::class, 'site_id', 'id');
    }
    
    public function siteOffers() {
        return $this->hasMany(SiteOffers::class,'property_id','id');
    }

    public function propertyImages() {
        return $this->hasMany(PropertyImages::class, 'property_id', 'id');
    }

    public function propertyFeatures() {
        return $this->hasOne(PropertyFeatures::class, 'property_id', 'id');
    }

    public function propertyMetas() {
        return $this->hasMany(PropertyMetas::class, 'property_id', 'id');
    }

    public function propertiesUnitCategory() {
        return $this->hasMany(PropertiesUnitCategory::class, 'property_id', 'id');
    }

    public static function createProperty(Request $request) {

        $property = new Properties;
        $property = $property->setParameters($request, $property);
        $property->save();

        return $property->id;
    }

    public static function editProperty(Request $request, $id) {

        if ($id > 0) {
           
           $property = Properties::find($id);
           $property = $property->setParameters($request, $property);
           $property->save();

        }
        return false;
    }

    private static function setParameters($request, $property) {

        $property->code                     = $request->code;
        $property->transaction_type         = $request->transaction_type;
        $property->cat_id                   = $request->cat_id;
        $property->city_id                  = $request->city_id;
        // if category not in commercial and residential
       /* if ( !in_array($request->cat_id, [2, 3])) {
            if (isset($request->sub_cat_id[0])) {
                $property->sub_cat_id           = $request->sub_cat_id[0];
            } else {
                $property->sub_cat_id           = 0;
            }
        } else {
            $property->sub_cat_id           = 0;
        }*/
        
          $property->sub_cat_id           = $request->sub_cat_id;

        $property->site_id                  = $request->site_id;
        
        $property->sub_title                = $request->sub_title;
        $property->description              = $request->description;
        
        $property->price                    = isset($request->price) ? $request->price : (isset($request->meta['floors']) && count($request->meta['floors']) > 0 ? min(array_column($request->meta['floors'],'price')) : null);
        

        if($request->status){
            $property->status                    = $request->status;
        }

        if($request->is_featured){
            $property->is_featured                    = $request->is_featured;
        }
        
        if($request->is_popular){
            $property->is_popular                    = $request->is_popular;
        }

        if($request->brokrage_type==1){
            
            $property->brokrage_type = $request->brokrage_type;
            $property->fix_pay_type = $request->fix_pay_type;
            $property->fix_pay_amount =  $request->fix_pay_amount;
        } 

        if($request->is_commission==1){
            $property->is_commission = $request->is_commission;
            $property->commission_percent = $request->commission_percent;
            $property->commission_basic_price =  $request->commission_basic_price;
            $property->commission_amount =  $request->commission_amount;
        } 


        if($request->is_escalation==1)
        {
            $property->is_escalation      =  $request->is_escalation;
            $property->is_garden_facing      =  $request->is_garden_facing;
            $property->garden_facing_amount  =  $request->garden_facing_amount;
            $property->is_club_house_facing      =  $request->is_club_house_facing;
            $property->club_house_facing_amount  =  $request->club_house_facing_amount;
            $property->id_road_facing        =  $request->id_road_facing;
            $property->road_facing_amount    =  $request->road_facing_amount;
            $property->is_croner_flat        =  $request->is_croner_flat;
            $property->corner_flat_amount    =  $request->corner_flat_amount;
            $property->is_others             =  $request->is_others;
            $property->other_amount          =  $request->other_amount;
            
        }

        $property->total_escalation_floors              = $request->total_escalation_floors;
        //dd($request->all());
        
        return $property;
    }


    public static function getWishListProperties($request){


        $query = Properties::select(['id', 'site_id', 'cat_id', 'sub_cat_id', 
                    'sub_title', 'description', 'video_link', 'transaction_type', 'price', 'status', 'sort_num','number'])
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
                    $query->with('wishList');    
                     
                    
                    
                    $query = $query->whereIn('id',\App\Models\WishList::where('user_id',$request->user_id)->pluck('property_id'))->paginate(10);


 

                return $query;
    }



}
