<?php

namespace Rainestech\AdminApi\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Rainestech\AdminApi\Entity\Roles;
use Rainestech\AdminApi\Entity\Tokens;
use Rainestech\AdminApi\Entity\Users;
use Rainestech\AdminApi\Notifications\EmailVerification;
use Rainestech\AdminApi\Requests\PasswordRequest;
use Rainestech\AdminApi\Requests\UsersRequest;
use Rainestech\AdminApi\Utils\ErrorResponse;
use Rainestech\AdminApi\Utils\LmsLogin;
use Rainestech\AdminApi\Utils\Login;
use Rainestech\AdminApi\Utils\Register;
use Rainestech\AdminApi\Utils\Security;
use Rainestech\Personnel\Entity\Candidates;

class UserApiController extends BaseApiController {
    use Login, Register, Security, ErrorResponse, LmsLogin;

    public function index() {
        return Users::all();
    }

    public function login(Request $request) {
        try {
            $this->validateLogin($request);
        } catch (ValidationException $e) {
            return $this->jsonError(422, $e->getMessage());
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            try {
                return $this->sendLockoutResponse($request);
            } catch (ValidationException $e) {
                return response(null, 403);
            }
        }

        if (!$testUser = Users::where('email', $request->input('username'))->first()) {
            if (!$testUser = Users::where('username', $request->input('username'))->first()) {
                return $this->loginLMS($request->input('username'), $request->input('password'));
            }
        }

        if ($this->attemptLogin($request)) {
            if ($this->checkHotList(auth('api')->id())) {
                return $this->sendFailedLoginResponse($request);
            }
            $user = auth('api')->user();

            if (!$user->companyName && !Candidates::where("email", $user->email)->orderBy('id', 'desc')->exists()) {
                return response()->json('Candidate profile not found!', 403);
            }

            return $this->prepareLoginResponse($user);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request) {
        $token = $request->bearerToken();
        $tokenData = Tokens::where('token', $token)->first();
        $tokenData->delete();
//        @ no longer used due to customization for WP passwords
        auth('api')->invalidate(true);

        return response()->json('logout');
    }

    public function renew(Request $request) {
        $tData = Tokens::where('token', $request->bearerToken())->first();

        if (!$tData) {
            return response()->setStatusCode(403);
        }

        if (!$user = Users::find($tData->userID))
            return response()->setStatusCode(403);

        $token = $this->generateToken($user);

        $tData->token = $token;
        $tData->save();

        return response()->json($token)->header('Authorization', 'Bearer ' . $token);
    }

    public function changePassword(Request $request) {
        $request->validate([
           'password' => 'required|string',
           'oldPassword' => 'required|string',
            'id' => 'integer|required|exists:admin_users,id'
        ]);

        $user = Users::find($request->input('id'));

        if ($user) {
            if (Hash::check($request->input('oldPassword'), $user->password)) {
                $user->password = Hash::make($request->input('password'));
                $user->save();

                return response()->json($user, 200);
            }

        }

        return response()->json([
            'error' => 'User Not Found',
            'message' => 'Can not change Password at this time, request another reset cod'],
            401);
    }

    public function me() {
//        @todo implement in middleware. set user base on token at that level
//        $token = \request()->bearerToken();
//        $dbToken =
        return response()->json(auth('api')->user());
    }

    public function recoverPassword(Request $request) {
        $request->validate(['email' => 'email|required']);

        $user = Users::where('email', $request->get('email'))
            ->first();

        if ($user) {
            $email = new EmailVerification();
            $email->resetPassword($user);
            return \response()->json($user);
        }

        return response()->json(['error' => 'Email Not Found', 'message' => 'User with provided email Not Found'], 422);
    }

    public function resetPassword(PasswordRequest $request)
    {
        $user = Users::where('email', $request->input('email'))
            ->where('lastPwd', $request->input('otp'))->first();

        if ($user) {
            if ($user->updated_at->addMinutes(20) < Carbon::now()) {
                $email = new EmailVerification();
                $email->resetPassword($user);
                abort(400, 'Expired Token, a new one has been regenerated');
            }

            clock($request->input('password'));
            $user->password = Hash::make($request->input('password'));
            $user->lastPwdChange = Carbon::now();
            $user->lastPwd = null;
            $user->save();

            return response()->json(['status' => 'ok']);
        }

        abort(422, 'Invalid Token! Can\'t verify email!');
        return;
    }

    public function verification(Request $request)
    {
        $user = Users::where('username', $request->input('username'))
            ->where('lastPwd', $request->input('code'))->first();

        if ($user) {
            if ($user->updated_at->addMinutes(20) < Carbon::now()) {
                $mail = new EmailVerification();
                $mail->sendVerification($user);
                abort(400, 'Expired Token, a new one has been generated');
            }

            $user->email_verified_at = Carbon::now();
            $user->status = true;
            $user->lastPwdChange = null;
            $user->save();

            return response()->json(['status' => 'ok']);
        }

        abort(422, 'Invalid Token! Can\'t verify email!');
        return;
    }

    public function regenerateToken(Request $request)
    {
        $user = Users::find($request->input('id'));

        if ($user) {
            if ($user->username != $request->input('username')) {
                abort(422, 'User Not Found');
            }

            $mail = new EmailVerification();
            $mail->sendVerification($user);
            return response()->json(['status' => 'ok']);
        }

        abort(422, 'User Not Found');
        return;
    }

    public function editMe(UsersRequest $request) {
        if (auth('api')->id() === $request->input('id')) {
            return $this->editUser($request);
        }

        return $this->jsonError(403, 'Unauthorized');
    }

    public function editUser(UsersRequest $request)
    {
        $user = Users::find($request->get('id'));
        $user->firstName = $request->get('firstName');
        $user->lastName = $request->get('lastName');
        $user->companyName = $request->get('companyName');
        $user->username = $request->get('username');
        $user->email = $request->get('email');
        $user->status = $request->get('status');

        if (strlen($request->get('password')) > 6) {
            $user->password = Hash::make($request->get('password'));
        }

        $user->save();

        if ($request->input('role') && Roles::where('role', $request->input('role'))->exists()) {
            $dbRoles = $user->roles;

            foreach ($dbRoles as $rol) {
                $user->roles()->detach($rol->id);
            }

            $role = Roles::where('role', $request->input('role'))->first();
            $user->roles()->attach($role->id);
        }

        return response()->json($user);
    }

    public function editRole(UsersRequest $request) {
        if (!$request->has('id')) {
            abort(422, 'Bad Request');
        }

        $user = Users::findOrFail($request->get('id'));

        foreach ($user->roles as $r) {
            $user->roles()->detach($r->id);
        }

        foreach ($request->get('roles') as $l) {
            $user->roles()->attach($l['id']);
        }

        return response()->json(Users::with('roles')->where('id', $user->id)->first());
    }

    public function remove($id) {
        $user = Users::findOrFail($id);

        $user->delete();
        return response()->json($user);
    }

    public function search($username, $type) {
        if ($type == "email") {
            if ($user = Users::where('email', $username)->first())
                return response()->json($user);

            abort(404, "User with email: $username not found");
        }

        if ($user = Users::where('username', $username)->first())
            return response()->json($user);

        abort(404, "User with username: $username not found");
        return;
    }

}
