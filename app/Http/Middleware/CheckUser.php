<?php

namespace App\Http\Middleware;

use App\Models\User\UserTokenModel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Auth\Factory as Auth;

class CheckUser
{

    protected $auth;
    function __construct(Auth $auth){
        $this->auth = $auth;
        $this->userTokenModel = new UserTokenModel();
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard = NULL): Response
    {

        $userToken = $this->userTokenModel->getData('checkToken', ['token' => $request->bearerToken()]);
        
        if(!empty($userToken)){
            return $next($request);
        }
        if($this->auth->guard($guard)->guest()) {
            return response()->json(['message' => ['Unauthorized']], 401);
        }
        return $next($request);
    }
}
