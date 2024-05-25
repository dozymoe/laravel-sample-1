<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleManager = Role::create(['name' => 'manager']);
        $roleSupervisor = Role::create(['name' => 'supervisor']);
        $roleUser = Role::create(['name' => 'user']);

        $permViewUsers = Permission::create(['name' => 'view users']);
        $permEditUsers = Permission::create(['name' => 'edit users']);
        $permViewSupervisors = Permission::create(['name' => 'view supervisors']);
        $permEditSupervisors = Permission::create(['name' => 'edit supervisors']);
        $permViewManagers = Permission::create(['name' => 'view managers']);
        $permEditManagers = Permission::create(['name' => 'edit managers']);
        $permViewAdmins = Permission::create(['name' => 'view admins']);
        $permEditAdmins = Permission::create(['name' => 'edit admins']);

        $roleAdmin->syncPermissions([$permViewUsers, $permEditUsers,
            $permViewSupervisors, $permEditSupervisors, $permViewManagers,
            $permEditManagers, $permViewAdmins, $permEditAdmins]);
        $roleManager->syncPermissions([$permViewSupervisors]);
        $roleSupervisor->syncPermissions([$permViewUsers]);
    }

    public function down(): void
    {
        Role::where('name', 'admin')->delete();
        Role::where('name', 'manager')->delete();
        Role::where('name', 'supervisor')->delete();
        Role::where('name', 'user')->delete();

        Permission::where('name', 'view users')->delete();
        Permission::where('name', 'edit users')->delete();
        Permission::where('name', 'view supervisors')->delete();
        Permission::where('name', 'edit supervisors')->delete();
        Permission::where('name', 'view managers')->delete();
        Permission::where('name', 'edit managers')->delete();
        Permission::where('name', 'view admins')->delete();
        Permission::where('name', 'edit admins')->delete();
    }
};
