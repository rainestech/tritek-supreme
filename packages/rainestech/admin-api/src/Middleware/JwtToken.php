<?php


namespace Rainestech\AdminApi\Middleware;


use Closure;
use Illuminate\Http\Request;
use Rainestech\AdminApi\Utils\ErrorResponse;
use Rainestech\AdminApi\Utils\Security;
use Rainestech\AdminApi\Utils\TokenCheck;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtToken extends BaseMiddleware {
    use Security, TokenCheck, ErrorResponse;

//    public function handle($request, Closure $next) {
//        $exRoutes = ['users/login'];
//
//        if (in_array($request->path(), $exRoutes))
//            return $next($request);
//
//        if (!$token = $this->checkToken($request)) {
//            return $this->jsonError(401, 'Unauthorized.');
//        }
//
//        if (!$this->validateToken($token)) {
//            return $this->jsonError(401, 'Unauthorized.');
//        }
//
//        return $next($request);
//    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     *
     * @throws UnauthorizedHttpException
     *
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $this->checkForToken($request);
        try {
            $this->auth->parseToken()->authenticate();
        } catch (JWTException $e) {
            throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
        }

        $token = $request->bearerToken();
        if ($token == null) {
            throw new UnauthorizedHttpException('token not set', 'Invalid Token', null, 401);
        }

        if (!$this->validateToken($token)) {
            throw new UnauthorizedHttpException('Token not found', 'Invalid Token', null, 401);
        }

//        if (!$this->verifyDbToken($token)) {
//            throw new UnauthorizedHttpException('Token not found', 'Invalid Token', null, 401);
//        }

//        // dd(auth('api')->id());
//        if ($this->checkHotList(auth('api')->id())) {
//            throw new UnauthorizedHttpException('Token Hotlisted', 'Invalid Token', null, 401);
//        }

        return $next($request);
    }


}
