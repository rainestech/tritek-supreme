<?php

namespace App\Http\Controllers;

use App\Jobs\AttachProjects;
use App\Jobs\People;
use App\Jobs\Projects;
use Belvedere\Basecamp\BasecampFacade;
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

        $params = [
            'id' => $user->user->accounts[0]['id'],
            'href' => $user->user['accounts'][0]['href'],
            'token' => $user->token,
            'refresh_token' => $user->refreshToken,
        ];

        People::dispatch($params);
        Projects::dispatch($params);
        AttachProjects::dispatch($params);

        return redirect()->route("basecamp.job");
    }

    public function runJobs() {
        \Artisan::call('queue:work --timeout=0');
        return response()->json([]);
    }

    public function projects() {
//        $user = json_decode(Cache::get('basecamp'), null);
//
//        if (!$user)
//            return request()->route('basecamp.login');

//        dd(Config::get('queue.default'));
//        exit();

        $params = [
            'id' => "3950847",
            'href' => "https://3.basecampapi.com/3950847",
            'token' => "BAhbB0kiAbB7ImNsaWVudF9pZCI6IjAwNWEwMmJmYjEzNGQ2NWVmNjFlZWUxOTJjMGY3MmVmMmRkNzI1NGIiLCJleHBpcmVzX2F0IjoiMjAyMS0wMy0xMVQxMzo1MDoxNloiLCJ1c2VyX2lkcyI6WzQzNjY5MjIyXSwidmVyc2lvbiI6MSwiYXBpX2RlYWRib2x0IjoiM2ZmNWJlZTMyNzUwMDYzZDg3ZDZjNjdkNjM0ZjgyODEifQY6BkVUSXU6CVRpbWUNbUkewB8GAskJOg1uYW5vX251bWkCMQI6DW5hbm9fZGVuaQY6DXN1Ym1pY3JvIgdWEDoJem9uZUkiCFVUQwY7AEY=--c9b2cf12971acc010c4f703b3ed1f7fa46c47f02",
            'refresh_token' => "BAhbB0kiAbB7ImNsaWVudF9pZCI6IjAwNWEwMmJmYjEzNGQ2NWVmNjFlZWUxOTJjMGY3MmVmMmRkNzI1NGIiLCJleHBpcmVzX2F0IjoiMjAzMS0wMi0yNVQxMzo1MDoxNloiLCJ1c2VyX2lkcyI6WzQzNjY5MjIyXSwidmVyc2lvbiI6MSwiYXBpX2RlYWRib2x0IjoiM2ZmNWJlZTMyNzUwMDYzZDg3ZDZjNjdkNjM0ZjgyODEifQY6BkVUSXU6CVRpbWUNLccgwDURAskJOg1uYW5vX251bWkCoQE6DW5hbm9fZGVuaQY6DXN1Ym1pY3JvIgdBcDoJem9uZUkiCFVUQwY7AEY=--2677f5d4e2982e18a1121a4b1feb3845d4abe125",
        ];

        // People::dispatch($params);
        // Projects::dispatch($params);
        AttachProjects::dispatch($params);

        // BasecampFacade::init([
        //     'id' => "3950847",
        //     'href' => "https://3.basecampapi.com/3950847",
        //     'token' => "BAhbB0kiAbB7ImNsaWVudF9pZCI6IjAwNWEwMmJmYjEzNGQ2NWVmNjFlZWUxOTJjMGY3MmVmMmRkNzI1NGIiLCJleHBpcmVzX2F0IjoiMjAyMS0wMy0xMVQxMzo1MDoxNloiLCJ1c2VyX2lkcyI6WzQzNjY5MjIyXSwidmVyc2lvbiI6MSwiYXBpX2RlYWRib2x0IjoiM2ZmNWJlZTMyNzUwMDYzZDg3ZDZjNjdkNjM0ZjgyODEifQY6BkVUSXU6CVRpbWUNbUkewB8GAskJOg1uYW5vX251bWkCMQI6DW5hbm9fZGVuaQY6DXN1Ym1pY3JvIgdWEDoJem9uZUkiCFVUQwY7AEY=--c9b2cf12971acc010c4f703b3ed1f7fa46c47f02",
        //     'refresh_token' => "BAhbB0kiAbB7ImNsaWVudF9pZCI6IjAwNWEwMmJmYjEzNGQ2NWVmNjFlZWUxOTJjMGY3MmVmMmRkNzI1NGIiLCJleHBpcmVzX2F0IjoiMjAzMS0wMi0yNVQxMzo1MDoxNloiLCJ1c2VyX2lkcyI6WzQzNjY5MjIyXSwidmVyc2lvbiI6MSwiYXBpX2RlYWRib2x0IjoiM2ZmNWJlZTMyNzUwMDYzZDg3ZDZjNjdkNjM0ZjgyODEifQY6BkVUSXU6CVRpbWUNLccgwDURAskJOg1uYW5vX251bWkCoQE6DW5hbm9fZGVuaQY6DXN1Ym1pY3JvIgdBcDoJem9uZUkiCFVUQwY7AEY=--2677f5d4e2982e18a1121a4b1feb3845d4abe125",
        // ]);

//        BasecampFacade::init([
//            'id' => $user->user->accounts[0]['id'],
//            'href' => $user->user['accounts'][0]['href'],
//            'token' => $user->token,
//            'refresh_token' => $user->refreshToken,
//        ]);

        // $projects = BasecampFacade::projects();
        // clock($projects->index()->get(0)->todoSet()->todoLists()->index());

        // clock($projects->index()->get(0)->todoSet());
        // clock($projects->index()->get(0));
        // clock($projects->index()->get(0)->messageBoard()->messages()->index());
        // clock(BasecampFacade::campfires(19568744)->index()->get(0)->lines());

        // return response()->json($projects->index()->get(0));
        return response()->json([]);
    }

    public function people() {
//        $user = json_decode(Cache::get('basecamp'), true);
//
//        if (!$user)
//            return request()->route('basecamp.login');
//
//        BasecampFacade::init([
//            'id' => $user['user']['accounts'][0]['id'],
//            'href' => $user['user']['accounts'][0]['href'],
//            'token' => $user['token'],
//            'refresh_token' => $user['refreshToken'],
//        ]);

        BasecampFacade::init([
            'id' => "3950847",
            'href' => "https://3.basecampapi.com/3950847",
            'token' => "BAhbB0kiAbB7ImNsaWVudF9pZCI6IjAwNWEwMmJmYjEzNGQ2NWVmNjFlZWUxOTJjMGY3MmVmMmRkNzI1NGIiLCJleHBpcmVzX2F0IjoiMjAyMS0wMy0xMVQxMzo1MDoxNloiLCJ1c2VyX2lkcyI6WzQzNjY5MjIyXSwidmVyc2lvbiI6MSwiYXBpX2RlYWRib2x0IjoiM2ZmNWJlZTMyNzUwMDYzZDg3ZDZjNjdkNjM0ZjgyODEifQY6BkVUSXU6CVRpbWUNbUkewB8GAskJOg1uYW5vX251bWkCMQI6DW5hbm9fZGVuaQY6DXN1Ym1pY3JvIgdWEDoJem9uZUkiCFVUQwY7AEY=--c9b2cf12971acc010c4f703b3ed1f7fa46c47f02",
            'refresh_token' => "BAhbB0kiAbB7ImNsaWVudF9pZCI6IjAwNWEwMmJmYjEzNGQ2NWVmNjFlZWUxOTJjMGY3MmVmMmRkNzI1NGIiLCJleHBpcmVzX2F0IjoiMjAzMS0wMi0yNVQxMzo1MDoxNloiLCJ1c2VyX2lkcyI6WzQzNjY5MjIyXSwidmVyc2lvbiI6MSwiYXBpX2RlYWRib2x0IjoiM2ZmNWJlZTMyNzUwMDYzZDg3ZDZjNjdkNjM0ZjgyODEifQY6BkVUSXU6CVRpbWUNLccgwDURAskJOg1uYW5vX251bWkCoQE6DW5hbm9fZGVuaQY6DXN1Ym1pY3JvIgdBcDoJem9uZUkiCFVUQwY7AEY=--2677f5d4e2982e18a1121a4b1feb3845d4abe125",
        ]);

        $people = BasecampFacade::people();

        foreach ($people->index() as $person) {
            clock($person->id);
        }

        clock($people->index()->get(10));
        clock($people->inProject(19568744));
        clock(BasecampFacade::projects()->show(19568744)->schedule()->show());

        return response()->json($people->index()->get(10)->name);
    }

    public function docs() {
//        $user = json_decode(Cache::get('basecamp'), true);
//
//        if (!$user)
//            return request()->route('basecamp.login');
//
//        BasecampFacade::init([
//            'id' => $user['user']['accounts'][0]['id'],
//            'href' => $user['user']['accounts'][0]['href'],
//            'token' => $user['token'],
//            'refresh_token' => $user['refreshToken'],
//        ]);

        BasecampFacade::init([
            'id' => "3950847",
            'href' => "https://3.basecampapi.com/3950847",
            'token' => "BAhbB0kiAbB7ImNsaWVudF9pZCI6IjAwNWEwMmJmYjEzNGQ2NWVmNjFlZWUxOTJjMGY3MmVmMmRkNzI1NGIiLCJleHBpcmVzX2F0IjoiMjAyMS0wMy0xMVQxMzo1MDoxNloiLCJ1c2VyX2lkcyI6WzQzNjY5MjIyXSwidmVyc2lvbiI6MSwiYXBpX2RlYWRib2x0IjoiM2ZmNWJlZTMyNzUwMDYzZDg3ZDZjNjdkNjM0ZjgyODEifQY6BkVUSXU6CVRpbWUNbUkewB8GAskJOg1uYW5vX251bWkCMQI6DW5hbm9fZGVuaQY6DXN1Ym1pY3JvIgdWEDoJem9uZUkiCFVUQwY7AEY=--c9b2cf12971acc010c4f703b3ed1f7fa46c47f02",
            'refresh_token' => "BAhbB0kiAbB7ImNsaWVudF9pZCI6IjAwNWEwMmJmYjEzNGQ2NWVmNjFlZWUxOTJjMGY3MmVmMmRkNzI1NGIiLCJleHBpcmVzX2F0IjoiMjAzMS0wMi0yNVQxMzo1MDoxNloiLCJ1c2VyX2lkcyI6WzQzNjY5MjIyXSwidmVyc2lvbiI6MSwiYXBpX2RlYWRib2x0IjoiM2ZmNWJlZTMyNzUwMDYzZDg3ZDZjNjdkNjM0ZjgyODEifQY6BkVUSXU6CVRpbWUNLccgwDURAskJOg1uYW5vX251bWkCoQE6DW5hbm9fZGVuaQY6DXN1Ym1pY3JvIgdBcDoJem9uZUkiCFVUQwY7AEY=--2677f5d4e2982e18a1121a4b1feb3845d4abe125",
        ]);

        $project = BasecampFacade::projects()->index()->get(10);

        clock($project->vault()->documents()->index());

        return response()->json($project->vault()->documents()->index());
    }
}
