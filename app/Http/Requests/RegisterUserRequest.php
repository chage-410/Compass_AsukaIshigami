<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'over_name' => ['required', 'string', 'max:10'],
            'under_name' => ['required', 'string', 'max:10'],
            'over_name_kana' => ['required', 'string', 'regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u', 'max:30'],
            'under_name_kana' => ['required', 'string', 'regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u', 'max:30'],
            'mail_address' => ['required', 'max:100', 'unique:users,mail_address', 'email'],
            'sex' => ['required'],
            'old_year' => ['required', 'integer'],
            'old_month' => ['required', 'between:1,12'],
            'old_day' => ['required', 'between:1,31'],
            'role' => ['required'],
            'password' => ['required', 'confirmed', 'min:8', 'max:30', Rules\Password::defaults()],
            'subject' => ['array', 'nullable'],
        ];
    }
}
