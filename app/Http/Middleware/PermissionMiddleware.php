<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$data)
    {
        // dd('Permission Middleware', $data);
        if($data){
            $autherity = ['Administrator','Admin'];
            $finalPermits = array_merge($autherity, $data);
            // dd($finalPermits);
            if(Auth::user()->hasAnyRoles($finalPermits)){
                return $next($request);
            }
        }else{
           return response()->json(["message"=> "CPFiveStar Unauthorized Request."], 503);
        }
    }
}
