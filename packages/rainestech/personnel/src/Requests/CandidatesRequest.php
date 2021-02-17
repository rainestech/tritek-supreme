<?php

namespace Rainestech\Personnel\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Rainestech\AdminApi\Errors\FormValidationErrors;

class CandidatesRequest extends FormRequest {
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
                    'id'  => 'integer|exists:members_personnel,id',
                    'description'  => 'required|between:3,250|string',
                    'role'  => 'required|between:3,200|string',
                    'address'  => 'required|between:3,200|string',
                    'city'  => 'string|required|between:6,100',
                    'country'  => 'string|required|between:6,100',
                    'user.id'  => 'integer|required|exists:admin_users,id',
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
