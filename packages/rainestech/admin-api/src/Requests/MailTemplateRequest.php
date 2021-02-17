<?php
namespace Rainestech\AdminApi\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Rainestech\AdminApi\Errors\FormValidationErrors;

class MailTemplateRequest extends FormRequest {
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
                    'name'  => 'required|between:3,100|string|unique:notifications_mail_templates,name,'.$id,
                    'subject'  => 'required|string',
                    'template'  => 'required|string',
                    'json'  => 'required|string',
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'id'    => 'required|integer|exists:notifications_mail_templates,id',
                    'name'  => 'required|between:3,100|string|unique:notifications_mail_templates,name,'.$id,
                    'subject'  => 'required|string',
                    'template'  => 'required|string',
                    'json'  => 'required|string'
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
