<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
class PropertyFeatures extends Model
{
    use HasFactory;
    protected $table = 'tbl_property_features';

    public static function processPropertyFeatures(Request $request, int $propId, string $action = 'add') {
     	
       
        if (isset($propId) && $propId > 0) {

            if ($action == 'edit') {
                // delete features first
                PropertyFeatures::where('property_id', $propId)->delete();
            }

            if (is_array($request->features)) {
                $features = new PropertyFeatures;
                $features->property_id = $propId;
                $features = $features->setParameters($request, $features);
                $features->save();
                return true;
            }
        }
          
        return false;
    }

    private static function setParameters($request, $features) {

        $features->bedrooms                 = $request->features['bedrooms'];
        $features->bathrooms                = $request->features['bathrooms'];
        $features->balconies                = $request->features['balconies'];

        $features->foyer_area               = $request->features['foyer_area'];
        $features->store_room               = $request->features['store_room'];
        $features->pooja_room               = $request->features['pooja_room'];
        $features->study_room               = $request->features['study_room'];
        $features->parking_area             = $request->features['parking_area'];
        $features->open_sides               = $request->features['open_sides'];
        $features->total_unit               = $request->features['total_unit'];
        $features->servant_room             = $request->features['servant_room'];

        if(isset($request->features['shed_area'])){
            $features->shed_area                = $request->features['shed_area'];            
            $features->shed_area_unit           = $request->features['shed_area_unit'];
            $features->shed_height              = $request->features['shed_height'];
            $features->shed_height_unit         = $request->features['shed_height_unit'];
        }else{
            $features->shed_area = '';
            $features->shed_area_unit = '';
            $features->shed_height = '';
            $features->shed_height_unit = '';
        }
        
        $features->electricity_connection   = isset($request->features['electricity_connection']) ? $request->features['electricity_connection'] : '';
        $features->crane_facility           = isset($request->features['crane_facility']) ? $request->features['crane_facility'] : '';
        $features->etp  = isset($request->features['etp']) ? $request->features['etp'] : '';

        if(isset($request->features['sb_area'])){
            $features->sb_area                  = $request->features['sb_area'];
            $features->sb_area_unit             = $request->features['sb_area_unit'];
        }else{
            $features->sb_area = '';
            $features->sb_area_unit = '';
        }
        if(isset($request->features['carpet_area'])){
            $features->carpet_area              = $request->features['carpet_area'];
            $features->carpet_area_unit         = $request->features['carpet_area_unit'];
        }else{
            $features->carpet_area = '';
            $features->carpet_area_unit = '';
        }
        if(isset($request->features['built_area'])){
            $features->built_area               = $request->features['built_area'];
            $features->built_area_unit          = $request->features['built_area_unit'];
        }else{
            $features->built_area = '';
            $features->built_area_unit = '';
        }
        if(isset($request->features['plot_area'])){
            $features->plot_area                = $request->features['plot_area'];
            $features->plot_area_unit           = $request->features['plot_area_unit'];
        }else{
            $features->plot_area = '';
            $features->plot_area_unit = '';
        }
        if(isset($request->features['price_sq_ft'])){
            $features->price_sq_ft              = $request->features['price_sq_ft'];
        }else{
            $features->price_sq_ft = '';
        }
        if(isset($request->features['area_covered'])){
            $features->area_covered             = $request->features['area_covered'];
            $features->area_covered_unit        = $request->features['area_covered_unit'];
        }else{
            $features->area_covered = '';
            $features->area_covered_unit = '';
        }
        $features->total_floors             = $request->features['total_floors'];
        $features->property_on_floor        = (isset($request->features['property_on_floor']) ? $request->features['property_on_floor'] : '');

        $features->vastu = (isset($request->features['vastu']) ? $request->features['vastu'] : '');
        $features->no_of_towers = (isset($request->features['no_of_towers']) ? $request->features['no_of_towers'] : '');
        $features->no_of_flats = (isset($request->features['no_of_flats']) ? $request->features['no_of_flats'] : '');        
        return $features;
    }
    
}
