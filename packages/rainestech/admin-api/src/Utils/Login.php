<?php

namespace Rainestech\AdminApi\Utils;

use Illuminate\Http\Request;
use Rainestech\AdminApi\Entity\LoginLog;

trait Login
{
    use ThrottlesLogins;

    protected $token;
    protected $user;

    private function generateToken($user) {
//        $payload = [
//            'iat' => time(),
//            'uid' => $user->id,
//            'exp' => null,
//            'iss' => 'myTritek',
//            'sub' => $user->email
//        ];
//
//        try {
//            clock(env('JWT_SECRET'));
//            return Token::customPayload($payload, env('JWT_SECRET'));
//        } catch (ValidateException $e) {
//            return $this->jsonError(500, $e->getMessage());
//        }
        return auth('api')->refresh(true, true);
    }

//    protected function attemptLogin(Request $request) {
//        if (!$user = Users::where('email', $request->input('username'))->first()) {
//            if (!$user = Users::where('username', $request->input('username'))->first()) {
//                return false;
//            }
//        }
//
//        if (WpPassword::check($request->input('password'), $user->password)) {
//            $this->token = $this->generateToken($user);
//            $this->user = $user;
//            auth()->loginUsingId($user->id);
//
//            return true;
//        }
//
//        return false;
//    }

    /**
     * Attempt to log the user into the application.
     *
     * @param Request $request
     *
     * @return bool
     */
    protected function attemptLogin(Request $request) {
        if ($token = auth('api')->attempt(
            $this->credentialsName($request))) {

            $this->token = $token;
            return true;
        }

        if ($token = auth('api')->attempt(
            $this->credentialsEmail($request))) {

            if (auth('api')->user()->adminVerified) {
                $this->token = $token;
                return true;
            }
        }

        return false;
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param Request $request
     *
     * @return array
     */
    protected function credentialsEmail(Request $request) {
        return ['email' => $request->get('username'), 'password' => $request->get('password')];
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param Request $request
     *
     * @return array
     */
    protected function credentialsName(Request $request) {
        return ['username' => $request->get('username'), 'password' => $request->get('password')];
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username() {
        return 'username';
    }

    public function sendLoginResponse(Request $request, $token) {
        $this->clearLoginAttempts($request);

        $loginLog = new LoginLog();
        $loginLog->userId = $this->guard()->id();
        $loginLog->save();

        return response()->json( $this->guard()->user())->header('Authorization', 'Bearer '.$token)->header('test', 'test');
    }

    public function sendFailedLoginResponse(Request $request) {
        return response('Password Error', 403);
    }

    public function redirectPath() {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }

    /**
     * Validate the user login request.
     *
     * @param Request $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request) {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }
}
