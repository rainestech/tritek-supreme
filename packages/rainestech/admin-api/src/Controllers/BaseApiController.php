<?php


namespace Rainestech\AdminApi\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Client\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Rainestech\AdminApi\Utils\ErrorResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BaseApiController extends BaseController{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ErrorResponse;

    public function errorResponse(Throwable $e) {
        $statusCode = $this->isHttpException($e) ? $e->getStatusCode() : 500;
        $response = Response::$statusTexts[$statusCode];

        return response()->json(["status" => $statusCode,
            "error" => $response,
            "message" => $e->getMessage(),
            "path" => request()->path(),
            "timestamp" => Carbon::now('UTC')])
            ->setStatusCode($statusCode);
    }

    public function sendResetEmail($email, $token)
    {
        //Retrieve the user from the database
        $user = DB::table('users')->where('email', $email)->select('firstname', 'email')->first();
        //Generate, the password reset link. The token generated is embedded in the link
        $link = config('base_url') . 'password/reset/' . $token . '?email=' . urlencode($user->email);

        try {
            //Here send the link with CURL with an external email API
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
