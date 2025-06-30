<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Support\Carbon;

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
            'sex' => ['required', 'in:1,2,3'],
            'old_year' => ['required', 'integer'],
            'old_month' => ['required', 'integer', 'between:1,12'],
            'old_day' => ['required', 'integer', 'between:1,31'],
            'role' => ['required', 'in:1,2,3,4'],
            'password' => ['required', 'confirmed', 'min:8', 'max:30', Rules\Password::defaults()],
            'subject' => ['array', 'nullable'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $year = (int) $this->input('old_year');
            $month = (int) $this->input('old_month');
            $day = (int) $this->input('old_day');

            // 日付として正しいか
            if (!checkdate($month, $day, $year)) {
                $validator->errors()->add('birth_day', '正しい生年月日を選択してください。');
                return;
            }

            // 2000年1月1日〜今日までの範囲にあるか
            $birthDate = Carbon::createFromDate($year, $month, $day);
            $minDate = Carbon::create(2000, 1, 1);
            $maxDate = Carbon::today();

            if ($birthDate->lt($minDate) || $birthDate->gt($maxDate)) {
                $validator->errors()->add('birth_day', '生年月日は2000年1月1日から本日までの間で入力してください。');
            }
        });
    }
}
