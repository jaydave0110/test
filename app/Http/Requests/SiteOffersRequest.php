<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteOffersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
         return [
            
            
            'option_name' => 'required',
            'final_price' => 'required',
        ];
    }

    public function messages() {
        return [
             
            'option_name.required'  => 'Option Name is mandatory', 
            'final_price.required'  => 'Final Price is mandatory', 
            
        ];
    }

}
