<?php

namespace App\Http\Middleware;

use Closure;
use DB;
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
        
        // get user role permissions
        $role = auth()->user()->role_id;
        $routeName = $request->route()[1]['as'];
        $permissions = DB::select('SELECT * from roles 
                        LEFT JOIN permission_role 
                        ON roles.id = permission_role.role_id 
                        RIGHT JOIN permissions 
                        ON permission_role.permission_id = permissions.id 
                        where roles.id = '.$role.' 
                        AND 
                        permissions.name = "'.$routeName.'"');


        // check if requested action is in permissions list
        if(!empty($permissions)){
        // authorized request
        return $next($request);
        }
    
        // none authorized request
        return response('Unauthorized Action', 403);

   
 }
}
