<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
class SiteMetas extends Model
{
    use HasFactory;

    protected $table = 'tbl_site_metas';
    protected $fillable = [
        'site_id',
        'meta_key',
        'meta_value',
        'meta_type'
    ];

    public static function processSiteMetas(Request $request, int $siteId, string $action = 'add') {
    
        if (isset($siteId) && $siteId > 0) {

            // if action is edit then clean this site meta first
            if ($action == 'edit') {
                SiteMetas::where('site_id', $siteId)->delete();
            }

            if (is_array($request->amenity)) {
                
                $data = array();
                $i = 0;

                /* amenities > meta type = 1 */
                foreach ($request->amenity as $key => $val) {
                    $data[$i]['site_id'] = $siteId;
                    $data[$i]['meta_key'] = $key;
                    $data[$i]['meta_value'] = $val;
                    $data[$i]['meta_type'] = 1;
                    $i++;
                }

                /* flooring > meta type 2 */
                foreach ($request->specs as $key => $val) {
                    if ($val != '') {
                        $data[$i]['site_id'] = $siteId;
                        $data[$i]['meta_key'] = $key;
                        $data[$i]['meta_value'] = $val;
                        $data[$i]['meta_type'] = 2;
                    } 
                    $i++;
                }
                
                SiteMetas::insert($data);
                return true;
            }
        }
        return false;
    }

    
}
