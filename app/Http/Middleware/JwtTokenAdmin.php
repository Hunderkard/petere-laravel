<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtTokenAdmin
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
            if ($request->user()->level != 0){
                return response()->json(['Ojito' => 'No tienes poder aquí.']);
            }
        } catch (Exception $e) {
            if ($e){
                return response()->json(['Ojito' => 'Hubo una excepción.']);
            }else{
                return response()->json(['Ojito' => 'Hubo un error.']);
            }
        }
        return $next($request);
    }
}