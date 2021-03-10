<?php


namespace Rainestech\AdminApi\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Rainestech\AdminApi\Errors\FormValidationErrors;

class StorageRequest extends FormRequest {
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {

        switch($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'id'    => 'integer|exists:file_storage,id',
                    'name'  => 'required|between:3,250|string',
                    'tag'  => 'required|string|between:3,100',
                    'file'  => 'required',
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'id'    => 'integer|required|exists:admin_fs_storage,id',
                    'name'  => 'required|between:3,100|string',
                    'tag'  => 'required|string|between:3,100',
                    'file'  => 'required',          ];
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
