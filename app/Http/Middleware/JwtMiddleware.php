<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            // $request->merge(['user' => auth('api')->user()]);
            //ac Arriba por si no funciona bien el $user.
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['Ojito' => 'El Token no es valido.']);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['Ojito' => 'El Token ha expirado.']);
            }else{
                return response()->json(['Ojito' => 'El Token no se encontró.']);
            }
        }
        return $next($request);
    }
}
