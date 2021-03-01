<?php


namespace Rainestech\AdminApi\Errors;


use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Validation\Validator;

class FormValidationErrors extends Exception {
    protected $validator;

    protected $code = 422;

    public function __construct(Validator $validator) {
        $this->validator = $validator;
    }

    public function render() {
        // return a json with desired format
        $str = "";
        foreach ($this->validator->errors()->getMessages() as $k => $e) {
            $str .= strtoupper($k) . ": " . $e[0] . " \n";
        }
        return response()->json(["status" => 422,
            "error" => "Input Validation Error",
            "message" => $str,
            "path" => request()->path(),
            "timestamp" => Carbon::now('UTC')])
            ->setStatusCode(422);
    }
}
