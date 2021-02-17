<?php


namespace Rainestech\AdminApi\Utils;

use Illuminate\Http\Request;
use Rainestech\AdminApi\Entity\ActivityLog;
use Rainestech\AdminApi\Entity\Tokens;
use Rainestech\AdminApi\Entity\UserDevice;
use Rainestech\AdminApi\Entity\Users;
//use ReallySimpleJWT\Token;

trait TokenCheck
{
    protected function checkToken(Request $request) {
        if ($token = $request->bearerToken())
            return $token;

        if($token = $request->query('token'))
            return $token;

        return null;
    }

    protected function validateToken($token) {
        if ($issuedToken = Tokens::where('token', $token)->first()) {
            if ($user = Users::find($issuedToken->userID)) { // && Token::validate($issuedToken->token, env('JWT_SECRET'))) {
                $this->userActivity($user);
                return $issuedToken;
            }
            return null;
        }

        return null;
    }

    protected function compareDevices() {
        if (UserDevice::where('deviceRaw', \request()->userAgent())->where('token', \request()->bearerToken())->exists())
            return true;

        return false;
    }

    protected function userActivity(Users $user) {
        if (strtolower(\request()->getMethod()) == 'get')
            return;

        $userActivity = new ActivityLog();
        $userActivity->userAgent = \request()->userAgent();
        $userActivity->userID = $user->id;
        $userActivity->ip = $this->getIp();
        $userActivity->query = json_encode(\request()->query->all());
        $userActivity->payload = json_encode(\request()->all());
        $userActivity->url = \request()->url();
        $userActivity->requestMethod = \request()->getMethod();
        $userActivity->save();
    }

}
