<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
class PropertiesUnitCategory extends Model
{
    use HasFactory;
    protected $table = 'tbl_properties_unit_category';
    protected $fillable = [
        'property_id',
        'cat_id',
        'sub_cat_id'
    ];


    public function properties() {
        return $this->belongsTo(Properties::class, 'property_id', 'id');
    }

    public function propertyCategory() {
        return $this->belongsTo(PropertyCategory::class, 'cat_id', 'id');
    }

    public function propertySubCategory() {
        return $this->belongsTo(PropertySubCategory::class, 'sub_cat_id', 'id');
    }

    public static function processPropertyCategory($request, $property_id, $action = 'add') {

        if ($property_id > 0) {

            if ($action == 'edit') {
                // delete already applied category
                PropertiesUnitCategory::where('property_id', $property_id)->delete();
            }

            // if category is in commercial and residential then process
            if (in_array($request->cat_id, [2, 3])) {
                
                if (is_array($request->sub_cat_id) && count($request->sub_cat_id) > 0) {

                    $data = array();
                    $i = 0;
                    
                    foreach ($request->sub_cat_id as $sub_cat_id) {
                        $data[$i]['property_id'] = $property_id;
                        $data[$i]['cat_id'] = $request->cat_id;
                        $data[$i]['sub_cat_id'] = $sub_cat_id;
                        $i++;
                    }

                    PropertiesUnitCategory::insert($data);
                    return true;
                }else{
                    $data = array(
                        array(
                            'property_id' => $property_id,
                            'cat_id' => $request->cat_id,
                            'sub_cat_id' => $request->sub_cat_id,
                        )
                    );                    
                    PropertiesUnitCategory::insert($data);
                    return true;
                }
            }
        }
        return false;
    }
}
