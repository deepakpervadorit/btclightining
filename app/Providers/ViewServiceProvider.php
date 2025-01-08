<?php
namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Sharing data with all views
        View::composer('*', function ($view) {
            $staffId = session('staff_id');  // Get the logged-in staff's ID
            
            // Get the role_id of the logged-in staff
            $roleId = DB::table('staff')
                ->where('id', $staffId)
                ->value('role_id');

            // Get the role name using the role_id
            $roleName = DB::table('roles')
                ->where('id', $roleId)
                ->value('name');

            // Get the permissions for that role
            $permissions = DB::table('permissions')
                ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
                ->where('permission_role.role_id', $roleId)
                ->pluck('permissions.name');  // This will give you a collection of permission names

            // Share the data with the view
            $view->with('roleName', $roleName)
                ->with('permissions', $permissions);
        });
    }

    public function register()
    {
        //
    }
}
