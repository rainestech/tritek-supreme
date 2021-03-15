<?php
namespace Rainestech\AdminApi\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Rainestech\AdminApi\Errors\FormValidationErrors;

class SmsTemplateRequest extends FormRequest {
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
                    'name'  => 'required|between:3,100|string|unique:notifications_sms,name,'.$id,
                    'template'  => 'required|string',
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'id'    => 'required|integer|exists:notifications_sms,id',
                    'name'  => 'required|between:3,100|string|unique:notifications_sms,name,'.$id,
                    'template'  => 'required|string',
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
