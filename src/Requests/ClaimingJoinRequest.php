<?php

namespace Railroad\Referral\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClaimingJoinRequest extends FormRequest
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
            'name' => 'required',
            'g-recaptcha-response' => 'required',
             'email' => 'required|email|unique:' .
                config('referral.database_info_for_unique_user_email_validation.database_connection_name') .
                '.' .
                config('referral.database_info_for_unique_user_email_validation.table') .
                ',' .
                config('referral.database_info_for_unique_user_email_validation.email_column'),
            'referral_code' => 'required',
            'password' => 'required|confirmed|' .
                config('referral.password_creation_rules', 'min:8|max:128'),
        ];

    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.unique' => 'This email address is already in use. Try another friend!',
            'g-recaptcha-response.required' => 'Please complete the captcha',
        ];
    }

}
