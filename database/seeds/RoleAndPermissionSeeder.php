<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        /**
         * Permissions
         */
        $p = array(
            'Users'=>[
                'Browse User',
                'Create User',
                'Edit User',
                'View User',
                'Delete User',
                'Restore User'
            ]
        );

        /**
         * Create Permissions
         */
        foreach ($p as $items){
            foreach ($items as $item){
                Permission::create(['name'=>$item]);
            }
        }

        /**
         * Role: Super Admin
         */
        Role::create(['name'=>'Super Admin']);

        /**
         * Role: Admin
         */
        $role = Role::create(['name'=>'Admin']);
        $role->givePermissionTo([
            $p['Users']
        ]);

        /**
         * Role: User
         */
        Role::create(['name'=>'Agent']);
    }
}
