<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteOffers extends Model
{
    use HasFactory;

    protected $table = 'tbl_site_offers';

    public function sites() {
        return $this->belongsTo(Sites::class, 'site_id', 'id');
    }
    public function properties() {
        return $this->belongsTo(Properties::class, 'property_id', 'id');
    }
    
   
    public static function getSiteOffersForAdmin($request, $perpage = 10) {
        $SiteOffers = SiteOffers::with(['sites']);

        if ($request) {
            if ($request->site_name) {
                $SiteOffers = $SiteOffers->whereIn('site_id', 
                    Sites::where('site_name', 'like', $request->site_name)->pluck('id')
                );
            }
        }
        return $SiteOffers->orderBy('id', 'desc')->paginate($perpage);

    }
    
}
