<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public function rules()
    {
        return  [
            'name' => 'required',
            'email' => 'required|email',
            'text' => 'required',
            'g-recaptcha-response' => 'required|captcha',
        ];
    }

    public function messages(){
        return [
            'g-recaptcha-response.required' => 'The recaptcha field is required.',
            'g-recaptcha-response.captcha'  => 'The recaptcha field is required.',
        ];
    }
}
