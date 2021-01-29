<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingsRequest extends FormRequest
{
     
    public function authorize()
    {
        return true;
    }

     
    public function rules()
    { 
         return [
            
            'package' => 'required',
            'handle_by' => 'required',
            'customer_name' => 'required',
            'amount' => 'required',
            'final_amount' => 'required',
            'booking_status' => 'required' 
        ];
    }

    public function messages() {
        return [
             
            'package.required'  => 'Site Offer is mandatory',
            'handle_by.required'  => 'Handled By is mandatory', 
            'customer_name.required'  => 'Customer Name By is mandatory', 
            'amount.required'  => 'Amount Price is mandatory', 
            'final_amount.required'  => 'Final Amount Price is mandatory', 
            'booking_status.required'  => 'Booking Status is required'  
            
        ];
    }
}
