<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipPackages extends Model {
	
	protected $table = 'tbl_membership_packages';
    protected $fillable = [
        'package_name',
        'package_for',
        'package_type',
        'add_property_limit_no',
        'enquiry_limit_no',
        'sort_order',
        'details',
        'created_by'
    ];

    public function adminUsers() {
        return $this->belongsTo(Admin\AdminUsers::class, 'created_by', 'id');
    }

    public function packagePricing() {
        return $this->hasMany(MembershipPricing::class, 'package_id', 'id');
    }

    public function membershipPricing() {
        return $this->hasMany(MembershipPricing::class, 'package_id', 'id');
    }

    public static function getMembershipPackagesForAdmin($request, $perpage) {
        $package = MembershipPackages::where('is_deleted', 0)
                    ->with(['packagePricing' => function ($query) use ($request) {
                        $query->with(['cities' => function ($query) {
                            $query->addSelect('id', 'name');
                        }]);
                    }]);
        if ($request) {
            if ($request->package_name != '') {
                $package = $package->where('package_name', 'like', '%' . $request->package_name . '%');
            }
            if ($request->package_for != '') {
                $package = $package->where('package_for', $request->package_for);
            }
            if ($request->package_type != '') {
                $package = $package->where('package_type', $request->package_type);
            }
        }
        return $package->orderBy('id', 'desc')->paginate($perpage);
    }

    public static function addMembershipPackage($request) {

       
       
        $package                           = new MembershipPackages;
        $package->package_for              = $request->package_for;
        $package->package_type             = $request->package_type;
        $package->package_name             = $request->package_name;
        $package->details                  = $request->details;
        $package->add_property_limit_no    = $request->add_property_limit_no;
        $package->enquiry_limit_no         = $request->enquiry_limit_no;
        $package->sort_order               = $request->sort_order;
        $request->created_by               = $request->session()->get('AdmId');
        $package->save();
        
        return $package->id;

    }

    public static function processMembershipPackage($id, $request) {
        
        if ($id > 0) {
        
            $package                           = MembershipPackages::find($id);
            $package->package_for              = $request->package_for;
            $package->package_type             = $request->package_type;
            //$package->is_microsite             = $request->is_microsite == '1' ? '1' : '0';
            $package->package_name             = $request->package_name;
            $package->details                  = $request->details;
            $package->add_property_limit_no    = $request->add_property_limit_no;
            $package->enquiry_limit_no         = $request->enquiry_limit_no;
            $package->sort_order               = $request->sort_order;
            $package->save();
            return true;
        
        }
        return false;
    }

}
