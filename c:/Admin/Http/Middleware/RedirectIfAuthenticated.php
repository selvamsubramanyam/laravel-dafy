<?php

namespace Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use SiteHelper;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::guard('admin')->check()) {
            
            if (!Auth::guard('seller')->check()) {
        
                if ($request->ajax()) { 
                return response([
                    'error' => 'unauthorized',
                    'error_description' => 'Failed authentication.',
                    'data' => [],
                ], 401);
                } else {
                    if($request->route()->getPrefix() == '/admin')
                    {
                        if(!Auth::guard($guard)->check())
                        {
                           Auth::guard('seller')->logout();
                           return redirect('/admin/login');
                        }else{
                            Auth::guard('seller')->logout();
                            return redirect('/admin/home');
                        }
                    }else{
                        if(!Auth::guard($seller)->check())
                        {
                            Auth::guard('admin')->logout();
                           return redirect('/seller/login');
                        }else{
                            Auth::guard('admin')->logout();
                            return redirect('/seller/home');
                        }
                       // return redirect('/seller/login');
                    }
                }

            }

        }
        
    
            if(Auth::guard('admin')->check())
            {
                if($request->route()->getPrefix() == '/seller')
                {
                    Auth::guard('admin')->logout();
                    return redirect('/seller/login');
                }
            }else{
                if($request->path()=='admin/home')
                {
                    Auth::guard('seller')->logout();
                    return redirect('/admin/login');
                }
            }
        
      
        return $next($request);
    }
}
