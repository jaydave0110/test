<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
class SiteLoans extends Model
{
    use HasFactory;

    protected $table = 'tbl_site_loans';
    protected $fillable = [
        'site_id',
        'bank_id'
    ];

    public static function processSiteLoanDetails($request, $siteId, $action = 'add') {

    	if (isset($request->banks) && is_array($request->banks)) {

    		$data = array();
	        $i = 0;

    		foreach ($request->banks['loan'] as $key => $val) {
    			$data[$i]['site_id']		= $siteId;
	    		$data[$i]['bank_id']		= $val;
			    $data[$i]['created_at']		= date('Y-m-d H:i:s');
				$i++;
	    	}	

	    	// action edit then clean all loan related data
			if ($action == 'edit') {
                self::where('site_id', $siteId)->delete();
			}

		    self::insert($data);
		    return true;
    	}
	}
    
}
