<?php

namespace Rainestech\AdminApi\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Rainestech\AdminApi\Errors\FormValidationErrors;

class RegistrationRequest extends FormRequest
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
                    'email' => 'required|email|unique:admin_users,email,' . $id,
                    'username' => 'required|string|between:3,50|unique:admin_users,username,' . $id,
                    'password' => 'required|string|confirmed|between:6,100',
                    'companyName' => 'required|string|between:10,200',
                    'description'  => 'required|between:3,250|string',
                    'website'  => 'nullable|between:3,100|string',
                    'industry'  => 'required|between:3,200|string',
                    'type'  => 'string|required|between:2,100',
                    'size'  => 'string|required|between:2,100',
                    'logo.id'  => 'integer|required|exists:file_storage,id',
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
