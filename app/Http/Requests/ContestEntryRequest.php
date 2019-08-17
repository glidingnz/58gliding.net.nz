<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContestEntryRequest extends FormRequest
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
            'contest_name'=> 'required',
            'classes_id'=> 'required',
            'first_name' => 'required',
            'last_name'  => 'required',
            'is_copilot' => 'boolean',
            'mobile' => 'required|regex:/([+(\d]{1})(([\d+() -.]){5,16})([+(\d]{1})/',
            'email'=>'email|unique_with:entries,contest_id',
            'address_1' =>'required',
            'club' => 'required',
            'e_contact' => 'required',
            'e_mobile' => 'required|regex:/([+(\d]{1})(([\d+() -.]){5,16})([+(\d]{1})/',
            'e_address_1' => 'required',
            'glider' => 'required_unless:is_copilot,1',
            'handicap' => 'required_unless:is_copilot,1',
            'wingspan' => 'required_unless:is_copilot,1',
        ];
    }

    public function messages(){
        return[
            'contest_name.required'=> 'Contest Name is Not Set',
            'classes_id.required'=> 'Choose a Contest Class',
            'first_name.required' => 'First Name cannot be blank',
            'last_name.required'  => 'Last Name cannot be Blank',
            'mobile.required' => 'Mobile Phone Cannot be Blank',
            'mobile.regex' => 'Mobile Phone number is not valid',
            'email.unique_with'=>'You have already entered this contest with that Email Address',
            'email.email' => 'Email Address is not Valid',
            'address_1.required' =>'Address cannot be blank',
            'club.required' => 'Club Name cannot be blank',
            'e_contact.required' => 'Emergency Contact Name cannot be blank',
            'e_mobile.required' => 'Emergency Contact Mobile Phone Cannot be Blank',
            'e_mobile.regex' => 'Emergency Contact Mobile Phone number is not valid',
            'e_address_1.required' => 'Emergency Contact Address cannot be blank',
            'glider.required_unless' => 'Glider Reg cannot be blank',
            'handicap.required_unless' => 'Glider Handicap cannot be blank',
            'wingspan.required_unless' => 'Glider Winspan cannot be blank',
        ];
    }
}
