<?php


namespace Rainestech\Tasks\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Rainestech\AdminApi\Errors\FormValidationErrors;

class TasksRequest extends FormRequest
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
                    'id'  => 'integer|exists:tasks_tasks,id',
                    'name'  => 'required|between:3,250|string',
                    'taskNo'  => 'required|between:3,25|string',
                    'tab'  => 'required|between:3,25|string',
                    'description'  => 'required|string',
                    'assignedTo.*.id'  => 'integer|exists:admin_users,id',
                    'channel.id'  => 'required|integer|exists:profiles_channels,id',
                    'parent.id'  => 'integer|exists:tasks_tasks,id',
                    'docs.*.id'  => 'integer|exists:file_storage,id',
                    'dueDate'  => 'date|required',
                    'doneDate'  => 'nullable|date',
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
