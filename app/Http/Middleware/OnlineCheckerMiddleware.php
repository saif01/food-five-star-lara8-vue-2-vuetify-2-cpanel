<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Carbon\Carbon;

class OnlineCheckerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check()) {
            $data = User::findOrFail(Auth::user()->id);
            if($data){
                // $data->last_activity = Carbon::now()->format('Y-m-d H:i');
                $data->last_activity = Carbon::now()->addMinute('5')->format('Y-m-d H:i');
                $data->save();
            }
        }
        return $next($request);
    }
}
