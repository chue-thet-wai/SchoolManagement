<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;

class CheckAdminPermission
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
        $route = Route::getRoutes()->match($request);
        $currentroute = $route->getName();
        if ($currentroute == '') {
            $main_route = $request->path();
        } else {
            $currentroute = explode('.', $currentroute);
            $main_route = $currentroute[0];
        }   
    
        if ($main_route=="admin/logout") {
            Auth::logout();
            return redirect('/');
        }

        $permission = Permission::where('menu_route','LIKE',"%$main_route%")->first();
        if (Auth::user() &&  !empty($permission)) {
            $permission_id = $permission->id;
            $role_id = Auth::user()->role;

            $chkRolePermission = DB::table('role_permission')
                                ->where('role_id',$role_id)
                                ->where('permission_id',$permission_id)
                                ->whereNull('deleted_at')
                                ->first();
            if (!empty($chkRolePermission)) {
                return $next($request);
            } else {
                return redirect('/')->with('error','You have not admin access');
            }
        } else if (Auth::user() && empty($permission)) {
            return $next($request);
        }
    }
}
