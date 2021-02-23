<?php
namespace Rainestech\AdminApi\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Rainestech\AdminApi\Errors\FormValidationErrors;

class UsersRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
       $id = request('id');

        switch($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'PUT':
            case 'PATCH':
            case 'POST': {
                return [
                    'id'  => 'integer|exists:admin_users,id',
                    'firstName'  => 'required|between:3,100|string',
                    'lastName'  => 'required|between:3,100|string',
                    'email'  => 'required|email|unique:admin_users,email,'.$id,
                    'username'  => 'required|string|between:3,50|unique:admin_users,username,'.$id,
                    'password'  => 'nullable|string|between:6,100',
                    'oldPassword'  => 'nullable|string|between:6,100',
                    'role.*.id'  => 'integer|exists:admin_roles,id',
                    'status'  => 'boolean',
                ];
            }

            default:break;
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    protected function failedValidation(Validator $validator) {
        throw new FormValidationErrors($validator);
    }
}
