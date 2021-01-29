<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesHeadRequest extends FormRequest
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
    {   $id = $this->segment(2) ? $this->segment(2) : '';
         
        if($id!="")
        {
            return [
                'name' =>'required',
                'email'=>'required|unique:users,email,'.$id,
                'phone'=>'required',
                'state'=>'required',
                'city' =>'required'
                
            ];

        } else {
            return [
                'name' =>'required',
                'email'=>'required|unique:users,email',
                'password'  =>'required|min:6',
                'phone'=>'required',
                'state'=>'required',
                'city' =>'required'
                
            ];            
        }
    }

    public function messages() {
        
        return [
            'name.required' => 'Name is mandatory',
            'email.required' => 'Email Address is mandatory',
            'password.required'=> 'Password is mandatory',
            'phone.required'=> 'Phone is mandatory',
            'state.required'=> 'State is mandatory',
            'city.required'=> 'City is mandatory',
        ];
    }
}
