<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class InjectCompanyName
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
        $companyId = session('company_id');
        $companyname = DB::table('companies')  
        ->select('company_name')
        ->where('id', $companyId)->first(); 
        // Share company name with all views
        View::share('companyName', $companyname ? $companyname->company_name : 'Unknown Company');

        return $next($request);
    }
}
