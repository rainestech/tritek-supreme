<?php

namespace Rainestech\AdminApi\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Rainestech\AdminApi\Entity\Roles;
use Rainestech\AdminApi\Entity\Tokens;
use Rainestech\AdminApi\Entity\UserDevice;
use Rainestech\AdminApi\Entity\Users;
use Rainestech\Personnel\Entity\Candidates;

trait LmsLogin
{
    private $baseUrl = "https://api.mytritek.co.uk/v1";

    protected function loginLMS($username, $password) {
        $ping = $this->ping();
        $response = Http::withToken($ping)->post($this->baseUrl . "/login",
            ['username' => $username, 'password' => $password]);

        if ($response->status() == 200) {
            $resp = collect($response->json());

            if (Users::where('username', $resp['username'])->exists()) {
                return $this->sendFailedLoginResponse(request());
            }

            $user = new Users();

            $user->status = $resp['status'] == 'active';
            $user->username = $resp['username'];
            $user->firstName = $resp['firstName'];
            $user->lastName = $resp['lastName'];
            $user->email = $resp['email'];
            $user->adminVerified = $resp['status'] == 'active';
            $user->password = Hash::make($password);
            $user->email_verified_at = Carbon::now();

            $user->save();

            $this->token = auth('api')->login($user);
            clock($token);
            if($role = Roles::find(3)) {
                $user->roles()->attach($role->id);
            }

            if (!$this->linkCandidateProfile($user)) {
                return response()->json('Candidate profile not found!', 403);
            }

            return $this->prepareLoginResponse($user);
        }

        return $this->sendFailedLoginResponse(request());
    }

    private function linkCandidateProfile(Users $user) {
        if ($candidate = Candidates::where("email", $user->email)->orderBy('id', 'desc')->first()) {
            $candidate->userId = $user->id;
            $candidate->update();

            $user->avatar = $candidate->avatar;
            $user->save();
            return true;
        }

        return false;
    }

    protected function ping() {
        if (!$ping = Cache::get("ping", false)) {
            $response = Http::get($this->baseUrl . '/ping');
            $ping = $response->json()['uuid'];
            Cache::add("ping", $ping);
        }

        return $ping;
    }

    protected function prepareLoginResponse(Users $user) {
        $token = Tokens::where('userID', $user->id)->first();
        if (!$token) {
            $token = new Tokens();
        }

        $token->ip = $this->getIp();
        $token->device = $this->getDeviceDetails();
        $token->userID = $user->id;
        $token->token = $this->token;
        $token->save();



        $location = $this->getLocation();
        $userDevice = UserDevice::where('userID', $user->id)
            ->where('deviceRaw', request()->userAgent())
            ->where('ip', $this->getIp())
            ->first();

        if (!$userDevice) {
            $userDevice = new UserDevice();
            $userDevice->fill($location);
            $userDevice->userID =  $user->id;
            $userDevice->token = $token->token;
//                dd(auth('api')->id());
            // @todo send mail
        }

        $userDevice->lastLogin = Carbon::now('UTC');
        $userDevice->token = $this->token;
        $userDevice->save();

        return $this->sendLoginResponse(request(), $this->token);
    }
}
