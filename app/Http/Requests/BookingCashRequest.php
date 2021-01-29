<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingCashRequest extends FormRequest
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
            
            'payment_type' => 'required',
            'status' => 'required',
            'amount' => 'required',
        ];
    }

    public function messages() {
        return [
             
            'payment_type.required'  => 'Cash Payments Type is mandatory',
            'status.required' => 'Cash Payment Status is mandatory',
            'amount.required' => 'Cash Amount is mandatory'

            
        ];
    }
}
