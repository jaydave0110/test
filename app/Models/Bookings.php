<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Bookings extends Model
{
    use HasFactory;
    use SoftDeletes;
     protected $table = 'tbl_bookings';
     protected $softDelete = true;

    public function sites() {
        return $this->belongsTo(Sites::class, 'site_id', 'id');
    }

    public function properties() {
        return $this->belongsTo(Properties::class, 'site_id', 'site_id');
    }

    public function sitesoffers() {
        return $this->belongsTo(SiteOffers::class, 'package', 'id');
    }

    public function users() {
        return $this->belongsTo(User::class, 'broker_id', 'id');
    }
    
     
 
    
    
}
