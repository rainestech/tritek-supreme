<?php

namespace Rainestech\AdminApi\Utils;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Rainestech\AdminApi\Entity\Roles;
use Rainestech\AdminApi\Entity\Users;
use Rainestech\AdminApi\Notifications\EmailVerification;
use Rainestech\AdminApi\Requests\RegistrationRequest;
use Rainestech\AdminApi\Requests\UsersRequest;
use Rainestech\Personnel\Entity\Recruiters;

trait Register {

    /**
     * Handle a registration request for the application.
     *
     * @param UsersRequest $request
     * @return JsonResponse|Response
     */
    public function register(UsersRequest $request) {
        event(new Registered($user = $this->create($request->all(), true)));

        foreach ($request->input('role') as $role) {
            $user->roles()->attach($role['id']);
        }

        if ($request->has('status')) {
            $user->status = $request->input('status');
            $user->firstName = $request->input('firstName');
            $user->lastName = $request->input('lastName');
            $user->save();
        }

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return new Response('', 201);
    }

    /**
     * Handle a registration request for quest registration
     *
     * @param RegistrationRequest $request
     * @return JsonResponse|Response
     */
    public function registerVerify(RegistrationRequest $request)
    {
        event(new Registered($user = $this->create($request->all(), false)));

//        $role = Roles::where('defaultRole', true)->first();
//
//        if ($role) {
//            $user->roles()->attach($role->id);
//        }

        $notification = new EmailVerification();
        $notification->sendVerification($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        $recruiters = new Recruiters($request->except(['logo', 'username', 'password']));
        $recruiters->userId = $request->input('user.id');
        $recruiters->fsId = $request->input('logo.id');
        $recruiters->save();

        $user->passportID = $request->input('logo.id');
        $user->update();

        return new Response('', 201);
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('api');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @param bool $status
     * @return Users
     */
    protected function create(array $data, bool $status)
    {
        return Users::create([
            'username' => $data['username'],
            'status' => $status,
            'adminVerified' => false,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function registered(Request $request, $user) {
        return response()->json($user);
    }
}
