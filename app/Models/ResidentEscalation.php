<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
class ResidentEscalation extends Model
{
    use HasFactory;
     
    protected $table = 'tbl_resident_eascalation'; 

    public static function processEscaltion(Request $request, int $propId, string $action = 'add')
    {
    	
    	if (isset($propId) && $propId > 0) {

            if ($action == 'edit') {
                // delete features first
                ResidentEscalation::where('property_id', $propId)->delete();
            }

            if (is_array($request->escalation_floor_price)) {
                foreach ($request->input("escalation_floor_price") as $key=> $escalation){
        				        
					       
					        $add_hobby = new ResidentEscalation;
					        $add_hobby->site_id= $request->site_id;
					        $add_hobby->property_id= $propId;
					        $add_hobby->floor= $key;
					        $add_hobby->price= $escalation;
					        $add_hobby->save();
					}

                return true;
            }
        }
    }

}
