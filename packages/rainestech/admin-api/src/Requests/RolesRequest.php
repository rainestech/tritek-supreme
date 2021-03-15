<?php
namespace Rainestech\AdminApi\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Rainestech\AdminApi\Errors\FormValidationErrors;

class RolesRequest extends FormRequest {
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $id = $this->request->get('id');

        switch($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'id'    => 'integer|exists:admin_roles,id',
                    'role'  => 'required|between:3,100|string|unique:admin_roles,role,'.$id,
                    'privileges.*.id'  => 'required|integer|exists:admin_privilege,id',
                    'privileges.*.privilege'  => 'required|string|max:100',
                    'privileges.*.module'  => 'required|string|max:200',
                    'privileges.*.url'  => 'required|string|max:200',
                    'privileges.*.app'  => 'required|string|max:200',
                    'privileges.*.name'  => 'required|string|max:200',
                    'privileges.*.orderNo'  => 'required|integer',
                    'privileges.*.hasChildren'  => 'required|boolean',
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'id'    => 'required|integer|exists:admin_roles,id',
                    'role'  => 'required|between:3,100|string|unique:admin_roles,role,'.$id,
                    'privileges.*.id'  => 'required|integer|exists:admin_privilege,id',
                    'privileges.*.privilege'  => 'required|string|max:100',
                    'privileges.*.module'  => 'required|string|max:200',
                    'privileges.*.url'  => 'required|string|max:200',
                    'privileges.*.app'  => 'required|string|max:200',
                    'privileges.*.name'  => 'required|string|max:200',
                    'privileges.*.orderNo'  => 'required|integer',
                    'privileges.*.hasChildren'  => 'required|boolean',                ];
            }
            default:{
                return [];
                break;
            }
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
