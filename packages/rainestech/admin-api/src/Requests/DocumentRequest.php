<?php


namespace Rainestech\AdminApi\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Rainestech\AdminApi\Errors\FormValidationErrors;

class DocumentRequest extends FormRequest {
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
            case 'PUT':
            case 'PATCH':
            case 'POST': {
                return [
                    'id'    => 'integer|exists:admin_documents,id',
                    'file.id'    => 'integer|required|exists:file_storage,id',
                    'name'  => 'required|between:3,100|string',
                    'description'  => 'required|string|between:3,200',
                    'private'  => 'required|boolean',
                ];
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
