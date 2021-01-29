<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyRequest extends FormRequest
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
            'sub_title' =>'required',
            'cat_id' =>'required',
             
            'status'  =>'required',
            
        ];
    }

    public function messages() {
        
        return [
            'sub_title.required' => 'Sub Title is mandatory',
            'cat_id.required' => 'Property Type is mandatory',
             
            'status.required'  => 'Property Status is mandatory',
        ];
    }



}
