<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateUserRequest extends Request
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
        
            'first_name' => 'max:255|required',
            'last_name' => 'max:255|required',
            'business_name' => 'max:255',
            'picture'   => 'image|max:5000',
            'business_logo' => 'image|max:5000',
            'country' => 'max:2',
            'state' => 'max:2'

        ];
    }
}
