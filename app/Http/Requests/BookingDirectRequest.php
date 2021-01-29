<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingDirectRequest extends FormRequest
{
     
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
         return [
            
            'payment_type' => 'required',
            'status' => 'required',
            'amount' => 'required',
        ];
    }

    public function messages() {
        return [
             
            'payment_type.required'  => 'Direct Payments Type is mandatory',
            'status.required' => 'Direct Payment Status is mandatory',
            'amount.required' => 'Direct Amount is mandatory'

            
        ];
    }
     
     
}
