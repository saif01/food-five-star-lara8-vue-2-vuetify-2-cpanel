<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $data=null)
    {
        // return $next($request);
        // dd('redirect', Auth::user()->hasAdmin(), $data);
        if($data == 'Admin'){
            if(Auth::user()->hasAdmin()){
                return $next($request);
            }else{
                if(Auth::user()->hasOperator()){
                    return redirect('/');
                }elseif(Auth::user()->hasOwner()){
                    return redirect('/owner');
                }
            }
        }elseif($data == 'Owner'){
            if(Auth::user()->hasOwner()){
                return $next($request);
            }else{
                if(Auth::user()->hasAdmin()){
                    return redirect('/admin');
                }elseif(Auth::user()->hasOperator()){
                    return redirect('/');
                }
            }
        }elseif($data == 'Operator'){
            if(Auth::user()->hasOperator()){
                return $next($request);
            }else{
                if(Auth::user()->hasAdmin()){
                    return redirect('/admin');
                }elseif(Auth::user()->hasOwner()){
                    return redirect('/owner');
                }
            }
        }else{
           return response()->json(["message"=> "CPFiveStar Unauthorized Request."], 503);
        }

    }
}
