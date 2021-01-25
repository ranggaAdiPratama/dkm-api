<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Role;
use App\Models\Permission;
class RolesAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Pre-Middleware Action

        $response = $next($request);

        // Post-Middleware Action
        // get user role permissions
        $role = Role::findOrFail(auth()->user()->role_id);
        $permissions = $role->permissions;
        print_r($role);
        exit();  
        // get requested action
        $actionName = $request->path();
        
        // check if requested action is in permissions list
        if (is_array($permissions) || is_object($permissions))
    {
        foreach ($permissions as $permission)
        {
        $name = $permission->name; 
        if ($actionName == $name )
        {
        // authorized request
        return $next($request);
        }
        }
        // none authorized request
        return response('Unauthorized Action', 403);

        return $response;
    }
 }
}