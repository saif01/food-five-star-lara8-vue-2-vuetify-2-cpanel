<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Illuminate\Auth\AuthenticationException;
use Response;
use Illuminate\Support\Facades\Auth;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }

    // protected function unauthenticated($request, AuthenticationException $exception)
    // {
    //     // return $request->expectsJson()
    //     //             ? response()->json(['message' => $exception->getMessage()], 401)
    //     //             : redirect()->guest(route('login'));

    //     if($request->expectsJson()) {
    //         return response()->json(['message' =>  $exception->getMessage()],401);
    //     }

    //     // $guard = Arr::get($exception->guards(), 0);

    //     // switch ($guard) {
    //     //     case 'admin':
    //     //     $login = 'admin.login';
    //     //     break;
    //     //     case 'vendor':
    //     //     $login = 'vendor.login';
    //     //     break;
            
    //     //     default:
    //     //     $login = 'login';
    //     //     break;
    //     // }

    //        // if(Auth::user()->hasAdmin()){
    //          //   return $this->redirectTo = route('admin.dashboard');

    //             //     return redirect('/admin');
                    
    //             // }



    //    // return redirect()->guest(route($login));

    // }
}
