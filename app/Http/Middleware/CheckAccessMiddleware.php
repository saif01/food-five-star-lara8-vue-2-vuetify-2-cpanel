<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $type)
    {
        // return $next($request);
        dd('redirect', $type, Auth::user()->user_type);


        if( Auth::user()->user_type )



        // if($type == 'op'){
        //     if(Auth::user()->hasOperator()){
        //         return $next($request);
        //     }
        // }elseif($type == 'ad'){
        //     if(Auth::user()->hasAdmin()){
        //         return $next($request);
        //     }
        // }elseif($type == 'wn'){
        //      if(Auth::user()->hasOwner()){
        //         return $next($request);
        //     }
        // }
        // else{
        //     return redirect('logout');
        // }

        // if( ! Auth::check() && ! Auth::user()->hasAdmin() ){
        //     //return $this->redirectTo = route('admin.dashboard') ;

        //    // return redirect()->route('dashboard');

        //    return redirect('/admin');

        //    // return $next($request);
            
        // } elseif( Auth::check() && Auth::user()->hasOperator() ){
        //     //return $this->redirectTo = route('admin.dashboard') ;

        //    // return redirect()->route('dashboard');

        //    return redirect('/');

        //    // return $next($request);
            
        // }
        
        // else{
           
        //    return back();
        
        // }

        return $next($request);
        

        

    }
}
