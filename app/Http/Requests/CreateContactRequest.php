<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class CreateContactRequest extends Request
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
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|unique:contacts,email,NULL,id,user_id,' . Auth::id(),
            'phone' => 'phone:AUTO,US' //'digits:10'

        ];
    }

    /**
     * [messages description]
     * @return [type] [description]
     */
    public function messages()
    {
        return [
            
            'email.unique' => 'You have already added this contact email',

        ];
    }
}
