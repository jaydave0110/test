<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
class SiteMarketers extends Model
{
    use HasFactory;

    protected $table = 'tbl_site_marketers';


    public static function processSiteMarketers(Request $request, int $siteId, string $action = 'add') {
    	
    	if (isset($siteId) && $siteId > 0) {
    		if (is_array($request->mp_name) && is_array($request->mp_phone)) {
	        	
	        	// if action is edit then clean this site marketers
	        	if ($action == 'edit') {
	        		SiteMarketers::where('site_id', $siteId)->delete();	
	        	}

	        	$data = array();
	        	foreach ($request->mp_name as $key => $val) {
		            if ($val != '' || $request->mp_phone[$key] != '') {
		                $data[$key]['site_id'] = $siteId;
		                $data[$key]['person_name'] = $val;
		                $data[$key]['person_phone'] = $request->mp_phone[$key];
		                $data[$key]['person_email'] = isset($request->mp_email[$key]) ? $request->mp_email[$key] : '';
		            }
		        }
		        SiteMarketers::insert($data);
		        return true;
	        }
    	}
    	return false;
    }
}
