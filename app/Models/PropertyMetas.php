<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
class PropertyMetas extends Model
{
    use HasFactory;
    protected $table = 'tbl_property_metas';
    protected $fillable = [
        'property_id',
        'meta_key',
        'meta_value'
    ];

    public static function processPropertyMetas($metas, $property_id, $action = 'add') {

		if (is_array($metas)) {

            $data = array();
	        $i = 0;

	    	foreach ($metas as $mk => $mv) {
	    		
	    		/* bedrooms metas */
	    		if ($mk == 'bedrooms') {
	    			foreach ($metas[$mk] as $key => $val) {
			    		if ($key == 'master_bedroom') {
			    			$data[$i]['property_id']	= $property_id;
				    		$data[$i]['meta_key']		= 'master_bedroom';
						    $data[$i]['meta_value']		= $metas['bedrooms']['master_bedroom'];
						    $data[$i]['created_at']		= date('Y-m-d H:i:s');
						    $i++;
			    		} else {
				    		foreach ($val as $k => $v) {
								$data[$i]['property_id']	= $property_id;
								$data[$i]['meta_key']		= $k.'_'.$key;
					    		$data[$i]['meta_value']		= ($v != '' && $v != null && $v != 'no' ? $v : 'no');
								$data[$i]['created_at']		= date('Y-m-d H:i:s');
								$i++;
							}
						}
				    }
	    		} elseif ($mk == 'floors') {
                    foreach ($metas[$mk] as $key => $val) {
                        foreach ($val as $k => $v) {
                        	// remove comma from numberic values
                        	$v = str_replace( ',', '', $v);
                            $data[$i]['property_id']	= $property_id;
                            $data[$i]['meta_key']		= 'floor_'.$key.'_'.$k;
                            $data[$i]['meta_value']		= ($v != '' && $v != null && $v != 'no' ? $v : 'no');
                            $data[$i]['created_at']		= date('Y-m-d H:i:s');
                            $i++;
                        }
                    }
                } else {
	    			/* common metas */
	    			$data[$i]['property_id']	= $property_id;
		    		$data[$i]['meta_key']		= $mk;
				    $data[$i]['meta_value']		= ($mv != '' && $mv != null && $mv != 'no' ? $mv : 'no');
				    $data[$i]['created_at']		= date('Y-m-d H:i:s');
				    $i++;
	    		}
	    	}

            // action edit then clean all meta related to current property
            if ($action == 'edit') {
                PropertyMetas::where('property_id', $property_id)->delete();
            }

	    	PropertyMetas::insert($data);
		    return true;
		}
	}
	
    
}
