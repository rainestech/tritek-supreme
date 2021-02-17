<?php

namespace Rainestech\Authorization\Middleware;

use Rainestech\Authorization\Role;
use Closure;
use Illuminate\Http\Request;

class AccessMiddleware
{
    use Role;

    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param Closure $next
     * @param $role
     * @return mixed
     */
    public function handle($request, Closure $next, ...$role) {
        if (!$this->checkAccess($role)) {

            if ($request->wantsJson()) {
                return response('Unauthorized.', 401);
            }
            else {
                return redirect()->route('home')->with('msgAlert', [
                    'message' => 'Permission denied! If you think this is wrong, please contact your web administrator',
                    'type' => 'Error',
                    'header' => 'Permission Denied!'
                ]);
            }
        }

        return $next($request);
    }
}
