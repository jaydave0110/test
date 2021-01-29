<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SitesRequest extends FormRequest 
{

    public function authorize() {    
        return true;
    }

    public function rules() {        
        return [
        
            'site_name' => 'required',
            
            'state_id' => 'required',
            'city_id' => 'required',
            'area_id' => 'required',
            
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'loan_approval' => 'required',
            'price_status' => 'required',
            'possession_status' => 'required',
             
        ];
    }

    public function messages() {
        return [
             
            'site_name.required'  => 'Site name is mandatory',
             
            'state_id.required' => 'Selection of state is mandatory',
            'city_id.required' => 'Selection of city is mandatory',
            'area_id.required' => 'Selection of area is mandatory',
            'address.required' => 'You must enter address of site to proceed.',
            'latitude.required' => 'You must enter latitude of site to proceed.',
            'longitude.required' => 'You must enter longitude of site to proceed.',
            'loan_approval.required' => 'You must select loan option to available or not.',
            'price_status.required' => 'You must select how price is being display in front to proceed.',
            'possession_status.required'  => 'Please provide possesion status',
        ];
    }

}
