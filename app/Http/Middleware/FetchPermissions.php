<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FetchPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $permissions = [];

        if (Auth::check()) {
            $userType = session('user_type');

            if ($userType == 'user') {
                $permissions = json_decode(Auth::user()->permissions, true);
            } elseif ($userType == 'driver') {
                $driver = \DB::table('drivers')->where('id', Auth::id())->first();
                if ($driver) {
                    $permissions = json_decode($driver->permissions, true);
                }
            }
        }

        // Share permissions with all views
        view()->share('permissions', $permissions);

        return $next($request);
    }
}
