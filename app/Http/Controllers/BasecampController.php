<?php

namespace App\Http\Controllers;

use Belvedere\Basecamp\BasecampFacade;
use Belvedere\Basecamp\Models\Document;
use Cache;
use Laravel\Socialite\Facades\Socialite;

class BasecampController extends Controller
{
    public function login() {
        return Socialite::driver('37signals')->stateless()->redirect();
    }

    public function callback() {
        $user = Socialite::driver('37signals')->stateless()->user();
        Cache::add('basecamp', json_encode($user));
        clock($user);
        return response()->json($user);
    }

    public function projects() {
        $user = json_decode(Cache::get('basecamp'), null);

        if (!$user)
            return request()->route('basecamp.login');

        BasecampFacade::init([
            'id' => $user->user->accounts[0]['id'],
            'href' => $user->user['accounts'][0]['href'],
            'token' => $user->token,
            'refresh_token' => $user->refreshToken,
        ]);

        $projects = BasecampFacade::projects();
        clock($projects->index());

        return response()->json($projects->index());
    }

    public function people() {
        $user = json_decode(Cache::get('basecamp'), true);

        if (!$user)
            return request()->route('basecamp.login');

        BasecampFacade::init([
            'id' => $user['user']['accounts'][0]['id'],
            'href' => $user['user']['accounts'][0]['href'],
            'token' => $user['token'],
            'refresh_token' => $user['refreshToken'],
        ]);

        $people = BasecampFacade::people();
        clock($people->index());

        return response()->json($people->index());
    }

    public function docs() {
        $user = json_decode(Cache::get('basecamp'), true);

        if (!$user)
            return request()->route('basecamp.login');

        BasecampFacade::init([
            'id' => $user['user']['accounts'][0]['id'],
            'href' => $user['user']['accounts'][0]['href'],
            'token' => $user['token'],
            'refresh_token' => $user['refreshToken'],
        ]);

        $project = BasecampFacade::projects()->index()->get(10);
        Document::class;

        clock($project->vault()->documents()->index());

        return response()->json($project->vault()->documents()->index());
    }
}
