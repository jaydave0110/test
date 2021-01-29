<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingLoanRequest extends FormRequest
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

     
    public function rules()
    {
         return [
            
            'bank_name' => 'required',
            'amount_sanction' => 'required',
            'la_amount' => 'required',
            'emi' => 'required',
        ];
    }

    public function messages() {
        return [
             
            'bank_name.required'  => 'Bank Name is mandatory',
            'amount_sanction.required' => 'Amount Sanction is mandatory',
            'la_amount.required' => 'Loan Amount is mandatory',
            'emi.required' => 'EMI Amount is mandatory'

            
        ];
    }
}
