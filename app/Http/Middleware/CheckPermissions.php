<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPermissions
{
    public function handle($request, Closure $next, $permission)
    {
        $userType = session('user_type');
        $permissions = [];

        if ($userType == 'user') {
            $permissions = json_decode(Auth::user()->permissions, true);
        } elseif ($userType == 'driver') {
            $driver = \DB::table('drivers')->where('id', Auth::id())->first();
            $permissions = json_decode($driver->permissions, true);
        }

        // Check if the user has full access or specific permission
        if (empty($permissions) || in_array($permission, $permissions)) {
            return $next($request);
        }

        return redirect('/')->withErrors('You do not have permission to access this page.');
    }
}
