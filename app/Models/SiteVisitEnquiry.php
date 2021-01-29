<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteVisitEnquiry extends Model
{
    use HasFactory;

    protected $table = 'tbl_site_visit_enquiry'; 
    
    protected $fillable = [
        'name', 'mobile_no', 'email', 'site_id',
    ];


}
