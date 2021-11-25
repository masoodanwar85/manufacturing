<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class AuthGates
{
    public function handle($request, Closure $next)
    {
        // $user = \Auth::user();
        //
        // if (!app()->runningInConsole() && $user) {
        //     $roles            = Role::with('permissions')->get();
        //     $permissionsArray = [];
        //
        //     foreach ($roles as $role) {
        //         foreach ($role->permissions as $permissions) {
        //             $permissionsArray[$permissions->title][] = $role->id;
        //         }
        //     }
        //
        //     foreach ($permissionsArray as $title => $roles) {
        //         Gate::define($title, function (\App\User $user) use ($roles) {
        //             return count(array_intersect($user->roles->pluck('id')->toArray(), $roles)) > 0;
        //         });
        //     }
        // }

        $user = \Auth::user();

		$rawSQL = "
			SELECT LOWER(CONCAT_WS('_',privilege.privilegeCode,accessLevel.accessLevel)) AS permission
			FROM userRole
			INNER JOIN rolePrivilege ON rolePrivilege.roleID = userRole.roleID
			INNER JOIN privilege ON privilege.privilegeID = rolePrivilege.privilegeID
			INNER JOIN accessLevel ON accessLevel.accessLevelID = privilege.accessLevelID
			WHERE userRole.userID = ";

        if (!app()->runningInConsole() && $user) {
			$rawSQL .= $user->userID;
            $allPrivileges = \App\Models\Privilege::with('accessLevel')->get();
            $permissionsArray = [];

            foreach ($allPrivileges as $privilege) {
				array_push($permissionsArray,strtolower($privilege->privilegeCode.'_'.$privilege->accessLevel->accessLevel));
            }

			$userPermissions = DB::select($rawSQL);

			$aryUserPermissions = [];
			foreach ($userPermissions as $userPermission) {
				array_push($aryUserPermissions,$userPermission->permission);
			}

			foreach ($permissionsArray as $permission) {
				Gate::define($permission, function (\App\Models\User $user) use ($permission,$aryUserPermissions) {
                    return count(array_intersect($aryUserPermissions,array($permission))) > 0;
                });
            }

			// Settings stuff
			\App\Services\SettingService::initializeSettings();
        }
        return $next($request);
    }
}
