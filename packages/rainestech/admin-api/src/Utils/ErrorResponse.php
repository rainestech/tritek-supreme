<?php


namespace Rainestech\AdminApi\Utils;

use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

trait ErrorResponse
{
    public function jsonError($status, $message) {

        return response()->json(["status" => $status,
            "error" => Response::$statusTexts[$status],
            "message" => $message,
            "path" => request()->path(),
            "timestamp" => Carbon::now('UTC')])
            ->setStatusCode($status);
    }
}
