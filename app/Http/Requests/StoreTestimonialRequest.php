<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreTestimonialRequest extends Request
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

            'contact_id' => 'required|exists:contacts,id',
            'user_id' => 'required|exists:contacts,user_id|exists:users,id',
            'rating' => 'integer|max:5|min:1',
            'email' => 'email|required',
            'body' => 'required_without:token',
            'token' => 'required_without:body',
            'thumbnail' => 'required_with:token',
            'url' => 'required_with:token',
            'invite_token' => 'required',
            //'g-recaptcha-response' => 'required|recaptcha',

        ];
    }

    /**
     * [messages description]
     * @return [type] [description]
     */
    public function messages()
    {
        return [
                'token.required_without' => 'Please add a video if not adding any text',
                'body.required_without' => 'Please add some text if not adding a video',
                'email.email' => 'Email is not valid',
                'email.required' => 'Email is required',
                'invite_token.required' => 'required'
            ];
    }
}
