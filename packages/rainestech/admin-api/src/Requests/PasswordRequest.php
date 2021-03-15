<?php

namespace Rainestech\AdminApi\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Rainestech\AdminApi\Errors\FormValidationErrors;

class PasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = request('id');

        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'PUT':
            case 'PATCH':
            case 'POST':
            {
                return [
                    'email' => 'required|email|exists:admin_users,email',
                    'otp' => 'required|string|between:3,50|exists:admin_users,lastPwd',
                    'password' => 'required|string|confirmed|between:6,100',
                ];
            }

            default:
                break;
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new FormValidationErrors($validator);
    }
}
