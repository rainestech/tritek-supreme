<?php

namespace Rainestech\Personnel\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Rainestech\AdminApi\Errors\FormValidationErrors;

class RecruiterRequest extends FormRequest {
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
                    'id'  => 'integer|exists:profiles_recruiters,id',
                    'companyName'  => 'required|between:3,250|string',
                    'description'  => 'required|between:3,250|string',
                    'email'  => 'required|email',
                    'website'  => 'nullable|between:3,100|string',
                    'industry'  => 'required|between:3,200|string',
                    'type'  => 'string|required|between:2,100',
                    'title'  => 'string|required|between:2,200',
                    'size'  => 'string|required|between:2,100',
                    'logo.id'  => 'integer|required|exists:file_storage,id',
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
