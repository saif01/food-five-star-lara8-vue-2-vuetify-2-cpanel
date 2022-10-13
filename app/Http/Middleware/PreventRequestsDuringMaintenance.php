<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array<int, string>
     */
    protected $except = [
        //All Admin routes are work in Maintenance Mode
        'login', 'login_action', 'logout', 'auth_check', 'admin', 'admin/*', 'common/*', 'api/*'
    ];
}
